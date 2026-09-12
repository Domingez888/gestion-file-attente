<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app-design.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container-ticket">
        <div class="ticket-stub p-4">
            <h3 class="mb-4">Ajouter un service</h3>

            @if ($errors->any())
                <div class="alert alert-ticket-danger p-3 mb-3">
                    @foreach ($errors->all() as $erreur)
                        <div>{{ $erreur }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('services.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nom du service</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Secteur</label>
                    <input type="text" name="secteur" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="adresse" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Prix</label>
                    <input type="number" name="prix" class="form-control" min="0" step="1" required>
                </div>

                <button type="submit" class="btn btn-indigo w-100">Ajouter</button>
            </form>
        </div>
    </div>
</body>
</html>