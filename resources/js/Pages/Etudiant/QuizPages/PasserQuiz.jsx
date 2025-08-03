import React, { useState } from 'react';
import { usePage, router } from '@inertiajs/react';
import Timer from '@/Components/Timer';

export default function PasserQuiz() {
  const { quiz } = usePage().props;
  const [reponses, setReponses] = useState({});
  const [envoye, setEnvoye] = useState(false);

  const handleChange = (questionId, valeur) => {
    setReponses(prev => ({
      ...prev,
      [questionId]: valeur,
    }));
  };

  const handleSubmit = () => {
    if (confirm('Êtes-vous sûr de vouloir soumettre vos réponses ?')) {
      router.post(`/etudiant/quizzes/${quiz.id}/soumettre`, { reponses });
      setEnvoye(true);
    }
  };


  /* Anti-triche */

  useEffect(() => {
    // Désactiver clic droit
    const handleContextMenu = (e) => e.preventDefault();
    document.addEventListener('contextmenu', handleContextMenu);

    // Désactiver copier/coller
    const disableCopyPaste = (e) => e.preventDefault();
    document.addEventListener('copy', disableCopyPaste);
    document.addEventListener('cut', disableCopyPaste);
    document.addEventListener('paste', disableCopyPaste);

    // Désactiver raccourcis clavier (Ctrl+C, Ctrl+U, F12...)
    const blockKeys = (e) => {
      if (
        (e.ctrlKey && ['c', 'u', 's', 'v', 'x'].includes(e.key.toLowerCase())) || // Ctrl + ...
        e.key === 'F12' ||
        (e.ctrlKey && e.shiftKey && ['i', 'j'].includes(e.key.toLowerCase()))
      ) {
        e.preventDefault();
      }
    };
    document.addEventListener('keydown', blockKeys);

    return () => {
      document.removeEventListener('contextmenu', handleContextMenu);
      document.removeEventListener('copy', disableCopyPaste);
      document.removeEventListener('cut', disableCopyPaste);
      document.removeEventListener('paste', disableCopyPaste);
      document.removeEventListener('keydown', blockKeys);
    };
  }, []);


  return (
    <div className="min-h-screen bg-gradient-to-br from-pink-100 via-yellow-100 to-orange-100 py-10">
      <div className="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl p-8 space-y-8">
        {quiz.duration > 0 && !envoye && (
          <Timer duration={quiz.duration} onExpire={() => {}} />
        )}
        <h1 className="text-3xl font-bold text-orange-700 text-center">{quiz.titre}</h1>

        {quiz.questions.map((question, index) => (
          <div key={question.id} className=" border border-orange-200 p-5 rounded-lg shadow-sm">
            <p className="font-semibold text-orange-800 mb-3">
              {index + 1}. {question.enonce}
              <span className="ml-2 text-sm font-thin text-gray-500">
                ({question.note} pt{question.note > 1 ? 's' : ''})
              </span>
            </p>
            

            {question.type === 'single' || question.type === 'vrai_faux' ? (
              question.reponse_attendues.map((rep) => (
                <div key={rep.id} className="mb-2">
                  <label className="flex items-center space-x-2 text-gray-700">
                    <input
                      type="radio"
                      name={`question_${question.id}`}
                      value={rep.texte}
                      checked={reponses[question.id] === rep.texte}
                      onChange={() => handleChange(question.id, rep.texte)}
                    />
                    <span>{rep.texte}</span>
                  </label>
                </div>
              ))
            ) : question.type === 'multiple' ? (
              question.reponse_attendues.map((rep) => (
                <div key={rep.id} className="mb-2">
                  <label className="flex items-center space-x-2 text-gray-700">
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
                    <span>{rep.texte}</span>
                  </label>
                </div>
              ))
            ) : (
              <textarea
                className="w-full border border-orange-300 rounded-lg p-2 mt-2 focus:ring-2 focus:ring-orange-500"
                rows={3}
                value={reponses[question.id] || ''}
                onChange={(e) => handleChange(question.id, e.target.value)}
                placeholder="Votre réponse ici..."
              />
            )}
          </div>
        ))}

        <div className="text-center">
            <button
              onClick={handleSubmit}
              className="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition"
            >
              Soumettre
            </button>
        </div>
      </div>
    </div>
  );
}
