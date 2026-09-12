<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app-design.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container-ticket">
        <div class="ticket-stub p-4">
            <h3 class="text-center mb-4">Créer un compte</h3>

            @if ($errors->any())
                <div class="alert alert-ticket-danger p-3 mb-3">
                    @foreach ($errors->all() as $erreur)
                        <div>{{ $erreur }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" value="{{ old('nom') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control" value="{{ old('telephone') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="motDePasse" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="motDePasse_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-indigo w-100">S'inscrire</button>
            </form>

            <p class="text-center mt-3 mb-0 text-muted-slate">
                Déjà un compte ? <a href="{{ route('login') }}" style="color: var(--indigo);">Se connecter</a>
            </p>
        </div>
    </div>
</body>
</html>