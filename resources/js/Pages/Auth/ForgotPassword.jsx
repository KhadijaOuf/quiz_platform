import { Head, useForm } from '@inertiajs/react'

export default function ForgotPassword({ status }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
    })

    const submit = (e) => {
        e.preventDefault()
        post(route('password.email'))
    }

    return (
        <>
            <Head title="Mot de passe oublié" />
            <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-pink-100 via-yellow-100 to-orange-100 p-4">
                <div className="bg-white rounded-xl shadow-md p-8 w-full max-w-md">
                    <h1 className="text-3xl font-bold text-gray-800 text-center mb-6">
                        Mot de passe oublié ?
                    </h1>

                    <p className="text-gray-600 text-sm mb-6 text-center leading-relaxed">
                        Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
                    </p>

                    {status && (
                        <div className="mb-4 text-sm text-green-600 font-semibold text-center">
                            {status}
                        </div>
                    )}

                    <form onSubmit={submit} className="space-y-4">
                        <div>
                            <label htmlFor="email" className="block text-gray-700 font-medium mb-1">
                                Adresse email
                            </label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                required
                                autoFocus
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                className="w-full px-4 py-2 border rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500"
                            />
                            {errors.email && (
                                <p className="text-red-500 text-sm mt-1">{errors.email}</p>
                            )}
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 rounded-md transition"
                        >
                            Envoyer le lien de réinitialisation
                        </button>
                    </form>
                </div>
            </div>
        </>
    )
}
