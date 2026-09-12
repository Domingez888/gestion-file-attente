<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de paiement</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app-design.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container-ticket">
        <div class="ticket-stub p-4 text-center">
            @if ($statut === 'successful' || $statut === 'success')
                <h3 class="mb-3" style="color: var(--success);">Paiement réussi</h3>
                <div class="alert alert-ticket-success p-3 mb-3">
                    Votre paiement a bien été confirmé. Merci !
                </div>
            @elseif ($statut === 'failed' || $statut === 'cancelled')
                <h3 class="mb-3" style="color: var(--danger);">Paiement échoué</h3>
                <div class="alert alert-ticket-danger p-3 mb-3">
                    Le paiement n'a pas pu être confirmé. Vous pouvez réessayer.
                </div>
            @else
                <h3 class="mb-3">Statut du paiement : {{ $statut }}</h3>
                <div class="alert alert-ticket-neutral p-3 mb-3">
                    Nous n'avons pas pu déterminer clairement le résultat. Vérifiez votre historique de paiement.
                </div>
            @endif

            <a href="{{ url('/') }}" class="btn btn-outline-slate mt-3">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>