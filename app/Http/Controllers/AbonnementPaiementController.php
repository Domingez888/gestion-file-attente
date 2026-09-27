<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InscriptionEnAttente;
use App\Models\Abonnement;
use App\Models\Paiement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\FlutterwaveService;
use Illuminate\Support\Facades\Http;


class AbonnementPaiementController extends Controller
{
    public function tarif()
{
    return response()->json([
        'montant' => config('services.fileflow.abonnement_annuel'),
        'devise' => config('services.fileflow.devise'),
        'duree_mois' => config('services.fileflow.duree_abonnement'),
    ]);
}
public function recapitulatif(Request $request)
{
    $inscription = $request->session()
        ->get('inscription_etablissement');

    if (!$inscription) {
        return redirect()
            ->route('inscription-etablissement.creer');
    }

    $montant = config(
        'services.fileflow.abonnement_annuel'
    );

    $duree = config(
        'services.fileflow.duree_abonnement'
    );

    return view(
        'abonnements.recapitulatif',
        compact('inscription', 'montant', 'duree')
    );
}
public function payer(Request $request)
{
    $inscription = $request->session()
        ->get('inscription_etablissement');

    if (!$inscription) {
        return redirect()
            ->route('inscription-etablissement.creer');
    }

    $donnees = $request->validate([
        'network' => 'required|in:MTN,ORANGE',
        'phone_number' => [
            'required',
            'regex:/^6[0-9]{8}$/'
        ],
    ]);
    $enAttente = InscriptionEnAttente::find(
    $request->session()->get('inscription_en_attente_id')
);

if (!$enAttente ||
    $enAttente->statut !== 'en_attente' ||
    $enAttente->expire_le->isPast()) {

    $enAttente = InscriptionEnAttente::create([
        'nom_etablissement' => $inscription['nom_etablissement'],
        'adresse' => $inscription['adresse'],
        'telephone_etablissement' => $inscription['telephone_etablissement'],
        'email_etablissement' => $inscription['email_etablissement'],

        'nom_administrateur' => $inscription['nom'],
        'email_administrateur' => $inscription['email'],
        'telephone_administrateur' => $inscription['telephone'],
        'mot_de_passe_hash' => $inscription['motDePasse'],

        'montant' => config('services.fileflow.abonnement_annuel'),
        'devise' => config('services.fileflow.devise'),
        'reference_paiement' => 'ABO-' . Str::uuid(),
        'reseau' => $donnees['network'],
        'telephone_paiement' => $donnees['phone_number'],
        'statut' => 'en_attente',
        'expire_le' => now()->addDay(),
    ]);

    $request->session()->put(
        'inscription_en_attente_id',
        $enAttente->id
    );
} else {
    $enAttente->update([
        'reseau' => $donnees['network'],
        'telephone_paiement' => $donnees['phone_number'],
    ]);
}
try {
    if ($enAttente->charge_id) {
        return back()->withErrors([
            'paiement' => 'Une transaction existe déjà. Vérifiez son statut avant de réessayer.',
        ]);
    }

    $transaction = $this->preparerTransaction($enAttente);

    $moyenPaiement = $this->preparerMoyenPaiement($enAttente);

    $charge = $this->creerChargeSandbox(
        $enAttente,
        $transaction,
        $moyenPaiement
    );

    $enAttente->update([
        'charge_id' => $charge['id'],
    ]);
}
 catch (\Throwable $e) {
    \Illuminate\Support\Facades\Log::warning(
        'Préparation Flutterwave échouée',
        [
            'inscription_id' => $enAttente->id,
            'type_erreur' => get_class($e),
            'message' => $e->getMessage(),
            'http_status' => $e instanceof \Illuminate\Http\Client\RequestException
    ? $e->response->status()
    : null,
        ]
    );

    return back()->withErrors([
        'paiement' => 'Impossible de préparer le paiement.',
    ]);
}

    return back()->with(
        'success',
        'Transaction de test cree. paiement en attente de verification. '
    );
}
public function formulaireRenouvellement()
{
    $abonnement = Abonnement::where('etablissement_id', Auth::user()->etablissement_id)
        ->latest()
        ->first();

    if (!$abonnement) {
        return back()->withErrors([
            'abonnement' => 'Aucun abonnement trouvé pour votre établissement.',
        ]);
    }

    $montant = config('services.fileflow.abonnement_annuel');
    $devise = config('services.fileflow.devise');

    return view('abonnements.renouveler', compact('abonnement', 'montant', 'devise'));
}

public function payerRenouvellement(Request $request)
{
    $donnees = $request->validate([
        'network' => 'required|in:MTN,ORANGE',
        'phone_number' => ['required', 'regex:/^6[0-9]{8}$/'],
    ]);

    $abonnement = Abonnement::where('etablissement_id', Auth::user()->etablissement_id)
        ->latest()
        ->firstOrFail();

    $montant = (int) config('services.fileflow.abonnement_annuel');
    $devise = config('services.fileflow.devise');
    $reference = 'RENOU-' . Str::uuid();

    $flutterwave = app(FlutterwaveService::class);

    $customer = $flutterwave->createCustomer(Auth::user()->email);
    $paymentMethod = $flutterwave->createMobileMoneyPaymentMethod(
        '237',
        $donnees['network'],
        $donnees['phone_number']
    );

    $charge = $flutterwave->createCharge(
        $customer['data']['id'],
        $paymentMethod['data']['id'],
        $montant,
        $devise,
        route('admin.tableau')
    );

    Paiement::create([
        'montant' => $montant,
        'date' => now(),
        'statut' => 'en_attente',
        'methode' => $donnees['network'],
        'reference' => $reference,
        'charge_id' => $charge['data']['id'] ?? null,
        'client_id' => Auth::id(),
        'abonnement_id' => $abonnement->id,
        'devise' => $devise,
    ]);

    $redirectUrl = $charge['data']['next_action']['redirect_url']['url'] ?? null;

    if ($redirectUrl) {
        return redirect()->away($redirectUrl);
    }

    return back()->with('success', 'Transaction créée. Paiement en attente de vérification.');
}

private function preparerTransaction(
    InscriptionEnAttente $enAttente
): array {
    $montant = (int) config(
        'services.fileflow.abonnement_annuel'
    );

    $devise = config('services.fileflow.devise');

    if (
        $enAttente->statut !== 'en_attente' ||
        $enAttente->expire_le->isPast() ||
        (int) $enAttente->montant !== $montant ||
        $enAttente->devise !== $devise
    ) {
        throw new \RuntimeException(
            'Inscription invalide ou expirée.'
        );
    }

    return [
        'amount' => $montant,
        'currency' => $devise,
        'reference' => $enAttente->reference_paiement,
        'email' => $enAttente->email_administrateur,
        'network' => $enAttente->reseau,
        'phone_number' => $enAttente->telephone_paiement,
    ];
}
private function preparerMoyenPaiement(
    InscriptionEnAttente $inscription
): array {
    $hote = parse_url(
        config('services.flutterwave.base_url'),
        PHP_URL_HOST
    );

    if ($hote !== 'developersandbox-api.flutterwave.com') {
        throw new \RuntimeException(
            'Environnement de test requis.'
        );
    }

    $flutterwave = app(FlutterwaveService::class);

  $reponse = Http::withToken(
    $flutterwave->getAccessToken()
)
    ->acceptJson()
    ->timeout(30)
    ->withHeaders([
        'X-Trace-Id' => (string) Str::uuid(),
    ])
    ->get(
        rtrim(config('services.flutterwave.base_url'), '/')
            . '/customers',
        ['page' => 1, 'size' => 50]
    );

$reponse->throw();

$clientExistant = collect($reponse->json('data') ?? [])
    ->first(fn ($client) =>
        strcasecmp(
            $client['email'] ?? '',
            $inscription->email_administrateur
        ) === 0
    );

$clientId = $clientExistant['id'] ?? null;

if (!$clientId) {
    $nouveauClient = $flutterwave->createCustomer(
        $inscription->email_administrateur
    );

    $clientId = $nouveauClient['data']['id'] ?? null;

    if (!$clientId) {
        throw new \RuntimeException(
            'Impossible de créer le client.'
        );
    }
}


\Illuminate\Support\Facades\Log::info(
    'Flutterwave : client retrouvé, création du moyen de paiement'
);
    $methode = $flutterwave
        ->createMobileMoneyPaymentMethod(
            '237',
            $inscription->reseau,
            $inscription->telephone_paiement
        );
        \Illuminate\Support\Facades\Log::info(
    'Flutterwave : réponse du moyen de paiement reçue'
);

    $methodeId = data_get($methode, 'data.id');

    if (!$methodeId) {
        throw new \RuntimeException(
            'Création du moyen de paiement impossible.'
        );
    }

    return [
        'customer_id' => $clientId,
        'payment_method_id' => $methodeId,
    ];
}
private function creerChargeSandbox(
    InscriptionEnAttente $inscription,
    array $transaction,
    array $moyenPaiement
): array {
    $baseUrl = rtrim(
        config('services.flutterwave.base_url'),
        '/'
    );

    if (parse_url($baseUrl, PHP_URL_HOST)
        !== 'developersandbox-api.flutterwave.com') {
        throw new \RuntimeException(
            'Le mode test est obligatoire.'
        );
    }

    $flutterwave = app(FlutterwaveService::class);

    $response = Http::withToken(
        $flutterwave->getAccessToken()
    )
        ->acceptJson()
        ->withHeaders([
            'X-Trace-Id' => (string) Str::uuid(),
            'X-Idempotency-Key' =>
                'fileflow-' . $inscription->reference_paiement,
        ])
        ->timeout(30)
        ->post($baseUrl . '/charges', [
            'amount' => $transaction['amount'],
            'currency' => $transaction['currency'],
            'reference' => $transaction['reference'],
            'customer_id' => $moyenPaiement['customer_id'],
            'payment_method_id' =>
                $moyenPaiement['payment_method_id'],
        ]);

    if (!$response->successful()
        || $response->json('status') !== 'success'
        || !$response->json('data.id')) {
        throw new \RuntimeException(
            'Impossible de créer la transaction.'
        );
    }

    return $response->json('data');
}
}