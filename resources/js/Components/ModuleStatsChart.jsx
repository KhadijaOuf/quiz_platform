import React from 'react'
import { BarChart, Bar, XAxis, YAxis, Tooltip, ResponsiveContainer } from 'recharts'

export default function ModuleStatsChart({ data }) {
  return (
    <div className="bg-white rounded shadow p-4">
      <h4 className="text-lg font-semibold mb-2">Quiz par module</h4>
      <ResponsiveContainer width="100%" height={300}>
        <BarChart data={data}>
          <XAxis dataKey="nom" />
          <YAxis />
          <Tooltip />
          <Bar dataKey="quiz_count" fill="#6366f1" />
        </BarChart>
      </ResponsiveContainer>
    </div>
  )
}
