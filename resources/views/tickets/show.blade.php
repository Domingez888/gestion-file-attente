<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon ticket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app-design.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container-ticket">
        <div class="ticket-stub p-4 text-center">
            @if (session('success'))
                <div class="alert alert-ticket-success p-3 mb-3">
                    {{ session('success') }}
                </div>
            @endif

            <h3 class="mb-3">Votre ticket</h3>

            <div class="ticket-number mb-4">{{ $ticket->numero }}</div>

            <table class="table mt-2 text-start">
                <tr>
                    <th class="text-muted-slate" style="width: 40%;">Statut</th>
                    <td>{{ $ticket->statut }}</td>
                </tr>
                <tr>
                    <th class="text-muted-slate">Créé le</th>
                    <td>{{ $ticket->heureCreation }}</td>
                </tr>
                <tr>
                    <th class="text-muted-slate">File</th>
                    <td>{{ $ticket->file?->nom ?? 'Aucune file' }}</td>
                </tr>
            </table>

            <div class="d-grid gap-2 mt-4">
                <a href="{{ route('tickets.position', $ticket->id) }}" class="btn btn-indigo">Voir ma position dans la file</a>
                <a href="{{ route('tickets.create') }}" class="btn btn-outline-slate">Prendre un autre ticket</a>
            </div>
        </div>
    </div>
</body>
</html>