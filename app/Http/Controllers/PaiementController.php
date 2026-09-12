<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiement;
use App\Models\Service;
use App\Services\FlutterwaveService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\File;
use App\Models\Ticket;
class PaiementController extends Controller
{
    public function creer(Service $service)
{
    return view('paiements.creer', compact('service'));
}
public function rediriger(Request $request)
{
    $donnees = $request->validate([
        'service_id' => 'required|exists:services,id',
    ]);

    return redirect()->route('paiements.creer', $donnees['service_id']);
}
public function payer(Request $request, Service $service)
{
    $donnees = $request->validate([
        'network' => 'required|in:MTN,ORANGE',
        'phone_number' => 'required|digits:9',
    ]);

    $flutterwave = new FlutterwaveService();

    $customer = $flutterwave->createCustomer(
        'client'.Auth::id().'.'.time().'@example.com'
    );
    $customerId = $customer['data']['id'];

    $paymentMethod = $flutterwave->createMobileMoneyPaymentMethod(
        '237',
        $donnees['network'],
        $donnees['phone_number']
    );
    $paymentMethodId = $paymentMethod['data']['id'];

    $charge = $flutterwave->createCharge(
        $customerId,
        $paymentMethodId,
        (int) $service->prix,
        'XAF',
        route ('paiements.confirmation',['service' => $service->id])
    );

  
Paiement::create([
    'montant' => $service->prix,
    'date' => now(),
    'statut' => 'en_attente',
    'methode' => $donnees['network'],
    'service_id' => $service->id,
    'reference' =>$charge['data']['reference']?? null,
    'charge_id' =>$charge['data']['id']?? null,
    'client_id'=> Auth:: id()
]);

$redirectUrl = $charge['data']['next_action']['redirect_url']['url'] ?? null;

if ($redirectUrl) {
    return redirect()->away($redirectUrl);
}

return view('paiements.instruction', [
    'instruction' => 'Aucune redirection reçue. Contactez le support.',
]);
}
   public function confirmation(Request $request)
{
    $serviceId = $request->query('service');
    $statut = 'inconnu';

    if ($serviceId) {
        $paiement = Paiement::where('client_id', Auth::id())
            ->where('service_id', $serviceId)
            ->latest()
            ->first();

        if ($paiement) {
            $statutFlutterwave = null;

            if ($paiement->statut === 'reussi') {
                $statutFlutterwave = 'succeeded';
            } elseif ($paiement->charge_id) {
                $flutterwave = new FlutterwaveService();
                $charge = $flutterwave->verifyCharge($paiement->charge_id);
                $statutFlutterwave = $charge['data']['status'] ?? null;
            }

            if ($statutFlutterwave === 'succeeded') {
                $paiement->update(['statut' => 'reussi']);
                $statut = 'success';

                $ticket = $this->creerTicketPourPaiement($paiement);

                return redirect()->route('tickets.show', $ticket->id)
                    ->with('success', 'Paiement réussi ! Voici votre ticket.');
            } elseif ($paiement->statut === 'echoue') {
                $statut = 'failed';
            }
        }
    }

    return view('paiements.confirmation', ['statut' => $statut]);
}

public function webhook(Request $request)
{
    $signature = $request->header('flutterwave-signature');
    $secretHash = config('services.flutterwave.webhook_secret');

    $payload = $request->getContent();
    $expectedSignature = base64_encode(hash_hmac('sha256', $payload, $secretHash, true));

    if (!$signature || !hash_equals($expectedSignature, $signature)) {
        Log::warning('Webhook Flutterwave: signature invalide');
        return response()->json(['message' => 'Signature invalide'], 401);
    }

    $data = $request->input('data', []);
    $reference = $data['reference'] ?? null;
    $statutFlutterwave = $data['status'] ?? null;

    if ($reference) {
        $nouveauStatut = match ($statutFlutterwave) {
            'successful', 'succeeded' => 'reussi',
            'failed', 'cancelled' => 'echoue',
            default => null,
        };

        if ($nouveauStatut) {
            $paiement = Paiement::where('reference', $reference)->first();

            if ($paiement) {
                $paiement->update(['statut' => $nouveauStatut]);

                if ($nouveauStatut === 'reussi') {
                    $this->creerTicketPourPaiement($paiement);
                }
            }
        }
    }

    return response()->json(['message' => 'OK'], 200);
}

private function creerTicketPourPaiement(Paiement $paiement): Ticket
{
    $service = Service::find($paiement->service_id);

    $file = File::firstOrCreate(
        ['service_id' => $service->id],
        ['nom' => 'File du service ' . $service->id, 'statut' => 'active']
    );

    return Ticket::firstOrCreate(
        [
            'client_id' => $paiement->client_id,
            'file_id' => $file->id,
            'statut' => 'en attente',
        ],
        [
            'numero' => 'T-' . strtoupper(uniqid()),
            'heureCreation' => now(),
        ]
    );
}
}