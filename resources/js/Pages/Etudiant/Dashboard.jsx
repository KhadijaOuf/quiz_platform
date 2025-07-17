import { Head, useForm } from '@inertiajs/react';

export default function Dashboard({ user }) {
    const { post } = useForm();

    const handleLogout = () => {
        post(route('logout'));
    };

    return (
        <>
            <Head title="Tableau de bord étudiant" />
            <div className="min-h-screen bg-orange-50 flex items-center justify-center">
                <div className="p-6 bg-white rounded shadow text-center">
                    <h1 className="text-2xl font-bold mb-2">Bienvenue, {user.name} !</h1>
                    <p className="text-gray-600">Ceci est ton espace étudiant ✨</p>
                    <button
                        onClick={handleLogout}
                        className="mt-4 px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded"
                    >
                        Se déconnecter
                    </button>
                </div>
            </div>
        </>
    );
}
