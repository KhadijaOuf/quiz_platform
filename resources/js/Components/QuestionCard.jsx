import React, { useState } from 'react';
import { usePage } from '@inertiajs/react';
import axios from 'axios';

export default function PasserQuiz() {
  const { quiz } = usePage().props;
  const [reponses, setReponses] = useState({});
  const [envoye, setEnvoye] = useState(false);
  const [score, setScore] = useState(null);
  const [correctionComplete, setCorrectionComplete] = useState(null);

  const handleChange = (questionId, valeur) => {
    setReponses(prev => ({
      ...prev,
      [questionId]: valeur,
    }));
  };

  const handleCheckboxChange = (questionId, valeur) => {
    const current = reponses[questionId] || [];
    if (current.includes(valeur)) {
      handleChange(questionId, current.filter(v => v !== valeur));
    } else {
      handleChange(questionId, [...current, valeur]);
    }
  };

  const handleSubmit = async () => {
    const donnees = Object.entries(reponses).map(([question_id, reponse]) => ({
      question_id,
      reponse,
    }));

    try {
      const res = await axios.post(`/quiz/${quiz.id}/soumettre`, {
        reponses: donnees,
      });

      setEnvoye(true);
      setScore(res.data.score_partiel);
      setCorrectionComplete(res.data.correction_complete);
    } catch (e) {
      console.error('Erreur lors de la soumission du quiz :', e);
    }
  };

  return (
    <div className="p-6 max-w-3xl mx-auto space-y-6">
      <h1 className="text-2xl font-bold">{quiz.titre}</h1>

      {quiz.questions.map((question, index) => (
        <div key={question.id} className="border p-4 rounded shadow-sm">
          <p className="font-semibold mb-2">{index + 1}. {question.enonce}</p>

          {/* QCM Simple ou Vrai/Faux */}
          {(question.type === 'qcm_simple' || question.type === 'vrai_faux') && (
            question.reponse_attendues.map(rep => (
              <div key={rep.id} className="flex items-center mb-1">
                <input
                  type="radio"
                  name={`question_${question.id}`}
                  value={rep.texte}
                  checked={reponses[question.id] === rep.texte}
                  onChange={() => handleChange(question.id, rep.texte)}
                  className="mr-2"
                />
                <label>{rep.texte}</label>
              </div>
            ))
          )}

          {/* QCM Multiple */}
          {question.type === 'qcm_multiple' && (
            question.reponse_attendues.map(rep => (
              <div key={rep.id} className="flex items-center mb-1">
                <input
                  type="checkbox"
                  value={rep.texte}
                  checked={reponses[question.id]?.includes(rep.texte)}
                  onChange={() => handleCheckboxChange(question.id, rep.texte)}
                  className="mr-2"
                />
                <label>{rep.texte}</label>
              </div>
            ))
          )}

          {/* Réponse courte */}
          {question.type === 'reponse_courte' && (
            <textarea
              className="w-full border rounded mt-2 p-2"
              rows={3}
              onChange={(e) => handleChange(question.id, e.target.value)}
              placeholder="Votre réponse ici..."
            />
          )}
        </div>
      ))}

      {!envoye ? (
        <button
          onClick={handleSubmit}
          className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
        >
          Soumettre
        </button>
      ) : (
        <div className="mt-4">
          {correctionComplete ? (
            <p className="text-green-600 font-bold">Votre score final : {score}</p>
          ) : (
            <>
              <p className="text-orange-600 font-bold">Score partiel : {score} points</p>
              <p>Votre score final sera disponible après la correction manuelle.</p>
            </>
          )}
        </div>
      )}
    </div>
  );
}
