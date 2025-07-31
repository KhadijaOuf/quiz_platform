import React, { useState } from 'react'

export default function ModaleQuestion({ onClose, onSave }) {
  const [questionData, setQuestionData] = useState({
    enonce: '',
    type: 'single',
    note: 1,
    options: [{ text: '', correct: false, note_partielle: 0 }],
    vraiFaux: null,
  })

  const handleChange = (field, value) => {
    setQuestionData(prev => ({ ...prev, [field]: value }))
  }

  const updateOption = (index, field, value) => {
    setQuestionData(prev => {
      const newOptions = [...prev.options]
      newOptions[index][field] = value
      return { ...prev, options: newOptions }
    })
  }

  const addOption = () => {
    setQuestionData(prev => ({
      ...prev,
      options: [...prev.options, { text: '', correct: false, note_partielle: 0 }]
    }))
  }

  const removeOption = (index) => {
    setQuestionData(prev => ({
      ...prev,
      options: prev.options.filter((_, i) => i !== index)
    }))
  }

  // Calcul automatique de la note totale pour QCM multiple
  const totalNotePartielle = questionData.options
    .filter(opt => opt.correct)
    .reduce((sum, opt) => sum + (parseFloat(opt.note_partielle) || 0), 0)

  // Empêcher modification manuelle de la note totale si type multiple
  const handleNoteChange = (value) => {
    if (questionData.type === 'multiple') {
      // Ne rien faire, la note est calculée automatiquement
      return
    }
    handleChange('note', value)
  }

  const handleSubmit = (e) => {
    e.preventDefault()
    if (!questionData.enonce.trim()) return alert("L'énoncé est requis")

    const data = {
      enonce: questionData.enonce,
      type: questionData.type,
      // Pour multiple on calcule la note, sinon on prend la note manuelle
      note: questionData.type === 'multiple' ? totalNotePartielle : questionData.note,
    }

    if (questionData.type === 'single' || questionData.type === 'multiple') {
      const validOptions = questionData.options.filter(opt => opt.text.trim() !== '')
      if (validOptions.length < 2) return alert("Minimum deux options")
      if (!validOptions.some(opt => opt.correct)) return alert("Marque une réponse correcte")

      data.options = validOptions.map(opt => ({
        text: opt.text,
        correct: opt.correct,
        note_partielle: opt.correct && questionData.type === 'multiple' ? opt.note_partielle : 0
      }))
    }

    if (questionData.type === 'vrai_faux') {
      if (questionData.vraiFaux === null) return alert("Choisis Vrai ou Faux comme bonne réponse")
      data.correctAnswer = questionData.vraiFaux
    }

    onSave(data)
  }

  const typeOptions = [
    { label: 'QCM - Unique', value: 'single' },
    { label: 'QCM - Multiple', value: 'multiple' },
    { label: 'Vrai / Faux', value: 'vrai_faux' },
    { label: 'Réponse courte', value: 'text' },
  ]

  const renderTypeFields = () => {
    if (questionData.type === 'single' || questionData.type === 'multiple') {
      return (
        <div className="space-y-2">
          <label className="block font-medium">Options</label>
          {questionData.options.map((opt, idx) => (
            <div key={idx} className="flex items-center space-x-2">
              <input
                type={questionData.type === 'single' ? 'radio' : 'checkbox'}
                name="correct"
                checked={opt.correct}
                onChange={() => {
                  if (questionData.type === 'single') {
                    const updated = questionData.options.map((o, i) => ({
                      ...o,
                      correct: i === idx,
                      note_partielle: i === idx ? questionData.note : 0
                    }))
                    setQuestionData(prev => ({ ...prev, options: updated }))
                  } else {
                    updateOption(idx, 'correct', !opt.correct)
                  }
                }}
                className="accent-orange-600 focus:ring-orange-500 checked:bg-orange-600"
              />
              <input
                type="text"
                className="flex-1 border-none p-1 rounded focus:ring-orange-500 focus:border-orange-500 transition"
                value={opt.text}
                placeholder={`Option ${idx + 1}`}
                onChange={(e) => updateOption(idx, 'text', e.target.value)}
              />
              {questionData.type === 'multiple' && opt.correct && (
                <input
                  type="number"
                  min={0}
                  step={0.1}
                  className="w-20 border-none p-1 rounded focus:ring-orange-500 focus:border-orange-500"
                  value={opt.note_partielle ?? 0}
                  onChange={(e) => updateOption(idx, 'note_partielle', parseFloat(e.target.value))}
                  placeholder="Note"
                />
              )}
              <button
                type="button"
                onClick={() => removeOption(idx)}
                className="text-red-500 hover:text-red-600 font-bold text-3xl"
              >
                ×
              </button>
            </div>
          ))}
          <button
            type="button"
            onClick={addOption}
            className="text-orange-600 text-sm hover:underline mt-1"
          >
            + Ajouter une option
          </button>

          {/* Note Totale placée ici */}
          <div className="mt-4">
            <label className="block font-medium mb-1">Note Totale</label>
            {questionData.type === 'multiple' ? (
              <input
                type="number"
                className="w-20 border rounded p-1 bg-gray-100 cursor-not-allowed"
                value={totalNotePartielle}
                disabled
                readOnly
              />
            ) : (
              <input
                type="number"
                min={0}
                step={0.1}
                className="w-20 border rounded p-1 focus:ring-orange-500 focus:border-orange-500 transition"
                value={questionData.note}
                onChange={(e) => handleNoteChange(parseFloat(e.target.value))}
              />
            )}
          </div>
        </div>
      )
    }

    if (questionData.type === 'vrai_faux' || questionData.type === 'text') {
      return (
        <div className="space-y-4">
          {questionData.type === 'vrai_faux' && (
            <div className="mt-2 space-x-4">
              <label className="block font-medium mb-1">Bonne réponse :</label>
              <label className="inline-flex items-center space-x-1">
                <input
                  type="radio"
                  checked={questionData.vraiFaux === true}
                  className="accent-orange-600 focus:ring-orange-500 checked:bg-orange-600"
                  onChange={() => handleChange('vraiFaux', true)}
                />
                <span>Vrai</span>
              </label>
              <label className="inline-flex items-center space-x-1">
                <input
                  type="radio"
                  checked={questionData.vraiFaux === false}
                  className="accent-orange-600 focus:ring-orange-500 checked:bg-orange-600"
                  onChange={() => handleChange('vraiFaux', false)}
                />
                <span>Faux</span>
              </label>
            </div>
          )}

          {/* Note Totale pour vrai/faux et réponse courte */}
          <div>
            <label className="block font-medium mb-1">Note Totale</label>
            <input
              type="number"
              min={0}
              step={0.1}
              className="w-20 border rounded p-1 focus:ring-orange-500 focus:border-orange-500 transition"
              value={questionData.note}
              onChange={(e) => handleNoteChange(parseFloat(e.target.value))}
            />
          </div>
        </div>
      )
    }

    return null
  }

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div className="bg-white rounded shadow-lg w-full max-w-2xl p-6">
        <h2 className="text-lg font-bold mb-4">Nouvelle Question</h2>

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block font-medium">Énoncé de la question</label>
            <textarea
              className="w-full border rounded p-2 focus:ring-orange-500 focus:border-orange-500"
              value={questionData.enonce}
              onChange={(e) => handleChange('enonce', e.target.value)}
              rows={4}
            />
          </div>

          <div>
            <label className="block font-medium mb-1">Type de question</label>
            <div className="flex flex-wrap gap-2">
              {typeOptions.map((opt) => (
                <button
                  key={opt.value}
                  type="button"
                  onClick={() => {
                    handleChange('type', opt.value)
                    setQuestionData(prev => ({
                      ...prev,
                      options: [{ text: '', correct: false, note_partielle: 0 }],
                      vraiFaux: null,
                      note: 1,
                    }))
                  }}
                  className={`px-3 py-1 rounded-full border ${
                    questionData.type === opt.value
                      ? 'bg-orange-600 text-white'
                      : 'bg-gray-100 text-gray-700'
                  }`}
                >
                  {opt.label}
                </button>
              ))}
            </div>
          </div>

          {renderTypeFields()}

          <div className="flex justify-end space-x-2 mt-4">
            <button type="button" onClick={onClose} className="text-gray-600">Annuler</button>
            <button type="submit" className="bg-orange-600 text-white px-4 py-2 rounded">Ajouter</button>
          </div>
        </form>
      </div>
    </div>
  )
}
