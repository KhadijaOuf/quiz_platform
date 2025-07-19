import React, { useState, useMemo } from 'react'
import DashboardLayout from '@/Layouts/FormateurDashboardLayout'
import { Link } from '@inertiajs/react'

export default function ModulesPage({ modules, specialites }) {
  const [filtreSpecialite, setFiltreSpecialite] = useState('') // filtre par specialité
  const [rechercheNom, setRechercheNom] = useState('') // zone de recherche par nom de module
  

  // Filtrer les modules selon le nom module/spécialité
  const modulesFiltre = useMemo(() => {
  return modules.filter((mod) => {
    const matchNom = mod.nom.toLowerCase().includes(rechercheNom.toLowerCase())
    const matchSpecialite = !filtreSpecialite || mod.specialites.some((s) => s.id === parseInt(filtreSpecialite))
    return matchNom && matchSpecialite
  })
}, [filtreSpecialite, rechercheNom, modules])
  

  return (
    <DashboardLayout>
      <h1 className="text-2xl font-bold mb-6">Mes Modules</h1>

{/* Filtre spécialité */}
      <div className="mb-4 bg-white pl-3 p-2 rounded-lg shadow">
        <div className='flex justify-between items-center'>
          <div>
            <label htmlFor="filtreSpecialite" className="mr-2 font-medium"> Filtrer par spécialité :</label>
            <select
              id="filtreSpecialite"
              value={filtreSpecialite}
              onChange={(e) => setFiltreSpecialite(e.target.value)}
              className="border rounded-lg p-1 text-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-500"
            >
              <option value="" className=''>Toutes</option>
              {specialites.map((spec) => (
                <option key={spec.id} value={spec.id}>
                  {spec.nom}
                </option>
              ))}
            </select>
          </div>
          <input
            id="rechercheNom"
            type="text"
            value={rechercheNom}
            onChange={(e) => setRechercheNom(e.target.value)}
            placeholder="Recherche ..."
            className="border rounded-lg p-1 w-64"
          />
        </div>
      </div>

{/* Tableau des modules */}
      <div className="mt-5 overflow-x-auto rounded-lg shadow bg-white">
        <table className="min-w-full text-sm text-left">
          <thead className="bg-gray-100 text-gray-700 border p-2">
            <tr>
              <th className="p-3">Nom du module</th>
              <th className="p-3">Spécialités</th>
              <th className="p-3 text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            {modulesFiltre.length === 0 && (
              <tr>
                <td colSpan={3} className="p-3 text-center text-gray-500">
                  Aucun module trouvé.
                </td>
              </tr>
            )}

            {modulesFiltre.map((mod) => (
              <tr key={mod.id} className="border-b hover:bg-gray-50">
                <td className="p-3 font-medium">{mod.nom}</td>
                <td className="p-3">
                  {mod.specialites.length > 0
                    ? mod.specialites.map((s) => s.nom).join(', ')
                    : '-'}
                </td>
                <td className="p-3 text-end pr-20 space-x-10">
                  <Link
                    href={`/formateur/modules/${mod.id}/quizzes`}
                    title="Voir les quiz de ce module"
                    className="text-sm text-gray-700 hover:underline hover:text-orange-600"
                  >
                    Quiz
                  </Link>
                  <Link
                    href={`/formateur/quizzes/create?module_id=${mod.id}`}
                    title="Créer un nouveau quiz pour ce module"
                    className="text-sm text-gray-700 hover:underline hover:text-orange-600"
                  >
                    Créer
                  </Link>
                  <Link
                    href={`/formateur/modules/${mod.id}/tentatives`}
                    title="Voir les tentatives des étudiants"
                    className="text-sm text-gray-700 hover:underline hover:text-orange-600"
                  >
                    Tentatives
                  </Link>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </DashboardLayout>
  )
}