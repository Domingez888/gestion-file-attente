<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Abonnements - FileFlow</title>

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

    <h1>Gestion des abonnements</h1>

    <p>Abonnements annuels des établissements FileFlow</p>

    <table>
        <thead>
            <tr>
                <th>Établissement</th>
                <th>Montant</th>
                <th>Paiement</th>
                <th>Statut</th>
                <th>Date de début</th>
                <th>Date de fin</th>
            </tr>
        </thead>

        <tbody>
            @forelse($abonnements as $abonnement)
                <tr>
                    <td>
                        {{ $abonnement->etablissement?->nom ?? 'Non renseigné' }}
                    </td>

                    <td>
                        {{ number_format($abonnement->montant, 0, ',', ' ') }}
                        {{ $abonnement->devise }}
                    </td>
                    <td>
    @php $dernierPaiement = $abonnement->paiements->first(); @endphp
    @if($dernierPaiement)
        {{ number_format($dernierPaiement->montant, 0, ',', ' ') }} {{ $dernierPaiement->devise }}
        <br>
        <strong>{{ $dernierPaiement->statut }}</strong>
        <br>
        <small>Réf: {{ $dernierPaiement->reference }}</small>
    @else
        —
    @endif
</td>

                    <td>
                        {{ $abonnement->statut ?? '—' }}
    <form action="{{ route('super-admin.abonnements.activer', $abonnement) }}"
          method="POST" style="display:inline;">
        @csrf
        <button type="submit">Activer</button>
    </form>
    <form action="{{ route('super-admin.abonnements.renouveler', $abonnement) }}"
          method="POST" style="display:inline;">
        @csrf
        <button type="submit">Renouveler</button>
    </form>
    <form action="{{ route('super-admin.abonnements.supprimer', $abonnement) }}"
          method="POST" style="display:inline;"
          onsubmit="return confirm('Supprimer cet abonnement ?');">
        @csrf
        @method('DELETE')
        <button type="submit" style="color:#dc3545;">Supprimer</button>
    </form>
</td>


                    <td>
                        {{ $abonnement->date_debut?->format('d/m/Y') ?? '—' }}
                    </td>

                    <td>
                        {{ $abonnement->date_fin?->format('d/m/Y') ?? '—' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">
                        Aucun abonnement enregistré.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>