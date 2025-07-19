import DashboardLayout from '@/Layouts/FormateurDashboardLayout'
import React from 'react'
import { BookOpenIcon, DocumentTextIcon, EyeIcon,ClipboardDocumentCheckIcon,ArrowRightIcon} from '@heroicons/react/24/outline'
import { Link, usePage } from '@inertiajs/react'
import StatCard from '@/Components/StatCard'

export default function DashboardFormateur() {
      const {
        auth,
        modulesCount,
        quizCount,
        recentTentativesCount,
        recentQuizzes,
        recentTentatives,
      } = usePage().props

      return (
        <DashboardLayout>
          <div className="p-6 space-y-8">
            <h2 className="text-2xl font-semibold">Bienvenue, {auth.user.name}</h2>

            {/* Stat Cards */}
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <StatCard Icon={BookOpenIcon} label="Modules assurés" value={modulesCount} color="text-pink-500" />
              <StatCard Icon={DocumentTextIcon} label="Quiz créés" value={quizCount} color="text-yellow-500" />
              <StatCard Icon={ClipboardDocumentCheckIcon} label="Tentatives récentes" value={recentTentativesCount} color="text-green-500" />
            </div>

            {/* Tentatives récentes */}
        <div>
          <h3 className="text-xl font-bold mt-6 mb-2">Tentatives récentes </h3>
          <div className="bg-white rounded shadow overflow-hidden">
            <table className="w-full text-sm">
              <thead className="bg-gray-100 text-left">
                <tr>
                  <th className="p-3">Étudiant</th>
                  <th className="p-3">Quiz</th>
                  <th className="p-3">Score</th>
                  <th className="p-3">Date</th>
                </tr>
              </thead>
              <tbody>
                {recentTentatives.length === 0 ? (
                  <tr>
                    <td colSpan="4" className="p-4 text-center text-gray-500">
                      Aucune tentative récente.
                    </td>
                  </tr>
                ) : (
                  recentTentatives.map((t) => (
                    <tr key={t.id} className="border-t">
                      <td className="p-3">{t.etudiant?.nom}</td>
                      <td className="p-3">{t.quiz?.titre}</td>
                      <td className="p-3">{t.score ?? 'N/A'}%</td>
                      <td className="p-3">
                        {new Date(t.created_at).toLocaleDateString()}
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </div>

        {/* Derniers quiz créés */}
        <div>
          <h3 className="text-xl font-bold mt-6 mb-2">Derniers quiz créés</h3>
          <div className="bg-white rounded shadow overflow-hidden">
            <table className="w-full text-sm">
              <thead className="bg-gray-100 text-left">
                <tr>
                  <th className="p-3">Titre</th>
                  <th className="p-3">Module</th>
                  <th className="p-3">Date</th>
                  <th className="p-3">Actions</th>
                </tr>
              </thead>
              <tbody>
                {recentQuizzes.length === 0 ? (
                  <tr>
                    <td colSpan="4" className="p-4 text-center text-gray-500">
                      Aucun quiz récemment créé.
                    </td>
                  </tr>
                ) : (
                  recentQuizzes.map((quiz) => (
                    <tr key={quiz.id} className="border-t">
                      <td className="p-3">{quiz.titre}</td>
                      <td className="p-3">{quiz.module?.nom}</td>
                      <td className="p-3">
                        {new Date(quiz.created_at).toLocaleDateString()}
                      </td>
                      <td className="p-3 text-right">
                        <Link
                          href={`/formateur/modules/${quiz.module_id}/quizzes/${quiz.id}`}
                          className="text-orange-600 hover:underline flex items-center space-x-1 justify-end"
                          title="Voir ce quiz"
                        >
                          <EyeIcon className="h-4 w-4" />
                          <span>Voir</span>
                        </Link>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
          <div className="mt-2 text-right">
            <Link
              href="/formateur/modules"
              className="text-sm text-gray-600 hover:underline flex items-center justify-end space-x-1"
            >
              <span>Voir tous les quiz</span>
              <ArrowRightIcon className="w-4 h-4" />
            </Link>
          </div>
        </div>
      </div>
    </DashboardLayout>
  )
}