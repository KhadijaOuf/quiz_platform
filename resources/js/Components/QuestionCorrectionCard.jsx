import React from 'react';

export default function QuestionCorrectionCard({
  question,
  index,
  correctionMode = false,
  reponseEtudiant = null,  // réponse donnée par l’étudiant (string ou array)
  estCorrecte = false,
  noteObtenue = null,
}) {
  // Couleur de fond selon correction
  const bgColor = correctionMode
    ? question.type === 'text' && !estCorrecte
      ? 'bg-gray-100'
      : estCorrecte
        ? 'bg-green-100'
        : 'bg-red-100'
    : '';

  // Fonction pour vérifier si une réponse est cochée par l’étudiant
  const isChecked = (texteRep) => {
    if (!reponseEtudiant) return false;
    if (Array.isArray(reponseEtudiant)) {
      return reponseEtudiant.includes(texteRep);
    }
    return reponseEtudiant === texteRep;
  };

  const renderReponses = () => {
    if (question.type === 'vrai_faux') {
      return (
        <div className="flex gap-4 mt-2">
          {['Vrai', 'Faux'].map((valeur) => (
            <label key={valeur} className="flex items-center cursor-default select-none">
              <input
                type="radio"
                disabled
                checked={isChecked(valeur)}
                className="mr-2"
                name={`question_${question.id}`}
                value={valeur}
              />
              <span>{valeur}</span>
            </label>
          ))}
        </div>
      );
    }

    if (question.type === 'multiple' || question.type === 'single') {
      return (
        <div className="mt-2">
          {question.reponse_attendues?.map((rep, idx) => (
            <label key={idx} className="flex items-center mb-1 cursor-default select-none">
              <input
                type={question.type === 'multiple' ? 'checkbox' : 'radio'}
                disabled
                checked={isChecked(rep.texte)}
                className="mr-2"
                name={`question_${question.id}`}
                value={rep.texte}
              />
              <span className="mr-2">{rep.texte}</span>
              {question.type === 'multiple' && (rep.note_partielle ?? 0) > 0 && (
                <span className="text-sm text-gray-500">({rep.note_partielle ?? 0} pts)</span>
              )}
            </label>
          ))}
        </div>
      );
    }

    if (question.type === 'text') {
      return (
        <textarea
          className="w-full border border-gray-300 rounded-lg p-2 mt-2 bg-gray-50 text-gray-800"
          rows={3}
          disabled
          value={reponseEtudiant || ''}
          placeholder="Aucune réponse donnée"
        />
      );
    }

    return null;
  };

  return (
    <div className={`border p-4 rounded-xl shadow mb-4 relative ${bgColor}`}>
      <div className="flex flex-col h-full">
        <div>
          <div className="flex justify-between items-center mb-2">
            <h3 className="text-lg font-semibold">Question {index + 1}</h3>
          </div>

          <p className="mt-1 text-gray-700">{question.enonce}</p>

          {/* Affiche les inputs cochés selon la réponse de l'étudiant */}
          {renderReponses()}
        </div>

        <p className="text-sm text-gray-500 mt-auto self-end mr-3">
          Note : {noteObtenue === null ? 'En attente' : noteObtenue}
        </p>
      </div>
    </div>
  );
}
