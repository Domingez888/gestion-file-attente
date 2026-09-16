<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un établissement - FileFlow</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

    <header class="bg-blue-700 text-white px-6 py-4">
        <div class="max-w-3xl mx-auto flex items-center gap-3">
            <img src="{{ asset('image/fileflow-icon-512.png') }}"
                 alt="FileFlow"
                 style="width: 45px; height: 45px; object-fit: contain;">

            <div>
                <h1 class="text-xl font-bold">FileFlow</h1>
                <p class="text-sm">Administration</p>
            </div>
        </div>
    </header>

    <main class="max-w-3xl mx-auto p-6">

        <div class="bg-white rounded-xl shadow p-6">

            <h2 class="text-2xl font-bold mb-6">
                Ajouter un établissement
            </h2>

            <form method="POST" action="{{ route('admin.etablissements.store') }}">
               @csrf

                <div class="mb-4">
                    <label for="nom" class="block mb-2 font-medium">
                        Nom de l'établissement
                    </label>

                    <input type="text"
                           id="nom"
                           name="nom"
                           value="{{ old('nom') }}"
                           required
                           class="w-full border rounded-lg px-4 py-3">
                </div>

                <div class="mb-4">
                    <label for="adresse" class="block mb-2 font-medium">
                        Adresse
                    </label>

                    <input type="text"
                           id="adresse"
                           name="adresse"
                           value="{{ old('adresse') }}"
                           required
                           class="w-full border rounded-lg px-4 py-3">
                </div>

                <div class="mb-4">
                    <label for="telephone" class="block mb-2 font-medium">
                        Téléphone
                    </label>

                    <input type="text"
                           id="telephone"
                           name="telephone"
                           value="{{ old('telephone') }}"
                           class="w-full border rounded-lg px-4 py-3">
                </div>

                <div class="mb-6">
                    <label for="email" class="block mb-2 font-medium">
                        Email
                    </label>

                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="w-full border rounded-lg px-4 py-3">
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="bg-blue-600 text-white px-5 py-3 rounded-lg">
                        Enregistrer
                    </button>

                    <a href="{{ route('admin.etablissements.index') }}"
                       class="bg-gray-200 px-5 py-3 rounded-lg">
                        Annuler
                    </a>
                </div>

            </form>

        </div>

    </main>

</body>
</html>