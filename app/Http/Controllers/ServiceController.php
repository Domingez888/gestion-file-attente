<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('id', Auth::user()->service_id)->get();
        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

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
            'prix' => $donnees['prix'],
        ]);

        return redirect()->route('services.index')->with('succes', 'Service ajouté avec succès.');
    }

    public function destroy(Service $service)
    {
    
        if ($service->id !== Auth::user()->service_id) {
            abort(403, 'Ce service ne vous appartient pas.');
        }

        $service->delete();
        return redirect()->route('services.index')->with('info', 'Service supprimé.');
    }
}