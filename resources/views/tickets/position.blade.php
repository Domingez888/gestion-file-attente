<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma position dans la file</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app-design.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container-ticket">
        <div class="ticket-stub p-4 text-center">
            <h3 class="mb-4">Suivi du ticket {{ $ticket->numero }}</h3>

            @if ($ticket->statut !== 'en attente')
                <div class="alert alert-ticket-neutral p-3">
                    Ce ticket n'est plus en attente (statut actuel : <strong>{{ $ticket->statut }}</strong>).
                </div>
            @else
                <div class="ticket-number mb-1">{{ $position }}</div>
                <p class="text-muted-slate mb-0">personne(s) devant vous</p>

                <p class="mt-4">
                    Temps d'attente estimé :
                    <strong style="color: var(--indigo);">{{ $tempsEstime }} minute(s)</strong>
                </p>
            @endif

            <table class="table mt-4 text-start">
                <tr>
                    <th class="text-muted-slate" style="width: 40%;">File</th>
                    <td>{{ $file->nom }}</td>
                </tr>
                <tr>
                    <th class="text-muted-slate">Statut de la file</th>
                    <td>{{ $file->statut }}</td>
                </tr>
            </table>

            <a href="{{ route('tickets.position', $ticket->id) }}" class="btn btn-outline-slate mt-2">
                Actualiser
            </a>
        </div>
    </div>
</body>
</html>