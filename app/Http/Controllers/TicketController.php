<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Service;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Etablissement;

class TicketController extends Controller
{
   public function create()
{
    $etablissements = Etablissement::orderBy('nom')->get();

    $services = Service::whereNotNull('etablissement_id')
        ->orderBy('nom')
        ->get();

    return view('tickets.create', compact('etablissements', 'services'));
}
    public function store(Request $request)
    {
        $donnees = $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);

       $file = File::firstOrCreate(
    ['service_id' => $donnees['service_id']],
    [
        'nom' => 'File du service ' . $donnees['service_id'],
        'statut' => 'active'
    ]
);
$ticketActifExiste = Ticket::where('client_id', Auth::id())
    ->where('file_id', $file->id)
    ->whereIn('statut', ['en attente', 'appele'])
    ->exists();

if ($ticketActifExiste) {
    return redirect()->back()
        ->with('error', 'Vous avez déjà un ticket actif pour ce service.');
}
        $ticket = Ticket::create([
            'numero' => 'T-' . strtoupper(uniqid()),
            'statut' => 'en attente',
            'heureCreation' => now(),
            'file_id' => $file->id,
            'client_id' => Auth::id(),
        ]);

       return redirect()->route('tickets.show', ['ticket' => $ticket->getKey()]);
    }
    // Affiche le ticket créé (position, temps estimé...)
    public function show(Ticket $ticket)
    {
        // Sécurité : le ticket doit appartenir à l'utilisateur connecté,
        // SAUF s'il s'agit d'un employé (qui gère les files)
       if (Auth::user()->role === 'client' && $ticket->client_id !== Auth::id()) {
    abort(403, 'Ce ticket ne vous appartient pas.');
}

if (
    Auth::user()->role === 'employe' &&
    (
        !$ticket->file ||
        !$ticket->file->service ||
        $ticket->file->service->employe_id !== Auth::id()
    )
) {
    abort(403, 'Ce ticket ne fait pas partie de vos services.');
}

        return view('tickets.show', compact('ticket'));
    }
public function suivre(Request $request)
{
    $request->validate([
        'numero' => 'required|string',
    ]);

    $ticket = Ticket::where('numero', $request->numero)->first();

    if (!$ticket) {
        return back()->with('error', 'Aucun ticket trouvé avec ce numéro.');
    }

   return redirect ('/tickets/' . $ticket->id);
}
}