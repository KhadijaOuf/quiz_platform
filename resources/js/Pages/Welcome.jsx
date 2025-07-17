import { Head, Link } from '@inertiajs/react';

export default function Welcome({ auth, laravelVersion, phpVersion }) {
    const handleImageError = () => {
        document
            .getElementById('screenshot-container')
            ?.classList.add('!hidden');
        document.getElementById('docs-card')?.classList.add('!row-span-1');
        document
            .getElementById('docs-card-content')
            ?.classList.add('!flex-row');
        document.getElementById('background')?.classList.add('!hidden');
    };

    return (
        <>
            <Head title="Welcome" />
            <div className="bg-gradient-to-br from-pink-100 via-yellow-100 to-orange-100 text-black/50 dark:bg-black dark:text-white/50">
                <div className="relative flex min-h-screen flex-col items-center justify-center">
                    <div className="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                        <header className="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                            <div className="flex lg:col-start-2 lg:justify-center">
                                // logo
                            </div>
                            <nav className="-mx-3 flex flex-1 justify-end">
                                {auth.user ? (
                                    <Link
                                        href={route('dashboard')}
                                        className="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        Dashboard
                                    </Link>
                                ) : null}
                            </nav>
                        </header>

                        <main className="mt-10 mb-20 px-6 py-16 max-h-screen">
                            <div className="max-w-4xl mx-auto text-center">
                                <h1 className="text-5xl sm:text-6xl font-extrabold text-gray-800 mb-6 leading-tight">
                                Bienvenue sur notre <br></br><span className="text-orange-600">plateforme de quiz</span> interactive !
                                </h1>
                                <p className="text-lg sm:text-xl text-gray-700 mb-4 leading-relaxed">
                                    Créez, passez et maîtrisez vos quiz en toute simplicité.
                                </p>
                                <p className="text-base sm:text-lg text-gray-600 mb-8 leading-relaxed">
                                    Que vous soyez <span className="font-bold text-gray-700">formateur</span> ou <span className="font-bold text-gray-700">étudiant</span>,
                                    profitez d’un espace dédié pour apprendre en vous amusant.
                                </p>
                                <div className="flex flex-col sm:flex-row justify-center gap-4">
                                    <a
                                        href="/login/formateur"
                                        className="bg-orange-600 hover:bg-orange-500 text-white px-6 py-3 rounded-full font-semibold shadow-md transition duration-200"
                                    >
                                        Espace Formateur
                                    </a>
                                    <a
                                        href="/login/etudiant"
                                        className="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-full font-semibold shadow-md transition duration-200"
                                    >
                                        Espace Étudiant
                                    </a>
                                </div>
                            </div>
                        </main>



                        <footer className="py-1 text-center text-sm text-gray-600 dark:text-white/70">
                            Made By Khadija.OUf
                        </footer>
                    </div>
                </div>
            </div>
        </>
    );
}
