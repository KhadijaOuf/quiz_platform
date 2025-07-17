import { Head, useForm } from '@inertiajs/react'

export default function LoginEtudiant() {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        role: 'etudiant',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('login')); // Breeze login route
    };

    return (
        <>
        
            <Head title="Connexion Étudiant" />
            <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-pink-100 via-yellow-100 to-orange-100 p-4">
                <div className='w-full max-w-md'>
                    <div className="mb-3">
                        <a
                            href="/"
                            className="text-sm text-gray-600 hover:text-orange-600 "
                        >
                            ← Retour à l'accueil
                        </a>
                    </div>
                    <div className="bg-white p-8 rounded-xl shadow-md w-full">
                        <h1 className="text-3xl font-extrabold text-center text-gray-800 mb-6">
                            Espace Étudiant
                        </h1>

                        <form onSubmit={submit} className="space-y-4">
                            <div>
                                <label className="block text-gray-700 font-semibold mb-1">Email</label>
                                <input
                                    type="email"
                                    name="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    required
                                    className="w-full border rounded px-3 py-2 shadow-sm focus:ring-orange-500 focus:border-orange-500 transition"
                                />
                                {errors.email && (
                                    <p className="text-red-500 text-sm">{errors.email}</p>
                                )}
                            </div>

                            <div>
                                <label className="block text-gray-700 font-semibold mb-1">Mot de passe</label>
                                <input
                                    type="password"
                                    name="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    required
                                    className="w-full border rounded px-3 py-2 shadow-sm focus:ring-orange-500 focus:border-orange-500 transition"
                                />
                                {errors.password && (
                                    <p className="text-red-500 text-sm">{errors.password}</p>
                                )}
                            </div>

                            <div className="flex items-center">
                                <input
                                    type="checkbox"
                                    id="remember"
                                    checked={data.remember}
                                    onChange={(e) => setData('remember', e.target.checked)}
                                    className="mr-2"
                                />
                                <label htmlFor="remember" className="text-sm text-gray-600">
                                    Se souvenir de moi
                                </label>
                            </div>

                            <p className="text-sm text-right mt-1">
                                <a href={route('password.request')} className="text-orange-600 hover:underline">
                                    Mot de passe oublié ?
                                </a>
                            </p>

                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 rounded"
                            >
                                Connexion
                            </button>
                        </form>
                    </div>
                </div> 
            </div>
        </>
    );
}
