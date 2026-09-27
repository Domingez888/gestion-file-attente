<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscriptions en attente - FileFlow</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        h1 {
            color: #1e3a5f;
        }

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

        .retour {
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>

<body>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <a class="retour"
       href="{{ route('super-admin.tableau-de-bord') }}">
        ← Retour au tableau de bord
    </a>

    <h1>Inscriptions en attente</h1>

    <p>Demandes d'inscription d'établissements sur FileFlow</p>

    <table>
        <thead>
            <tr>
                <th>Établissement</th>
                <th>Administrateur</th>
                <th>Montant</th>
                <th>Paiement</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($inscriptions as $inscription)
                <tr>
                    <td>
                        {{ $inscription->nom_etablissement }}
                        <br>
                        <small>{{ $inscription->adresse }}</small>
                    </td>

                    <td>
                        {{ $inscription->nom_administrateur }}
                        <br>
                        <small>{{ $inscription->email_administrateur }}</small>
                    </td>

                    <td>
                        {{ number_format($inscription->montant, 0, ',', ' ') }}
                        {{ $inscription->devise }}
                    </td>

                    <td>
                        {{ $inscription->reference_paiement ?? '—' }}
                    </td>

                    <td>
                        <form action="{{ route('super-admin.inscriptions.valider', $inscription) }}"
                              method="POST" style="display:inline;"
                              onsubmit="return confirm('Valider cette inscription et créer l\'établissement ?');">
                            @csrf
                            <button type="submit">Valider</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">
                        Aucune inscription en attente.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>