<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Service;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // Affiche le formulaire de prise de ticket
    public function create()
    {
        $services = Service::all();
        return view('tickets.create', compact('services'));
    }

    // Traite la prise de ticket
    public function store(Request $request)
    {
        $donnees = $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);

        // On récupère (ou on crée) une file pour ce service
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

        return redirect()->route('tickets.show', $ticket->id);
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

}