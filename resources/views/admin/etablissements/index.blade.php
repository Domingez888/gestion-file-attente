<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Établissements - FileFlow</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

    <header class="bg-blue-700 text-white px-6 py-4">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('image/fileflow-icon-512.png') }}"
                     alt="FileFlow"
                     style="width: 45px; height: 45px; object-fit: contain;">

                <div>
                    <h1 class="text-xl font-bold">FileFlow</h1>
                    <p class="text-sm">Administration</p>
                </div>
            </div>

            <a href="{{ route('admin.tableau') }}"
               class="bg-white text-blue-700 px-4 py-2 rounded-lg">
                Tableau de bord
            </a>
        </div>
    </header>

    <main class="max-w-6xl mx-auto p-6">
        @if (session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
@endif

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-3xl font-bold">
                    Gestion des établissements
                </h2>

                <p class="text-gray-600 mt-1">
                    Liste des établissements enregistrés dans FileFlow.
                </p>
            </div>
<a href="{{ route('admin.etablissements.creer') }}"
   class="bg-orange-500 text-white px-5 py-3 rounded-lg">
    + Ajouter un établissement
</a>

        <div class="bg-white rounded-xl shadow overflow-hidden">

            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left p-4">Nom</th>
                        <th class="text-left p-4">Adresse</th>
                        <th class="text-left p-4">Téléphone</th>
                        <th class="text-left p-4">Employés</th>
                        <th class="text-left p-4">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($etablissements as $etablissement)
                        <tr class="border-t">
                            <td class="p-4">{{ $etablissement->nom }}</td>
                            <td class="p-4">{{ $etablissement->adresse }}</td>
                            <td class="p-4">
                                {{ $etablissement->telephone ?? '-' }}
                            </td>
                            <td class="p-4">
                                {{ $etablissement->employes_count }}
                            </td>
                         <td class="p-4">
    <div class="flex items-center gap-4">

        <a href="{{ route('admin.etablissements.modifier', $etablissement) }}"
           class="text-blue-600 font-semibold hover:underline">
            Modifier
        </a>

        <form method="POST"
              action="{{ route('admin.etablissements.destroy', $etablissement) }}"
              onsubmit="return confirm('Voulez-vous vraiment supprimer cet établissement ?');">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="text-red-600 font-semibold hover:underline">
                Supprimer
            </button>
        </form>

    </div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="p-8 text-center text-gray-500">
                                Aucun établissement enregistré.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </main>

</body>
</html>