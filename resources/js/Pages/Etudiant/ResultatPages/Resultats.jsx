import React, { useState, useMemo } from 'react';
import DashboardLayout from '@/Layouts/EtudiantDashboardLayout';
import { Link } from '@inertiajs/react';

export default function Resultats({ tentatives }) {
  const [search, setSearch] = useState('');

  const filteredTentatives = useMemo(() => {
    return tentatives.filter((tentative) =>
      tentative.quiz.title.toLowerCase().includes(search.toLowerCase())
    );
  }, [search, tentatives]);

  return (
    <DashboardLayout>
      <h1 className="text-2xl font-bold mb-6 mt-6">Mes résultats</h1>

      <div className="mb-4 flex justify-between items-center bg-white px-4 py-2 rounded-lg shadow">
        <input
          type="text"
          placeholder="Rechercher un quiz..."
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="border rounded-lg p-1 w-64 px-2 focus:ring-orange-500 focus:border-orange-500"
        />
      </div>

      <div className="overflow-x-auto bg-white rounded-lg shadow">
        <table className="min-w-full text-sm text-left">
          <thead className="bg-gray-100">
            <tr>
              <th className="p-3">Quiz</th>
              <th className="p-3">Module</th>
              <th className="p-3">Date de soumission</th>
              <th className="p-3">Score</th>
              <th className="p-3">Décision</th>
              <th className="p-3">Correction</th>
            </tr>
          </thead>

          <tbody>
            {filteredTentatives.length === 0 ? (
              <tr>
                <td colSpan="6" className="text-center p-4 text-gray-500">
                  Aucune resultat trouvée.
                </td>
              </tr>
            ) : (
              filteredTentatives.map((tentative) => (
                <tr key={tentative.id} className="border-t hover:bg-gray-50">
                  <td className="p-3 font-semibold">{tentative.quiz.title}</td>
                  <td className="p-3">{tentative.quiz.module?.nom || '-'}</td>
                  <td className="p-3">{new Date(tentative.termine_a).toLocaleString()}</td>
                  <td className="p-3 font-mono"> {tentative.quiz.contientQuestionTexte ? 'En attente' : `${tentative.score} / ${tentative.quiz.noteTotale}`}</td>
                  <td className="p-3 font-semibold" style={{color: tentative.est_passed ? 'green' : 'red'}}>
                    {tentative.est_passed ? 'Réussi' : 'Échoué'}
                  </td>
                  <td className="p-3">
                    <Link
                      href={`/etudiant/quizzes/${tentative.quiz.id}/tentatives/${tentative.id}/correction`}
                      className="text-blue-600 hover:underline font-semibold"
                    >
                      Voir
                    </Link>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>
    </DashboardLayout>
  );
}
