<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etablissement;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
public function tableauDeBord()
{
  $nombreEtablissements = Etablissement::count();
  $nombreEmployes = User::where('role','employe')->count();
  return view('admin.tableau',
  compact(
    'nombreEtablissements',
    'nombreEmployes'
  ));  
}
public function etablissements()
{
    $etablissements = Etablissement::withCount('employes')->get();

    return view('admin.etablissements.index', compact('etablissements'));
}
public function creerEtablissement()
{
    return view('admin.etablissements.creer');
}
public function enregistrerEtablissement(Request $request)
{
    $donnees = $request->validate([
        'nom' => 'required|string|max:255',
        'adresse' => 'required|string|max:255',
        'telephone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
    ]);

    Etablissement::create($donnees);

    return redirect()
        ->route('admin.etablissements.index')
        ->with('success', 'Établissement ajouté avec succès.');
}
public function employes()
{
    $employes = User::where('role', 'employe')
        ->with('etablissement')
        ->get();

    return view('admin.employes.index', compact('employes'));
}
public function creerEmploye()
{
    $etablissements = Etablissement::orderBy('nom')->get();

    return view('admin.employes.creer', compact('etablissements'));
}
public function enregistrerEmploye(Request $request)
{
    $donnees = $request->validate([
        'nom' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'telephone' => 'nullable|string|max:20',
        'motDePasse' => 'required|string|min:8',
        'etablissement_id' => 'required|exists:etablissements,id',
    ]);

    User::create([
        'nom' => $donnees['nom'],
        'email' => $donnees['email'],
        'telephone' => $donnees['telephone'] ?? null,
        'motDePasse' => Hash::make($donnees['motDePasse']),
        'role' => 'employe',
        'etablissement_id' => $donnees['etablissement_id'],
    ]);

    return redirect()
        ->route('admin.employes.index')
        ->with('success', 'Employé ajouté avec succès.');
}
public function modifierEmploye(User $employe)
{
    abort_unless($employe->role === 'employe', 404);

    $etablissements = Etablissement::orderBy('nom')->get();

    return view('admin.employes.modifier', compact(
        'employe',
        'etablissements'
    ));
}
public function mettreAJourEmploye(Request $request, User $employe)
{
    abort_unless($employe->role === 'employe', 404);

    $donnees = $request->validate([
        'nom' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $employe->id,
        'telephone' => 'nullable|string|max:20',
        'etablissement_id' => 'required|exists:etablissements,id',
    ]);

    $employe->update([
        'nom' => $donnees['nom'],
        'email' => $donnees['email'],
        'telephone' => $donnees['telephone'] ?? null,
        'etablissement_id' => $donnees['etablissement_id'],
    ]);

    return redirect()
        ->route('admin.employes.index')
        ->with('success', 'Employé modifié avec succès.');
}
public function modifierEtablissement(Etablissement $etablissement)
{
    return view(
        'admin.etablissements.modifier',
        compact('etablissement')
    );
}
public function mettreAJourEtablissement(Request $request, Etablissement $etablissement)
{
    $donnees = $request->validate([
        'nom' => 'required|string|max:255',
        'adresse' => 'required|string|max:255',
        'telephone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
    ]);

    $etablissement->update($donnees);

    return redirect()
        ->route('admin.etablissements.index')
        ->with('success', 'Établissement modifié avec succès.');
}
public function supprimerEtablissement(Etablissement $etablissement)
{
    $etablissement->delete();

    return redirect()
        ->route('admin.etablissements.index')
        ->with('success', 'Établissement supprimé avec succès.');
}
public function supprimerEmploye(User $employe)
{
    abort_unless($employe->role === 'employe', 404);

    $employe->delete();

    return redirect()
        ->route('admin.employes.index')
        ->with('success', 'Employé supprimé avec succès.');
}
}
