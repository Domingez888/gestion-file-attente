<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Modifier un établissement - FileFlow</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

<header class="bg-blue-700 text-white px-6 py-4">
    <div class="max-w-4xl mx-auto flex justify-between items-center">

        <div class="flex items-center gap-3">
            <img src="{{ asset('image/fileflow-icon-512.png') }}"
                 alt="FileFlow"
                 style="width: 45px; height: 45px; object-fit: contain;">

            <div>
                <h1 class="text-xl font-bold">FileFlow</h1>
                <p class="text-sm">Administration</p>
            </div>
        </div>

        <a href="{{ route('admin.etablissements.index') }}"
           class="bg-white text-blue-700 px-4 py-2 rounded-lg">
            Retour
        </a>

    </div>
</header>

<main class="max-w-2xl mx-auto p-6">

    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-2xl font-bold mb-6">
            Modifier l'établissement
        </h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
      action="{{ route('admin.etablissements.update', $etablissement) }}">
        @csrf
               @method('PUT')
            

            <div class="mb-4">
                <label class="block font-semibold mb-2">
                    Nom
                </label>

                <input type="text"
                       name="nom"
                       value="{{ old('nom', $etablissement->nom) }}"
                       required
                       class="w-full border rounded-lg px-4 py-3">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">
                    Adresse
                </label>

                <input type="text"
                       name="adresse"
                       value="{{ old('adresse', $etablissement->adresse) }}"
                       required
                       class="w-full border rounded-lg px-4 py-3">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">
                    Téléphone
                </label>

                <input type="text"
                       name="telephone"
                       value="{{ old('telephone', $etablissement->telephone) }}"
                       class="w-full border rounded-lg px-4 py-3">
            </div>

            <div class="mb-6">
                <label class="block font-semibold mb-2">
                    Email
                </label>

                <input type="email"
                       name="email"
                       value="{{ old('email', $etablissement->email) }}"
                       class="w-full border rounded-lg px-4 py-3">
            </div>

            <div class="flex gap-3">

                <button type="submit"
                        class="bg-orange-500 text-white px-5 py-3 rounded-lg">
                    Enregistrer les modifications
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