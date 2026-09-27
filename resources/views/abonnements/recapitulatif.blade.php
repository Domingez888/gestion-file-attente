<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>Abonnement annuel - FileFlow</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            padding: 30px;
        }
        .carte {
            max-width: 550px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px #ddd;
        }
        h1 { color: #2563eb; }
        .montant {
            font-size: 30px;
            font-weight: bold;
            color: #16a34a;
        }
    </style>
</head>
<body>
    <div class="carte">
        @if(session('success'))
    <div style="background:#d4edda;
                color:#155724;
                padding:15px;
                margin-bottom:20px;
                border-radius:6px;">
        {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div style="background:#f8d7da;
                color:#721c24;
                padding:15px;
                margin-bottom:20px;
                border-radius:6px;">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
        <h1>FileFlow</h1>
        <h2>Récapitulatif de l'abonnement</h2>

        <p><strong>Établissement :</strong>
            {{ $inscription['nom_etablissement'] }}
        </p>

        <p><strong>Administrateur :</strong>
            {{ $inscription['nom'] }}
        </p>

        <p><strong>Durée :</strong>
            {{ $duree }} mois
        </p>

        <p>Montant à payer :</p>
        <div class="montant">
            {{ number_format($montant, 0, ',', ' ') }}
            FCFA
        </div>

        <p>Le compte sera créé après
           confirmation du paiement.</p>
    
    <h3>Choisissez votre moyen de paiement</h3>

<form method="POST" action="{{ route('abonnement.payer') }}">
    @csrf
    <label>
        <input type="radio" name="network" value="MTN" required>
        MTN Mobile Money
    </label>

    <br><br>

    <label>
        <input type="radio" name="network" value="ORANGE">
        Orange Money
    </label>

    <br><br>

    <label>Numéro de téléphone</label>
    <input type="tel"
           name="phone_number"
           placeholder="6XXXXXXXX"
           pattern="6[0-9]{8}"
           maxlength="9"
           required>

    <br><br>

    <button type="submit">
        Payer 100 000 FCFA
    </button>
</form>
</div>

</body>
</html>