<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Employé</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app-design.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container" style="max-width: 900px; margin: 50px auto 0; padding: 0 1rem;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Tableau de bord — Gestion des files</h3>
            <form method="POST" action="{{ url('/deconnexion') }}">
                @csrf
                <button type="submit" class="btn btn-outline-slate btn-sm">Déconnexion</button>
            </form>
        </div>

        @if (session('succes'))
            <div class="alert alert-ticket-success p-3 mb-3">{{ session('succes') }}</div>
        @endif
        @if (session('info'))
            <div class="alert alert-ticket-neutral p-3 mb-3">{{ session('info') }}</div>
            @if (session('info'))
    <div class="alert alert-ticket-neutral p-3 mb-3">{{ session('info') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-ticket-danger p-3 mb-3">{{ session('error') }}</div>
@endif
        @endif

        @foreach ($files as $file)
            <div class="card-ticket mb-4">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">
                            {{ $file->nom }}
                            <span class="small" style="color: var(--slate); font-weight: 400;">({{ $file->statut }})</span>
                        </h5>
                        <form method="POST" action="{{ route('employe.appelerSuivant', $file->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-indigo btn-sm">Appeler suivant</button>
                        </form>
                    </div>

                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th class="text-muted-slate">Numéro</th>
                                <th class="text-muted-slate">Statut</th>
                                <th class="text-muted-slate">Créé le</th>
                                <th class="text-muted-slate">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($file->tickets as $ticket)
                                <tr>
                                    <td style="font-family: var(--font-display); font-weight: 600;">{{ $ticket->numero }}</td>
                                    <td>{{ $ticket->statut }}</td>
                                    <td>{{ $ticket->heureCreation }}</td>
                                    <td>
                                        @if ($ticket->statut === 'appele')
                                            <form method="POST" action="{{ route('employe.marquerTraite', $ticket->id) }}" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm" style="background: var(--success); color: #fff; border-color: var(--success);">Traité</button>
                                            </form>
                                            <form method="POST" action="{{ route('employe.marquerAbsent', $ticket->id) }}" class="d-inline">
                                                @csrf
                                                <button class="btn btn-amber btn-sm">Absent</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted-slate">Aucun ticket</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>