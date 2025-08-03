import React from 'react'
import DashboardLayout from '@/Layouts/FormateurDashboardLayout'
import { Link, usePage } from '@inertiajs/react'

export default function TentativesList() {

  const { tentatives, quiz, noteTotale } = usePage().props

  return (
    <DashboardLayout>
      <h1 className="text-2xl font-bold mb-6">Tentatives - {quiz.title}</h1>

      <div className="overflow-x-auto bg-white rounded-lg shadow">
        <table className="min-w-full text-sm text-left">
          <thead className="bg-gray-100">
            <tr>
              <th className="p-3">Étudiant</th>
              <th className="p-3">Note obtenue</th>
              <th className="p-3">Soumis le</th>
              <th className="p-3">Actions</th>
            </tr>
          </thead>

          <tbody>
            {tentatives.length === 0 ? (
              <tr>
                <td colSpan="4" className="text-center p-4 text-gray-500">
                  Aucune tentative trouvée pour ce quiz.
                </td>
              </tr>
            ) : (
              tentatives.map((tentative) => (
                <tr key={tentative.id} className="border-t hover:bg-gray-50">
                  <td className="p-3">{tentative.etudiant.nom_complet}</td>
                  <td className="p-3">{tentative.score ?? '-'} / {noteTotale} </td>
                  <td className="p-3">{new Date(tentative.created_at).toLocaleString()}</td>
                  <td className="p-3">
                    <Link
                      href={route('quizzes.tentatives.show', [tentative.quiz_id, tentative.id])}
                      className="text-orange-600 hover:underline"
                    >
                      Voir la correction
                    </Link>
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
