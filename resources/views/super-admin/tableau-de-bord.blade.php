<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Super Administrateur - FileFlow</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f6f9;
        margin: 0;
        padding: 30px;
    }

    h1 { color: #1e3a5f; }

    .dashboard {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 30px;
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        min-width: 200px;
        box-shadow: 0 3px 10px #00000012;
        border-top: 4px solid #ccc;
    }

    .card h2 {
        font-size: 32px;
    }

    .card-etablissements { border-top-color: #1e3a5f; }
    .card-etablissements h2 { color: #1e3a5f; }

    .card-admins { border-top-color: #2563eb; }
    .card-admins h2 { color: #2563eb; }

    .card-abonnements { border-top-color: #16a34a; }
    .card-abonnements h2 { color: #16a34a; }

    .card-inscriptions { border-top-color: #f97316; }
    .card-inscriptions h2 { color: #f97316; }
</style>
</head>
<body>
    <h1>Tableau de bord - FileFlow</h1>
    <p>Bienvenue dans l'espace du super administrateur.</p>

   <div class="dashboard">
    <div class="card card-etablissements">
        <h3>Établissements</h3>
        <h2>{{ $totalEtablissements }}</h2>
    </div>

    <div class="card card-admins">
        <h3>Administrateurs</h3>
        <h2>{{ $totalAdministrateurs }}</h2>
    </div>

    <div class="card card-abonnements">
        <h3>Abonnements actifs</h3>
        <h2>{{ $abonnementsActifs }}</h2>
    </div>

    <div class="card card-inscriptions">
        <h3>Inscriptions en attente</h3>
        <h2>{{ $inscriptionsEnAttente }}</h2>
    </div>
    </div>
    <div style="margin-top: 30px;">
    <a href="{{ route('super-admin.etablissements') }}">
        Gérer les établissements
    </a>

    <br><br>

    <a href="{{ route('super-admin.abonnements') }}">
        Gérer les abonnements
    </a>
        <br><br>

    <a href="{{ route('super-admin.inscriptions') }}">
        Gérer les inscriptions
        @if($inscriptionsEnAttente > 0)
            ({{ $inscriptionsEnAttente }})
        @endif
    </a>
</div>
<form action="{{ route('logout') }}" method="POST"
      style="margin-top: 25px;">
    @csrf

    <button type="submit"
            style="background:#dc3545;
                   color:white;
                   padding:12px 20px;
                   border:none;
                   border-radius:6px;
                   cursor:pointer;">
        Se déconnecter
    </button>
</form>
</body>
</html>