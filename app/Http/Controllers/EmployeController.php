<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeController extends Controller
{
   public function tableauDeBord()
{
    $employe = Auth::user();

    $files = File::with(['tickets', 'service'])
        ->where('service_id', $employe->service_id)
        ->get();

    return view('employe.tableau', compact('files'));
}

    public function appelerSuivant(File $file)
    {
        if (!$file->service || $file->service_id !== Auth::user()->service_id) {
    abort(403, 'Cette file ne vous appartient pas.');
}

        $prochainTicket = $file->tickets()
            ->where('statut', 'en attente')
            ->orderBy('heureCreation')
            ->first();
            $ticketDejaAppelee = $file->tickets()
    ->where('statut', 'appele')
    ->first();

if ($ticketDejaAppelee) {
    return back()->with('info', 'Un ticket est déjà appelé. Traitez-le ou marquez-le absent avant d’appeler le suivant.');
}

        if (!$prochainTicket) {
            return back()->with('info', 'Aucun ticket en attente dans cette file.');
        }

        $prochainTicket->update([
            'statut' => 'appele',
            'heureAppel' => now(),
        ]);

        return back()->with('succes', 'Ticket ' . $prochainTicket->numero . ' appelé.');
    }

    public function marquerAbsent(Ticket $ticket)
    {
        if (
    !$ticket->file ||
    !$ticket->file->service ||
    $ticket->file->service_id !== Auth::user()->service_id
) {
    abort(403, 'Ce ticket ne vous appartient pas.');
}
if ($ticket->statut !== 'appele') {
    return back()->with('error', 'Ce ticket doit être appelé avant d’être marqué comme absent.');
}
        $ticket->update(['statut' => 'absent']);
        return back()->with('info', 'Ticket ' . $ticket->numero . ' marqué absent.');
    }

    public function marquerTraite(Ticket $ticket)
    {
       if (
    !$ticket->file ||
    !$ticket->file->service ||
    $ticket->file->service_id !== Auth::user()->service_id
) {
    abort(403, 'Ce ticket ne vous appartient pas.');
}
if ($ticket->statut !== 'appele') {
    return back()->with('error', 'Ce ticket doit être appelé avant d’être marqué comme traité.');
}
        $ticket->update(['statut' => 'traite']);
        return back()->with('succes', 'Ticket ' . $ticket->numero . ' traité.');
    }
}