import React, { useState } from 'react';
import { usePage, router } from '@inertiajs/react';

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
      console.error(e);
    }
  };

  return (
    <div className="p-6 max-w-3xl mx-auto space-y-6">
      <h1 className="text-2xl font-bold">{quiz.titre}</h1>

      {quiz.questions.map((question, index) => (
        <div key={question.id} className="border p-4 rounded shadow-sm">
          <p className="font-semibold mb-2">{index + 1}. {question.enonce}</p>

          {question.type === 'single' || question.type === 'vrai_faux' ? (
            question.reponse_attendues.map((rep) => (
              <div key={rep.id}>
                <input
                  type="radio"
                  name={`question_${question.id}`}
                  value={rep.texte}
                  checked={reponses[question.id] === rep.texte}
                  onChange={() => handleChange(question.id, rep.texte)}
                />
                <label className="ml-2">{rep.texte}</label>
              </div>
            ))
          ) : question.type === 'multiple' ? (
            question.reponse_attendues.map((rep) => (
              <div key={rep.id}>
                <input
                  type="checkbox"
                  value={rep.texte}
                  checked={reponses[question.id]?.includes(rep.texte)}
                  onChange={(e) => {
                    const current = reponses[question.id] || [];
                    if (e.target.checked) {
                      handleChange(question.id, [...current, rep.texte]);
                    } else {
                      handleChange(question.id, current.filter(v => v !== rep.texte));
                    }
                  }}
                />
                <label className="ml-2">{rep.texte}</label>
              </div>
            ))
          ) : (
            <textarea
              className="w-full border rounded mt-2"
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
          className="bg-blue-600 text-white px-4 py-2 rounded"
        >
          Soumettre
        </button>
      ) : (
        <div className="mt-4">
          {correctionComplete ? (
            <p className="text-green-600 font-bold">Votre score final : {score}</p>
          ) : (
            <>
              <p className="text-orange-600 font-bold">
                Score partiel : {score} points
              </p>
              <p>Votre score final sera disponible après la correction manuelle.</p>
            </>
          )}
        </div>
      )}
    </div>
  );
}
