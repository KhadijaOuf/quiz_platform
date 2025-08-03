import React from 'react'
import { Link } from '@inertiajs/react'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import 'dayjs/locale/fr'   // importer la locale française
dayjs.extend(relativeTime)
dayjs.locale('fr')  // activer la locale française


export default function QuizCard({ quiz }) {
  // Calcul du temps restant avant la fin de disponibilité du quiz
  const now = dayjs()
  const disponibleJusquau = dayjs(quiz.disponible_jusquau)

  const timeLeft = disponibleJusquau.isAfter(now)
    ? disponibleJusquau.fromNow(true) 
    : null

  return (
    <div className="border rounded-lg p-4 shadow hover:shadow-lg transition bg-white flex flex-col justify-between">
      <div>
        <h1 className="text-xl font-semibold mb-2">{quiz.title}</h1>
        <p className="text-sm text-gray-600 mb-2">{quiz.description}</p>

        <p className="text-sm mb-1">
          <span className="font-semibold">Durée :</span> {quiz.duration ? `${quiz.duration} min` : 'illimitée'}
        </p>

        <p className="text-sm mb-3">
          <span className="font-semibold">Note de réussite :</span> {quiz.note_reussite}
        </p>

        {timeLeft && (
          <p className="text-sm text-orange-600 font-semibold">
            Disponible encore : {timeLeft}
          </p>
        )}
      </div>

      {quiz.dejaPasse ? (
        <span className="w-fit mt-4 px-4 py-2 bg-green-100 text-green-700 rounded cursor-default">
          <span className="pb-5">✔️</span> Vous avez passé ce quiz
        </span>
      ) : quiz.status === 'actif' ? (
        <Link
          href={`/etudiant/quizzes/${quiz.id}/passer`}
          className="w-fit mt-4 px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700 transition"
        >
          Passer le quiz
        </Link>
      ) : (
        <span className="w-fit mt-4 px-4 py-2 bg-gray-300 text-gray-700 rounded cursor-not-allowed">
          Quiz non disponible
        </span>
      )}
    </div>
  )
}
