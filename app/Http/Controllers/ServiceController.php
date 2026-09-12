<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    // Liste des services de l'employé connecté
    public function index()
    {
        $services = Service::where('employe_id', Auth::id())->get();
        return view('services.index', compact('services'));
    }

    // Formulaire d'ajout d'un service
    public function create()
    {
        return view('services.create');
    }

    // Traite l'ajout d'un service
    public function store(Request $request)
    {
        $donnees = $request->validate([
            'nom' => 'required|string|max:255',
            'secteur' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'prix'=>'required|numeric|min:0',
        ]);

        Service::create([
            'nom' => $donnees['nom'],
            'secteur' => $donnees['secteur'],
            'adresse' => $donnees['adresse'],
            'employe_id' => Auth::id(),
            'prix' => $donnees['prix'],
        ]);

        return redirect()->route('services.index')->with('succes', 'Service ajouté avec succès.');
    }

       // Supprime un service (uniquement si l'employé connecté en est le propriétaire)
    public function destroy(Service $service)
    {
        // Sécurité : seul le propriétaire du service peut le supprimer
        if ($service->employe_id !== Auth::id()) {
            abort(403, 'Ce service ne vous appartient pas.');
        }

        $service->delete();
        return redirect()->route('services.index')->with('info', 'Service supprimé.');
    }
}