// resources/js/Pages/Etudiant/Dashboard.jsx
import React from 'react'
import DashboardLayout from '@/Layouts/EtudiantDashboardLayout'
import { usePage } from '@inertiajs/react'

import StatCard from '@/Components/StatCard'

// Import des icônes Heroicons
import { ClipboardDocumentIcon, PencilSquareIcon, CheckCircleIcon } from '@heroicons/react/24/solid'

export default function Dashboard() {
  const { auth, totalQuizzes, totalAttempts, passedCount, latestQuizzes } = usePage().props

  return (
    <DashboardLayout>
      <div className="space-y-6">
        <h2 className="text-xl font-semibold">Bienvenue {auth?.user?.etudiant?.nom_complet} 👋</h2>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          <StatCard
            Icon={ClipboardDocumentIcon}
            label="Total de quiz disponibles"
            value={totalQuizzes}
            color="text-blue-500"
          />
          <StatCard
            Icon={PencilSquareIcon}
            label="Quiz déjà tentés"
            value={totalAttempts}
            color="text-yellow-500"
          />
          <StatCard
            Icon={CheckCircleIcon}
            label="Quiz réussis"
            value={passedCount}
            color="text-green-500"
          />
        </div>

        {latestQuizzes?.length > 0 && (
          <div className="mt-8">
            <h3 className="text-lg font-semibold mb-4">Derniers quiz disponibles</h3>
            <ul className="space-y-4">
              {latestQuizzes.map((quiz) => (
                <li key={quiz.id} className="bg-white p-4 rounded shadow">
                  <h4 className="text-md font-bold">{quiz.title}</h4>
                  <p className="text-sm text-gray-600">{quiz.description}</p>
                  <p className="text-sm text-gray-500 mt-1">Durée : {quiz.duration ? `${quiz.duration} min` : 'illimitée'}</p>
                </li>
              ))}
            </ul>
          </div>
        )}
      </div>
    </DashboardLayout>
  )
}
