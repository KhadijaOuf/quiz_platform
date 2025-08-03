import React from 'react'
import DashboardLayout from '@/Layouts/FormateurDashboardLayout'
import { Link } from '@inertiajs/react'

export default function CorrectionList({ tentatives }) {
  return (
    <DashboardLayout>
      <h1 className="text-2xl font-bold mb-6">Corrections à faire</h1>

      {tentatives.length === 0 ? (
        <p>Aucune tentative nécessitant une correction rédactionnelle.</p>
      ) : (
        <div className="overflow-x-auto bg-white rounded-lg shadow">
          <table className="min-w-full text-left text-sm">
            <thead className="bg-white">
              <tr>
                <th className="p-3">Étudiant</th>
                <th className="p-3">Quiz</th>
                <th className="p-3">Date de tentative</th>
                <th className="p-3">État</th>
                <th className="p-3">Action</th>
              </tr>
            </thead>
            <tbody>
              {tentatives.map(tentative => {
                const estCorrigee = tentative.est_corrigee

                return (
                  <tr
                    key={tentative.id}
                    className={`border-t transition ${
                      estCorrigee
                        ? 'bg-green-50 hover:bg-green-100'
                        : 'hover:bg-orange-50'
                    }`}
                  >
                    <td className="p-3">{tentative.etudiant?.nom_complet || 'Inconnu'}</td>
                    <td className="p-3">{tentative.quiz?.title || '-'}</td>
                    <td className="p-3">
                      {new Date(tentative.created_at).toLocaleString()}
                    </td>
                    <td className="p-3">
                      {estCorrigee ? (
                        <span className="inline-flex items-center text-green-700 font-medium">
                          🟢 Corrigé
                        </span>
                      ) : (
                        <span className="inline-flex items-center text-orange-600 font-medium">
                          🟠 En attente
                        </span>
                      )}
                    </td>
                    <td className="p-3">
                      <Link
                        href={`/formateur/correction/${tentative.id}`}
                        className={`font-semibold hover:underline ${
                          estCorrigee ? 'text-green-700' : 'text-orange-600'
                        }`}
                      >
                        {estCorrigee ? 'Voir correction' : 'Corriger'}
                      </Link>
                    </td>
                  </tr>
                )
              })}
            </tbody>
          </table>
        </div>
      )}
    </DashboardLayout>
  )
}
