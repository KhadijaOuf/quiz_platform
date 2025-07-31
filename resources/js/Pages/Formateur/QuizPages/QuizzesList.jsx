import React, { useState, useMemo } from 'react'
import DashboardLayout from '@/Layouts/FormateurDashboardLayout'
import { Link, router } from '@inertiajs/react'

export default function QuizzesList({ quizzes }) {
  const [search, setSearch] = useState('')

  const filteredQuizzes = useMemo(() => {
    return quizzes.filter((quiz) =>
      quiz.title.toLowerCase().includes(search.toLowerCase())
    )
  }, [search, quizzes])

  const handleDelete = (id) => {
    if (confirm('Voulez-vous vraiment supprimer ce quiz ?')) {
      router.delete(route('quizzes.destroy', id))
    }
  }

  const archiverQuiz = (id) => {
    if (confirm('Voulez-vous archiver ce quiz ?')) {
      axios.put(`/formateur/quizzes/${id}/archive`)
        .then(() => {
          router.visit('/formateur/quizzes/archives')  // ← redirection manuelle après succès
        })
        .catch(error => {
          alert(error.response?.data?.message || "Erreur lors de l'activation")
        })
    }
  }

  return (
    <DashboardLayout>
      <h1 className="text-2xl font-bold mb-6">Mes Quiz</h1>

      <div className="mb-4 flex justify-between items-center bg-white px-4 py-2 rounded-lg shadow">
        <input
          type="text"
          placeholder="Rechercher un quiz..."
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="border rounded-lg p-1 w-64 px-2 focus:ring-orange-500 focus:border-orange-500"
        />

        <Link
          href={route('quizzes.create')}
          className="bg-orange-600 text-white px-4 py-2 rounded-lg p-1 hover:bg-orange-700"
        >
          Nouveau quiz
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
              <th className="p-3">Statut</th>
              <th className="p-3"></th>
            </tr>
          </thead>

          <tbody>
            {filteredQuizzes.length === 0 ? (
              <tr>
                <td colSpan="7" className="text-center p-4 text-gray-500">
                  Aucun quiz trouvé.
                </td>
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
                <td className="p-3">{quiz.est_actif ? 'Actif' : 'Innactif'}</td>
                <td className="px-6 text-right space-x-4">
                  {quiz.est_actif ? (
                    <>
                      <Link
                        href={route('quizzes.show', quiz.id)}
                        className="text-sm text-gray-700 hover:underline hover:text-orange-600"
                      >
                        Voir
                      </Link>
                      <button
                        onClick={() => {archiverQuiz(quiz.id)}}
                        className="text-sm text-yellow-600 hover:underline"
                      >
                        Archiver
                      </button>
                    </>
                  ) : (
                    <>
                      <Link
                        href={route('quizzes.questions.index', quiz.id)}
                        className="text-sm text-blue-600 hover:underline"
                      >
                        Modifier
                      </Link>
                      <button
                        onClick={() => {handleDelete(quiz.id)}}
                        className="text-sm text-red-600 hover:underline"
                      >
                        Supprimer
                      </button>
                    </>
                  )}
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
