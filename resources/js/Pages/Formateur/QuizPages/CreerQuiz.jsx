import React from 'react'
import { useForm, usePage, Link } from '@inertiajs/react'
import DashboardLayout from '@/Layouts/FormateurDashboardLayout'

export default function CreerQuiz() {
  const { modules } = usePage().props

  const { data, setData, post, processing, errors } = useForm({
    title: '',
    description: '',
    duration: '',
    note_reussite: '',
    module_id: '',
    disponible_du: '',
    disponible_jusquau: '',
    est_actif: false,
  })

  const submit = (e) => {
    e.preventDefault()
    post(route('quizzes.store'))
  }

  return (
    <DashboardLayout>
      <div className="max-w-7xl mx-auto">
        <h2 className="text-2xl font-bold mb-6">Créer un nouveau quiz</h2>
        <form onSubmit={submit} className="space-y-5 bg-white rounded-lg shadow py-4 px-6">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block font-medium text-gray-700">Titre <span className="text-red-500">*</span> </label>
              <input
                type="text"
                value={data.title}
                onChange={(e) => setData('title', e.target.value)}
                className="w-full border p-2 mt-2 rounded focus:ring-orange-500 focus:border-orange-500 transition"
                required
              />
              {errors.title && <p className="text-red-500 text-sm">{errors.title}</p>}
            </div>

            <div>
              <label className="block font-medium text-gray-700">Module concerné <span className="text-red-500">*</span> </label>
              <select
                value={data.module_id}
                onChange={(e) => setData('module_id', e.target.value)}
                className="w-full border p-2 mt-2 rounded focus:ring-orange-500 focus:border-orange-500 transition"
                required
              >
                <option value="">-- Sélectionner --</option>
                {modules.map((m) => (
                  <option key={m.id} value={m.id}>
                    {m.nom}
                  </option>
                ))}
              </select>
              {errors.module_id && <p className="text-red-500 text-sm">{errors.module_id}</p>}
            </div>
          </div>

          <div>
            <label className="block font-medium">Description</label>
            <textarea
              rows={4}
              value={data.description}
              onChange={(e) => setData('description', e.target.value)}
              className="w-full border p-2 mt-2 rounded resize-none overflow-y-auto focus:ring-orange-500 focus:border-orange-500 transition"
            />
            {errors.description && <p className="text-red-500 text-sm">{errors.description}</p>}
          </div>

          <div className="grid grid-cols-2 gap-4">
            <div>
              <label className="block font-medium">Note de réussite <span className="text-red-500">*</span> </label>
              <input
                type="number"
                value={data.note_reussite}
                onChange={(e) => setData('note_reussite', e.target.value)}
                className="w-full border p-2 mt-2 rounded focus:ring-orange-500 focus:border-orange-500 transition"
                required
              />
              {errors.note_reussite && <p className="text-red-500 text-sm">{errors.note_reussite}</p>}
            </div>

            <div>
              <label className="block font-medium">Durée (minutes)</label>
              <input
                type="number"
                value={data.duration}
                onChange={(e) => setData('duration', e.target.value)}
                className="w-full border p-2 mt-2 rounded focus:ring-orange-500 focus:border-orange-500 transition"
              />
              {errors.duration && <p className="text-red-500 text-sm">{errors.duration}</p>}
            </div>
          </div>

          <div className="grid grid-cols-2 gap-4">
            <div>
              <label className="block font-medium">Disponible du</label>
              <input
                type="datetime-local"
                value={data.disponible_du}
                onChange={(e) => setData('disponible_du', e.target.value)}
                className="w-full border p-2 mt-2 rounded focus:ring-orange-500 focus:border-orange-500 transition"
              />
              {errors.disponible_du && <p className="text-red-500 text-sm">{errors.disponible_du}</p>}
            </div>

            <div>
              <label className="block font-medium">Disponible jusqu'au</label>
              <input
                type="datetime-local"
                value={data.disponible_jusquau}
                onChange={(e) => setData('disponible_jusquau', e.target.value)}
                className="w-full border p-2 mt-2 rounded focus:ring-orange-500 focus:border-orange-500 transition"
              />
              {errors.disponible_jusquau && <p className="text-red-500 text-sm">{errors.disponible_jusquau}</p>}
            </div>
          </div>

          <div className="text-right">
            <button
              type="submit"
              disabled={processing}
              className="bg-orange-600 text-white p-2 rounded hover:orange-700 mt-1"
            >
              Créer le quiz
            </button>
          </div>
        </form>
      </div>
    </DashboardLayout>
  )
}
