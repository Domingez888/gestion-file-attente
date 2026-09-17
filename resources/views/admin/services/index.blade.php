<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - FileFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

<main class="max-w-5xl mx-auto p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">
            Gestion des services
        </h1>

        <a href="{{ route('admin.services.creer') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Ajouter un service
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3 text-left">Service</th>
                    <th class="p-3 text-left">Établissement</th>
                    <th class="p-3 text-left">Secteur</th>
                    <th class="p-3 text-left">Prix</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($services as $service)
                    <tr class="border-t">
                        <td class="p-3">
                            {{ $service->nom }}
                        </td>

                        <td class="p-3">
                            {{ $service->etablissement?->nom ?? 'Non affecté' }}
                        </td>

                        <td class="p-3">
                            {{ $service->secteur ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ number_format($service->prix ?? 0, 0, ',', ' ') }} FCFA
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            class="p-6 text-center text-gray-500">
                            Aucun service enregistré.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.tableau') }}"
           class="text-blue-600">
            ← Retour au tableau de bord
        </a>
    </div>

</main>

</body>
</html>