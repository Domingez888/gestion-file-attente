<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;


class FileController extends Controller
{
      // Affiche la position et le temps estimé pour un ticket donné
    public function position(Ticket $ticket)
    {
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

       
    
        $file = $ticket->file;

        $ticketsEnAttente = $file->tickets()
            ->where('statut', 'en attente')
            ->orderBy('heureCreation')
            ->get();

        $position = $ticketsEnAttente->search(function ($t) use ($ticket) {
            return $t->id === $ticket->id;
        });

        $position = $position === false ? null : $position + 1;

        $tempsEstime = $position ? ($position - 1) * 5 : null;

        return view('tickets.position', compact('ticket', 'file', 'position', 'tempsEstime'));
    }
}