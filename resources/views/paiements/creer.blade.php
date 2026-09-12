<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app-design.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container-ticket">
        <div class="ticket-stub p-4">
            <h3 class="mb-1">Paiement du service</h3>
            <p class="text-muted-slate mb-4">Réglez votre ticket avant de rejoindre la file</p>

            @if (session('error'))
                <div class="alert alert-ticket-danger p-3 mb-3">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-ticket-danger p-3 mb-3">
                    @foreach ($errors->all() as $erreur)
                        <div>{{ $erreur }}</div>
                    @endforeach
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Service</label>
                <input type="text" class="form-control" value="{{ $service->nom }}" disabled>
            </div>

            <div class="mb-4">
                <label class="form-label">Montant à payer</label>
                <div class="ticket-number">{{ number_format($service->prix, 0, ',', ' ') }} <span style="font-size: 1.1rem; color: var(--slate);">XAF</span></div>
            </div>

            <form method="POST" action="{{ route('paiements.payer', $service) }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Réseau mobile money</label>
                    <select name="network" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="MTN">MTN Mobile Money</option>
                        <option value="ORANGE">Orange Money</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Numéro de téléphone</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background: var(--paper); border-color: var(--slate-light); color: var(--slate);">+237</span>
                        <input type="text" name="phone_number" class="form-control" placeholder="6XXXXXXXX" pattern="[0-9]{9}" maxlength="9" required>
                    </div>
                    <div class="text-muted-slate small mt-1">9 chiffres, sans le +237 (ex : 650000000)</div>
                </div>

                <button type="submit" class="btn btn-indigo w-100">Payer maintenant</button>
            </form>
        </div>
    </div>
</body>
</html>