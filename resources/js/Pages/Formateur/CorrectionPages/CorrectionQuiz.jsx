import React, { useState } from 'react';
import QuestionCorrectionCard from '@/Components/QuestionCorrectionCard';
import { useForm, Link, usePage } from '@inertiajs/react';

export default function CorrectionQuiz() {
  const { quiz, tentative, reponsesDonnees, estCorrigee, noteTotale } = usePage().props;

  // Formulaire pour notes de correction (uniquement pour questions type 'text')
  // Initialisation avec notes existantes ou null
  const initialNotes = {};
  reponsesDonnees.forEach(rep => {
    if (rep.question.type === 'text') {
      initialNotes[rep.question.id] = rep.note_obtenue ?? '';
    }
  });

  const { data, setData, put, processing, errors } = useForm({
    notes: initialNotes,
  });

  const handleNoteChange = (questionId, value, max) => {
    let val = value === '' ? '' : Number(value);
        if (val !== '') {
            if (val < 0) val = 0;
            if (val > max) val = max;
        }
        setData('notes', { ...data.notes, [questionId]: val });
    };


  const submitCorrection = (e) => {
    e.preventDefault();
    put(route('correction.update', tentative.id), {
      onSuccess: () => {
        alert('Correction enregistrée avec succès !');
      },
    });
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-pink-100 via-yellow-100 to-orange-100 py-10">
      <div className="max-w-4xl mx-auto py-10">

        <h1 className="text-3xl font-bold text-gray-700 text-center mb-6">{quiz.title}</h1>

        <div className="text-center text-lg font-semibold text-orange-700 mb-6">
          {estCorrigee
            ? `Score final : ${tentative.score} / ${noteTotale}`
            : 'En attente de correction manuelle...'}
        </div>
        
        <form onSubmit={submitCorrection} className="bg-white rounded-2xl shadow-xl p-8 space-y-8">
          {reponsesDonnees.map(({ question, texte, est_correcte, note_obtenue }, index) => (
            <div key={question.id}>
              <QuestionCorrectionCard
                question={question}
                index={index}
                reponseEtudiant={texte}
                correctionMode={true}
                estCorrecte={est_correcte}
                noteObtenue={note_obtenue}
              />

              {question.type === 'text' && !estCorrigee && (
                <div className="mt-2">
                  <label className="block font-semibold mb-1" htmlFor={`note-${question.id}`}>
                    Note pour la réponse rédactionnelle : <span className="text-sm text-gray-500">(max {question.note} pts)</span>
                  </label>
                  <input
                    id={`note-${question.id}`}
                    type="number"
                    min={0}
                    max={question.note}
                    className="border p-2 rounded w-24"
                    value={data.notes[question.id] ?? ''}
                    onChange={(e) => handleNoteChange(question.id, e.target.value, question.note)}
                    disabled={processing}
                    required
                  />
                  {errors[`notes.${question.id}`] && (
                    <p className="text-red-600 text-sm mt-1">{errors[`notes.${question.id}`]}</p>
                  )}
                </div>
              )}
            </div>
          ))}

          {!estCorrigee && (
            <div className="mt-6 text-center">
              <button
                type="submit"
                disabled={processing}
                className="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded font-semibold transition"
              >
                {processing ? 'Enregistrement...' : 'Soumettre la correction'}
              </button>
            </div>
          )}
        </form>
        <div className="mb-3">
          <Link
            href="/formateur/correction"
            className="inline-block mt-4 px-6 py-3 text-orange-700 text-sm font-semibold hover:underline transition"
          >
            ← Retour à la liste des corrections
          </Link>
        </div>
      </div>
    </div>
  );
}
