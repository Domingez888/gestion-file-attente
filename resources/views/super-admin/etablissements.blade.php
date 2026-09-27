<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Établissements - FileFlow</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        h1 { color: #1e3a5f; }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 25px;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #1e3a5f;
            color: white;
        }

        a {
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <a href="{{ route('super-admin.tableau-de-bord') }}">
        ← Retour au tableau de bord
    </a>

    <h1>Gestion des établissements</h1>
    <p>Liste des établissements inscrits sur FileFlow</p>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Utilisateurs</th>
            </tr>
        </thead>
        <tbody>
            @forelse($etablissements as $etablissement)
                <tr>
                    <td>{{ $etablissement->nom }}</td>
                    <td>{{ $etablissement->adresse ?? '—' }}</td>
                    <td>{{ $etablissement->telephone ?? '—' }}</td>
                    <td>{{ $etablissement->email ?? '—' }}</td>
                    <td>{{ $etablissement->employes_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">
                        Aucun établissement enregistré.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>