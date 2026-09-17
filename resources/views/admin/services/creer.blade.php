<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un service - FileFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

<main class="max-w-2xl mx-auto p-6">

    <div class="bg-white rounded-xl shadow p-6">

        <h1 class="text-2xl font-bold mb-6">
            Ajouter un service
        </h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('admin.services.store') }}">

            @csrf

            <div class="mb-4">
                <label class="block mb-2 font-medium">
                    Établissement
                </label>

                <select name="etablissement_id"
                        required
                        class="w-full border rounded-lg px-3 py-2">

                    <option value="">
                        -- Choisir un établissement --
                    </option>

                    @foreach ($etablissements as $etablissement)
                        <option value="{{ $etablissement->id }}"
                            @selected(old('etablissement_id') == $etablissement->id)>
                            {{ $etablissement->nom }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">
                    Nom du service
                </label>

                <input type="text"
                       name="nom"
                       value="{{ old('nom') }}"
                       required
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">
                    Secteur
                </label>

                <input type="text"
                       name="secteur"
                       value="{{ old('secteur') }}"
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">
                    Adresse
                </label>

                <input type="text"
                       name="adresse"
                       value="{{ old('adresse') }}"
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-6">
                <label class="block mb-2 font-medium">
                    Prix (FCFA)
                </label>

                <input type="number"
                       name="prix"
                       value="{{ old('prix', 0) }}"
                       min="0"
                       required
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="flex justify-between items-center">

                <a href="{{ route('admin.services.index') }}"
                   class="text-gray-600">
                    ← Annuler
                </a>

                <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded-lg">
                    Ajouter le service
                </button>

            </div>

        </form>

    </div>

</main>

</body>
</html>