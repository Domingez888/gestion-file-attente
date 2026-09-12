<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes services</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app-design.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container" style="max-width: 700px; margin: 60px auto 0; padding: 0 1rem;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <form method="POST" action="{{ url('/deconnexion') }}">
                @csrf
                <button type="submit" class="btn btn-outline-slate btn-sm">Déconnexion</button>
            </form>

            <h3 class="mb-0">Mes services</h3>
            <a href="{{ route('services.create') }}" class="btn btn-indigo btn-sm">+ Ajouter un service</a>
        </div>

        @if (session('succes'))
            <div class="alert alert-ticket-success p-3 mb-3">{{ session('succes') }}</div>
        @endif
        @if (session('info'))
            <div class="alert alert-ticket-neutral p-3 mb-3">{{ session('info') }}</div>
        @endif

        <div class="card-ticket">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="text-muted-slate ps-3">Nom</th>
                        <th class="text-muted-slate">Secteur</th>
                        <th class="text-muted-slate">Adresse</th>
                        <th class="text-muted-slate">Prix</th>
                        <th class="text-muted-slate">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                        <tr>
                            <td class="ps-3">{{ $service->nom }}</td>
                            <td>{{ $service->secteur }}</td>
                            <td>{{ $service->adresse }}</td>
                            <td>{{ number_format($service->prix, 0, ',', ' ') }} XAF</td>
                            <td>
                                <form method="POST" action="{{ route('services.destroy', $service->id) }}" onsubmit="return confirm('Supprimer ce service ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm" style="background: var(--danger-bg); color: var(--danger); border-color: var(--danger);">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted-slate py-3">Aucun service pour l'instant</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>