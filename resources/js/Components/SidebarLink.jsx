import React from 'react'
import { Link, usePage } from '@inertiajs/react'

export default function SidebarLink({ href, label }) {
  const { url } = usePage()
  const active = url === href

  return (
    <Link
      href={href}
      className={`block px-4 py-2 rounded ${
        active
          ? 'bg-orange-100 text-orange-600 font-semibold'
          : 'hover:bg-orange-50 text-gray-700'
      }`}
    >
      {label}
    </Link>
  )
}