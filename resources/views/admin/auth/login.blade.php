<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <form method="POST" action="{{ route('admin.login') }}" class="bg-white p-6 rounded shadow-md w-96">
        @csrf

        <h2 class="text-2xl font-bold mb-6 text-center">Connexion Administrateur</h2>

        {{-- Message d’erreur général --}}
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Champ email --}}
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Adresse Email</label>
            <input type="email" id="email" name="email" required autofocus
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('email') }}">
        </div>

        {{-- Champ mot de passe --}}
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
            <input type="password" id="password" name="password" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>

        {{-- Remember me --}}
        <div class="mb-4 flex items-center">
            <input type="checkbox" name="remember" id="remember" class="mr-2">
            <label for="remember" class="text-sm text-gray-600">Se souvenir de moi</label>
        </div>

        {{-- Bouton de connexion --}}
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
            Se connecter
        </button>
    </form>
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

</body>

</html>