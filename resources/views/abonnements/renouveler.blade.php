<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Renouveler l'abonnement - FileFlow</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 30px; }
        .carte { background: white; max-width: 420px; margin: auto; padding: 25px; border-radius: 12px; box-shadow: 0 3px 10px #00000012; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; box-sizing: border-box; }
        button { margin-top: 20px; background: #2563eb; color: white; border: none; padding: 12px 20px; border-radius: 6px; cursor: pointer; width: 100%; }
        .erreur { color: #dc3545; }
    </style>
</head>
<body>
<div class="carte">
    <h2>Renouveler l'abonnement</h2>
    <p>Établissement : {{ $abonnement->etablissement?->nom ?? '—' }}</p>
    <p><strong>Montant :</strong> {{ number_format($montant, 0, ',', ' ') }} {{ $devise }} (1 an)</p>

    @if($errors->any())
        <p class="erreur">{{ $errors->first() }}</p>
    @endif

    <form action="{{ route('abonnement.payer-renouvellement') }}" method="POST">
        @csrf
        <label>Réseau</label>
        <select name="network" required>
            <option value="MTN">MTN</option>
            <option value="ORANGE">ORANGE</option>
        </select>

        <label>Numéro de téléphone</label>
        <input type="tel" name="phone_number" placeholder="6XXXXXXXX" required>

        <button type="submit">Valider et payer</button>
    </form>

    <a href="{{ url('/admin/tableau') }}">← Retour au tableau de bord</a>
</div>
</body>
</html>
