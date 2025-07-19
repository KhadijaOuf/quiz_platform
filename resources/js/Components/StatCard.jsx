import React from 'react'

export default function StatCard({ Icon, label, value, color }) {
    return (
    <div className="bg-white p-4 rounded shadow flex items-center space-x-4">
      <Icon className={`h-8 w-8 ${color}`} />
      <div>
        <p className="text-lg font-medium">{value}</p>
        <p className="text-sm text-gray-500">{label}</p>
      </div>
    </div>
  )
}