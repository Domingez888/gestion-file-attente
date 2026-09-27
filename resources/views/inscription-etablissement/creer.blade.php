<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription établissement - FileFlow</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }
        .conteneur {
            max-width: 550px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
        }
        h1 { color: #1e3a5f; }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            margin-top: 25px;
            width: 100%;
            padding: 14px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="conteneur">
          @if(session('success'))
    <div style="background:#d4edda; color:#155724;
                padding:15px; margin-bottom:20px;
                border-radius:6px;">
        {{ session('success') }}
    </div>
@endif
        
@if($errors->any())
    <div style="background:#f8d7da; color:#721c24;
                padding:15px; margin-bottom:20px;
                border-radius:6px;">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
        <h1>Inscription à FileFlow</h1>
        <p>Créez votre établissement et préparez
           votre abonnement annuel.</p>
           <form method="POST" action="{{ route('inscription-etablissement.enregistrer') }}">

        @csrf
            <h2>Informations de l'établissement</h2>

            <label>Nom de l'établissement</label>
            <input type="text" name="nom_etablissement" required>

            <label>Adresse</label>
            <input type="text" name="adresse" required>

            <label>Téléphone</label>
            <input type="tel" name="telephone_etablissement">

            <label>Email</label>
            <input type="email" name="email_etablissement">

            <h2>Compte administrateur</h2>

            <label>Nom complet</label>
            <input type="text" name="nom" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Téléphone</label>
            <input type="tel" name="telephone">

            <label>Mot de passe</label>
            <input type="password" name="motDePasse" required>

            <label>Confirmer le mot de passe</label>
            <input type="password"
                   name="motDePasse_confirmation" required>

            <button type="submit">
                Continuer vers le paiement
            </button>
          

        </form>
    </div>
</body>
</html>