import React, { useState } from 'react'
import { Link, usePage } from '@inertiajs/react'
import SidebarLink from '@/Components/SidebarLink'

export default function FormateurDashboardLayout({ children }) {
  const { auth } = usePage().props
  const [dropdownOpen, setDropdownOpen] = useState(false)

  const toggleDropdown = () => setDropdownOpen(!dropdownOpen)

  return (
    <div
      className="flex min-h-screen bg-gradient-to-br from-pink-100 via-yellow-100 to-orange-100 text-gray-800 overflow-x-hidden"
      style={{ overflowX: 'hidden' }} // pour s'assurer pas de scroll horizontal
    >
      {/* Sidebar fixe */}
      <aside className="w-60 bg-white shadow-md hidden md:block fixed h-screen top-0 left-0">
        <div className="p-4 pt-7 pb-7 text-lg font-bold text-gray-800 border-b border-gray-200">
          Espace Formateur
        </div>
        <nav className="p-4 space-y-2 text-sm">
          <SidebarLink href="/formateur/dashboard" label="Accueil" />
          <SidebarLink href="/formateur/modules" label="Mes Modules" />
          <SidebarLink href="/formateur/quizzes" label="Mes Quizzes" />
          <SidebarLink href="/formateur/quizzes/create" label="Créer un Quiz" />
          <SidebarLink href="/formateur/correction" label="Correction" />
          <SidebarLink href="/formateur/quizzes/archives" label="Archives" />
        </nav>
      </aside>

      {/* Contenu principal qui prend la largeur restante */}
      <div
        className="flex flex-col"
        style={{
          marginLeft: '15rem', // = 240px = w-60
          width: 'calc(100vw - 15rem)',
          minHeight: '100vh',
        }}
      >
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
                  <Link
                    href="/logout"
                    method="post"
                    as="button"
                    className="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600"
                  >
                    Déconnexion
                  </Link>
                </li>
              </ul>
            )}
          </div>
        </header>

        {/* Content */}
        <main className="flex-1 p-6 overflow-auto">{children}</main>
      </div>
    </div>
  )
}

