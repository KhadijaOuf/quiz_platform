import React from 'react';
import DashboardLayout from '@/Layouts/FormateurDashboardLayout';
import { usePage } from '@inertiajs/react';
import StatCard from '@/Components/StatCard';
import { ClipboardDocumentListIcon, PuzzlePieceIcon, UserGroupIcon } from '@heroicons/react/24/outline';

export default function StatistiquesGlobales() {
  const {
    modulesCount,
    quizCount,
    tentativeCount,
    averageScore,
    modulesStats,  // [{ id, nom, quiz_count, tentative_count, avg_score }]
  } = usePage().props;

  return (
    <DashboardLayout>
      <div className="p-6 space-y-8">
        <h2 className="text-2xl font-semibold mb-6">Statistiques globales</h2>

        <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
          <StatCard Icon={PuzzlePieceIcon} label="Modules assurés" value={modulesCount} color="text-indigo-500" />
          <StatCard Icon={ClipboardDocumentListIcon} label="Quiz créés" value={quizCount} color="text-yellow-500" />
          <StatCard Icon={UserGroupIcon} label="Tentatives totales" value={tentativeCount} color="text-green-500" />
        </div>

        <h3 className="text-xl font-semibold mt-8 mb-4">Statistiques par module</h3>
        <table className="w-full text-left text-sm bg-white rounded shadow overflow-hidden">
          <thead className="bg-gray-100">
            <tr>
              <th className="p-3">Module</th>
              <th className="p-3">Quiz créés</th>
              <th className="p-3">Tentatives</th>
              <th className="p-3">Score moyen</th>
            </tr>
          </thead>
          <tbody>
            {modulesStats.length === 0 ? (
              <tr>
                <td colSpan="4" className="p-4 text-center text-gray-500">Aucune donnée disponible.</td>
              </tr>
            ) : (
              modulesStats.map(module => (
                <tr key={module.id} className="border-t">
                  <td className="p-3">{module.nom}</td>
                  <td className="p-3">{module.quiz_count}</td>
                  <td className="p-3">{module.tentative_count}</td>
                  <td className="p-3">{module.avg_score.toFixed(2)}%</td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>
    </DashboardLayout>
  );
}
