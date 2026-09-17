<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivre mon ticket - FileFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">

        <div class="text-center mb-6">
            <img src="{{ asset('image/fileflow-icon-512.png') }}"
                 alt="FileFlow"
                 class="w-20 h-20 mx-auto mb-3">

            <h1 class="text-2xl font-bold">
                Suivre mon ticket
            </h1>

            <p class="text-gray-600 mt-2">
                Entrez votre numéro de ticket pour consulter son état et votre position dans la file.
            </p>
        </div>

        <form id="suiviTicketForm" method="POST" action="{{ route('tickets.suivre.rechercher') }}">
    @csrf
            <label for="ticket" class="block mb-2 font-medium">
                Numéro du ticket
            </label>

            <input type="text"
                   id="ticket"
                   name="numero"
                   required
                   placeholder="Exemple : T-6AA21D28262CA"
                   class="w-full border rounded-lg px-4 py-3 mb-4">

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg">
                Consulter mon ticket
            </button>
        </form>

    </div>

   

</body>
</html>