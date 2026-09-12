<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Instruction de paiement</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app-design.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container-ticket">
        <div class="ticket-stub p-4 text-center">
            <h3 class="mb-4">Paiement en attente</h3>

            @if ($instruction)
                <div class="alert alert-ticket-neutral p-3 mb-3">
                    {{ $instruction }}
                </div>
                <p class="text-muted-slate small">
                    Une fois le paiement autorisé sur votre téléphone, revenez sur cette page ou rafraîchissez pour vérifier le statut.
                </p>
            @else
                <div class="alert alert-ticket-danger p-3 mb-3">
                    Aucune instruction reçue de Flutterwave. Réessayez ou contactez le support.
                </div>
            @endif

            <a href="{{ url('/') }}" class="btn btn-outline-slate mt-3">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>