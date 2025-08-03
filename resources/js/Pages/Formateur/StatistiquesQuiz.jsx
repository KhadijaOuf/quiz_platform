import React from 'react';
import DashboardLayout from '@/Layouts/FormateurDashboardLayout';
import { Bar, Pie, Line } from 'react-chartjs-2';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  PointElement,
  LineElement,
} from 'chart.js';

ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  PointElement,
  LineElement
);

export default function StatistiquesQuiz({ quiz, stats, chartsData, totalNotes }) {
  if (!chartsData) {
    return (
      <DashboardLayout>
        <h1 className="text-2xl font-bold mb-6">Statistiques du Quiz : {quiz.title}</h1>
        <p>Chargement des données graphiques...</p>
      </DashboardLayout>
    );
  }

  const { barData, pieData, lineData } = chartsData;

  return (
    <DashboardLayout>
      <h1 className="text-2xl font-bold mb-6">Statistiques du Quiz : {quiz.title}</h1>

      {/* Cartes statistiques */}
      <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">
        <div className="bg-white p-6 rounded-lg shadow flex flex-col items-center">
          <span className="text-3xl font-bold">{stats.nb_tentatives}</span>
          <span className="text-gray-600 mt-1">Tentatives</span>
        </div>
        <div className="bg-white p-6 rounded-lg shadow flex flex-col items-center">
          <span className="text-3xl font-bold">{stats.moyenne} / {totalNotes}</span>
          <span className="text-gray-600 mt-1">Moyenne</span>
        </div>
        <div className="bg-white p-6 rounded-lg shadow flex flex-col items-center">
          <span className="text-3xl font-bold">{stats.score_max} / {totalNotes}</span>
          <span className="text-gray-600 mt-1">Score max</span>
        </div>
        <div className="bg-white p-6 rounded-lg shadow flex flex-col items-center">
          <span className="text-3xl font-bold">{stats.score_min} / {totalNotes}</span>
          <span className="text-gray-600 mt-1">Score min</span>
        </div>
        <div className="bg-white p-6 rounded-lg shadow flex flex-col items-center">
          <span className="text-3xl font-bold text-green-600">{stats.nb_reussis}</span>
          <span className="text-gray-600 mt-1">Réussites</span>
        </div>
        <div className="bg-white p-6 rounded-lg shadow flex flex-col items-center">
          <span className="text-3xl font-bold text-red-600">{stats.nb_echoues}</span>
          <span className="text-gray-600 mt-1">Échecs</span>
        </div>
      </div>

      {/* Graphe Notes étudiants - prend toute la largeur */}
      <div
        className="bg-white rounded-xl shadow p-11 mb-8 max-w-full mx-auto w-full"
        style={{ height: '450px' }} // hauteur fixe importante
      >
        <h2 className="font-semibold mb-4">Notes des étudiants</h2>
        <Bar
          data={barData}
          options={{
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: { beginAtZero: true },
              x: { ticks: { maxRotation: 90, minRotation: 45 } },
            },
          }}
        />
      </div>

      {/* Les autres graphes en grille 2 colonnes */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-20">
        <div
          className="bg-white rounded-xl p-11 shadow"
          style={{ height: '400px' }} // hauteur fixe
        >
          <h2 className="font-semibold mb-4">Répartition réussites / échecs</h2>
          <Pie
            data={pieData}
            options={{
              responsive: true,
              maintainAspectRatio: false,
              plugins: { legend: { position: 'bottom' } },
            }}
            className="w-full h-full"
          />
        </div>
        <div
          className="bg-white rounded-xl p-11 shadow"
          style={{ height: '400px' }} // hauteur fixe
        >
          <h2 className="font-semibold mb-4">Notes triées</h2>
          <Line
            data={lineData}
            options={{
              responsive: true,
              maintainAspectRatio: false,
              scales: { y: { beginAtZero: true } },
            }}
          />
        </div>
      </div>
    </DashboardLayout>
  );
}
