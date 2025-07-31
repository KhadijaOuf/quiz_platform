import React, { useState, useMemo } from 'react'
import DashboardLayout from '@/Layouts/FormateurDashboardLayout'
import { Link, router } from '@inertiajs/react'

export default function QuizzesArchives({ quizzes }) {
  const [search, setSearch] = useState('')

  const filteredQuizzes = useMemo(() => {
    return quizzes.filter((quiz) =>
      quiz.title.toLowerCase().includes(search.toLowerCase())
    )
  }, [search, quizzes])


  return (
    <DashboardLayout>
      <h1 className="text-2xl font-bold mb-6">Quiz Archivés</h1>

      <div className="mb-4 flex justify-between items-center bg-white px-4 py-2 rounded-lg shadow">
        <input
          type="text"
          placeholder="Rechercher un quiz archivé..."
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="border rounded-lg p-1 w-64 px-2 focus:ring-orange-500 focus:border-orange-500"
        />

        <Link
          href={route('quizzes.index')}
          className="bg-orange-600 text-white px-4 py-2 rounded-lg p-1 hover:bg-orange-700"
        >
          Retour aux Quiz actifs
        </Link>
      </div>

      <div className="overflow-x-auto bg-white rounded-lg shadow">
        <table className="min-w-full text-sm text-left">
          <thead className="bg-gray-100">
            <tr>
              <th className="p-3">Titre</th>
              <th className="p-3">Module</th>
              <th className="p-3">Date de création</th>
              <th className="p-3">Durée</th>
              <th className="p-3">Période</th>
              <th className="p-3"></th>
            </tr>
          </thead>
          <tbody>
            {filteredQuizzes.length === 0 ? (
              <tr>
                <td colSpan="6" className="text-center p-4 text-gray-500">Aucun quiz archivé.</td>
              </tr>
            ) : (
              filteredQuizzes.map((quiz) => (
                <tr key={quiz.id} className="border-t hover:bg-gray-50">
                  <td className="p-3">{quiz.title}</td>
                  <td className="p-3">{quiz.module?.nom || '-'}</td>
                  <td className="p-3">{new Date(quiz.created_at).toLocaleDateString()}</td>
                  <td className="p-3">{quiz.duration ? `${quiz.duration} min` : '-'}</td>
                  <td className="p-3">
                    {quiz.disponible_du ? new Date(quiz.disponible_du).toLocaleDateString() : '-'} → {quiz.disponible_jusquau ? new Date(quiz.disponible_jusquau).toLocaleDateString() : '-'}
                  </td>
                  <td className="px-6 text-right space-x-4">
                    <button
                      className="text-sm text-gray-700 hover:text-orange-600 hover:underline"
                    >
                      Tentatives
                    </button>
                    <button
                      className="text-sm text-gray-700 hover:text-orange-600 hover:underline"
                    >
                      Statistiques
                    </button>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>
    </DashboardLayout>
  )
}
