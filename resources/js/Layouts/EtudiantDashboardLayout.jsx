import React, { useState } from 'react'
import { Link, usePage, useForm } from '@inertiajs/react'

export default function EtudiantDashboardLayout({ children }) {
  const { auth, url } = usePage().props
  const [dropdownOpen, setDropdownOpen] = useState(false)

  const toggleDropdown = () => setDropdownOpen(!dropdownOpen)

  const { post } = useForm()

  const logout = (e) => {
    e.preventDefault()
    post('logout')
  }

  return (
    <div className="flex min-h-screen bg-gradient-to-br from-pink-100 via-yellow-100 to-orange-100 text-gray-800">
      {/* Sidebar */}
      <aside className="w-60 bg-white shadow-md hidden md:block">
        <div className="p-4 pt-7 pb-7 text-lg font-bold text-gray-800 border-b border-gray-200">
          Espace Etudiant
        </div>
        <nav className="p-4 space-y-2 text-sm">
          <SidebarLink href="" label="Accueil" />
          <SidebarLink href="" label="" />
          <SidebarLink href="" label="Mes Quizzes" />
          <SidebarLink href="" label="Correction" />
          <SidebarLink href="" label="" />
        </nav>
      </aside>

      {/* Main area */}
      <div className="flex-1 flex flex-col">
        {/* Header */}
        <header className="px-6 p-6 flex justify-between items-center">
          <h1 className="text-sm text-gray-600 mt-1">
            Bonjour, <strong>{auth?.user?.name}</strong>
          </h1>

          <div className="relative">
            <button
              onClick={toggleDropdown}
              className="flex items-center space-x-2 focus:outline-none"
            >
              <img
                src="/images/profile.png"
                alt="Profil"
                className="w-10 h-10 rounded-full border border-gray-900 shadow"
              />
            </button>

            {dropdownOpen && (
              <ul className="absolute right-0 mt-2 w-48 bg-white border rounded shadow-md z-50 text-sm">
                <li>
                  <Link
                    href="#"
                    className="block px-4 py-2 hover:bg-gray-100"
                  >
                    Profil
                  </Link>
                </li>
                <li>
                  <Link
                    href="#"
                    className="block px-4 py-2 hover:bg-gray-100"
                  >
                    Paramètres
                  </Link>
                </li>
                <li>
                    <button
                      onClick={logout}
                      className="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600"
                    >
                      Déconnexion
                    </button>
                </li>
              </ul>
            )}
          </div>
        </header>

        {/* Content */}
        <main className="flex-1 p-6">{children}</main>
      </div>
    </div>
  )
}

function SidebarLink({ href, label }) {
  const { url } = usePage()
  const active = url.startsWith(href)

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
