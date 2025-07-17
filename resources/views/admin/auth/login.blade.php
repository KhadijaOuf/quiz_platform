<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css'])
</head>

<body
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-pink-100 via-yellow-100 to-orange-100">

    <form method="POST" action="{{ route('admin.login') }}"
        class="bg-white shadow-xl rounded-2xl px-8 py-10 w-full max-w-md animate-fade-in">
        @csrf

        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-800 mb-2">Connexion Admin</h2>
            <p class="text-gray-500 text-sm">Accédez à l’espace sécurisé d’administration</p>
        </div>

        {{-- Message d’erreur général --}}
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600 bg-red-100 px-4 py-2 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Email --}}
        <div class="mb-5">
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Adresse Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 transition">
        </div>

        {{-- Mot de passe --}}
        <div class="mb-5">
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Mot de passe</label>
            <input type="password" id="password" name="password" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 transition">
        </div>

        {{-- Se souvenir de moi --}}
        <div class="mb-5 flex items-center">
            <input type="checkbox" name="remember" id="remember" class="mr-2 rounded">
            <label for="remember" class="text-sm text-gray-600">Se souvenir de moi</label>
        </div>

        {{-- Bouton --}}
        <button type="submit"
            class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition">
            Se connecter
        </button>
    </form>

</body>

</html>