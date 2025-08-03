import React from 'react';
import QuestionCorrectionCard from '@/Components/QuestionCorrectionCard';
import { usePage, Link } from '@inertiajs/react';

export default function CorrectionQuiz() {

  const { quiz, reponsesDonnees, score, estCorrigee, noteTotale, tentative } = usePage().props;

  return (
    <div className="min-h-screen bg-gradient-to-br from-pink-100 via-yellow-100 to-orange-100 py-10">

      <div className="max-w-4xl mx-auto">
        <div className="mb-3">
          <Link
              href={route('quizzes.tentatives.index', quiz.id)}
              className="inline-block px-6 py-3 text-orange-700 text-sm font-semibold hover:underline transition"
            >
              ← Retour au tentatives
            </Link>
        </div>
        <div className='bg-white rounded-2xl shadow-xl p-8 space-y-8 mb-10'>
          <h1 className="text-3xl font-bold text-gray-700 text-center">{quiz.title}</h1>

          <div className="text-center text-lg font-semibold text-orange-700 mb-6">
            {estCorrigee
              ? `Score final : ${score} / ${noteTotale}`
              : 'En attente de correction manuelle...'}
          </div>

          {reponsesDonnees.map(({ question, texte, est_correcte, note_obtenue }, index) => (
            <QuestionCorrectionCard
              key={question.id}
              question={question}
              index={index}
              reponseEtudiant={texte}
              correctionMode={true}
              estCorrecte={est_correcte}
              noteObtenue={note_obtenue}   
            />
          ))}
        </div>
      </div>
    </div>
  );
}

