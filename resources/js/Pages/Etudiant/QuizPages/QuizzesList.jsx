import React from 'react'
import { usePage } from '@inertiajs/react'
import DashboardLayout from '@/Layouts/EtudiantDashboardLayout'
import QuizCard from '@/Components/QuizCard' // adapte le chemin selon ton projet

export default function MesQuizzes() {
  const { quizzes } = usePage().props
 // Affiche dans la console ce que tu reçois côté frontend
  console.log('Quizzes reçus du backend : ', quizzes)
  return (
    <DashboardLayout>
      <div className="mt-6">
        <h1 className="text-2xl font-bold mb-4">Mes quizzes disponibles</h1>

        {quizzes.length === 0 ? (
          <p className="text-gray-600">Aucun quiz disponible pour le moment.</p>
        ) : (
          <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {quizzes.map(quiz => (
              <QuizCard key={quiz.id} quiz={quiz} />
            ))}
          </div>
        )}
      </div>
    </DashboardLayout>
  )
}
