<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Etablissement;
use App\Models\Abonnement;
use App\Models\InscriptionEnAttente;

class SuperAdminController extends Controller
{
    public function tableauDeBord()
{
    $totalEtablissements = Etablissement::count();

    $totalAdministrateurs = User::where('role', 'admin')->count();

    $abonnementsActifs = Abonnement::where('statut', 'actif')->count();

    $inscriptionsEnAttente = InscriptionEnAttente::where('statut', 'en_attente')->count();

    return view('super-admin.tableau-de-bord', compact(
        'totalEtablissements',
        'totalAdministrateurs',
        'abonnementsActifs',
        'inscriptionsEnAttente'
    ));
}
    public function abonnements()
{
   $abonnements = Abonnement::with(['etablissement', 'paiements' => function ($q) {
        $q->latest();
    }])
    ->orderBy('created_at', 'desc')
    ->get();
    

    return view('super-admin.abonnements', compact('abonnements'));
}
public function etablissements()
{
    $etablissements = Etablissement::withCount('employes')
        ->orderBy('nom')
        ->get();

    return view(
        'super-admin.etablissements',
        compact('etablissements')
    );
}
public function inscriptionsEnAttente()
{
    $inscriptions = InscriptionEnAttente::where('statut', 'en_attente')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('super-admin.inscriptions', compact('inscriptions'));
}

public function validerInscription(InscriptionEnAttente $inscription)
{
    $etablissement = Etablissement::create([
        'nom' => $inscription->nom_etablissement,
        'adresse' => $inscription->adresse,
        'telephone' => $inscription->telephone_etablissement,
        'email' => $inscription->email_etablissement,
    ]);

    User::create([
        'nom' => $inscription->nom_administrateur,
        'email' => $inscription->email_administrateur,
        'telephone' => $inscription->telephone_administrateur,
        'motDePasse' => $inscription->mot_de_passe_hash,
        'role' => 'admin',
        'etablissement_id' => $etablissement->id,
    ]);

    Abonnement::create([
        'etablissement_id' => $etablissement->id,
        'montant' => $inscription->montant,
        'devise' => $inscription->devise,
        'reference_paiement' => $inscription->reference_paiement,
        'statut' => 'actif',
        'date_debut' => now(),
        'date_fin' => now()->addYear(),
    ]);

    $inscription->update(['statut' => 'validee']);

    return back()->with('success', 'Établissement créé avec succès.');
}
public function activerAbonnement(Abonnement $abonnement)
{
    $abonnement->update([
        'statut' => 'actif',
        'date_debut' => $abonnement->date_debut ?? now(),
        'date_fin' => now()->addYear(),
    ]);

    return back()->with('success', 'Abonnement activé.');
}

public function renouvelerAbonnement(Abonnement $abonnement)
{
    $abonnement->update([
        'statut' => 'actif',
        'date_debut' => now(),
        'date_fin' => now()->addYear(),
    ]);

    $abonnement->paiements()
        ->where('statut', 'en_attente')
        ->latest()
        ->first()
        ?->update(['statut' => 'confirmé']);

    return back()->with('success', 'Abonnement renouvelé pour un an.');
}
public function supprimerAbonnement(Abonnement $abonnement)
{
    $abonnement->delete();

    return back()->with('success', 'Abonnement supprimé.');
}

}