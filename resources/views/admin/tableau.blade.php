<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - FileFlow</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

    <header class="bg-blue-700 text-white px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <img src="{{ asset('image/fileflow-icon-512.png') }}"
                 alt="FileFlow"
                 style="width: 45px; height: 45px; object-fit: contain;">

            <div>
                <h1 class="text-xl font-bold">FileFlow</h1>
                <p class="text-sm">Espace administrateur</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="bg-white text-blue-700 px-4 py-2 rounded-lg">
                Déconnexion
            </button>
        </form>
    </header>

    <main class="max-w-5xl mx-auto p-6">

        <h2 class="text-3xl font-bold mb-2">
            Tableau de bord
        </h2>

        <p class="text-gray-600 mb-8">
            Gérez les établissements et les employés de FileFlow.
        </p>

        <a href="{{ route('admin.etablissements.index') }}"
   class="block bg-white rounded-xl shadow p-6 hover:shadow-lg transition">

            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-xl font-semibold mb-3">
                    Établissements
                </h3>

                <p class="text-4xl font-bold text-blue-600">
                    {{ $nombreEtablissements }}
                </p>

                <p class="text-gray-500 mt-2">
                    établissements enregistrés
                </p>
            </a>

            <a href="{{ route('admin.employes.index') }}"
   class="block bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <h3 class="text-xl font-semibold mb-3">
                    Employés
                </h3>

                <p class="text-4xl font-bold text-orange-500">
                    {{ $nombreEmployes }}
                </p>

                <p class="text-gray-500 mt-2">
                    employés enregistrés
                </p>
            </a>

        </div>

    </main>

</body>
</html>