<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeController extends Controller
{
    // Affiche la liste des files gérées par l'employé (ou toutes, pour simplifier)
    public function tableauDeBord()
    {
        $files = File::with(['tickets', 'service'])
    ->whereHas('service', function ($query) {
    $query->where('employe_id', Auth::id());
})->get();
        return view('employe.tableau', compact('files'));
    }

    // Appelle le prochain ticket en attente dans une file donnée
    public function appelerSuivant(File $file)
    {
        if (!$file->service || $file->service->employe_id !== Auth::id()) {
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

    // Marque un ticket comme "absent" (client ne s'est pas présenté)
    public function marquerAbsent(Ticket $ticket)
    {
        if (!$ticket->file || !$ticket->file->service || $ticket->file->service->employe_id !== Auth::id()) {
    abort(403, 'Ce ticket ne vous appartient pas.');
}
if ($ticket->statut !== 'appele') {
    return back()->with('error', 'Ce ticket doit être appelé avant d’être marqué comme absent.');
}
        $ticket->update(['statut' => 'absent']);
        return back()->with('info', 'Ticket ' . $ticket->numero . ' marqué absent.');
    }

    // Marque un ticket comme "traité" (client pris en charge avec succès)
    public function marquerTraite(Ticket $ticket)
    {
        if (!$ticket->file || !$ticket->file->service || $ticket->file->service->employe_id !== Auth::id()) {
    abort(403, 'Ce ticket ne vous appartient pas.');
}
if ($ticket->statut !== 'appele') {
    return back()->with('error', 'Ce ticket doit être appelé avant d’être marqué comme traité.');
}
        $ticket->update(['statut' => 'traite']);
        return back()->with('succes', 'Ticket ' . $ticket->numero . ' traité.');
    }
}