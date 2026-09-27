<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InscriptionEtablissementController extends Controller
{
    public function afficherFormulaire()
    {
        return view('inscription-etablissement.creer');
    }
    public function enregistrer(Request $request)
{
    $donnees = $request->validate([
        'nom_etablissement' => 'required|string|max:255',
        'adresse' => 'required|string|max:255',
        'telephone_etablissement' => 'nullable|string|max:20',
        'email_etablissement' => 'nullable|email|max:255',

        'nom' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'telephone' => 'nullable|string|max:20',
        'motDePasse' => 'required|string|min:8|confirmed',
    ]);
    $inscription = [
    'nom_etablissement' => $donnees['nom_etablissement'],
    'adresse' => $donnees['adresse'],
    'telephone_etablissement' => $donnees['telephone_etablissement'] ?? null,
    'email_etablissement' => $donnees['email_etablissement'] ?? null,
    'nom' => $donnees['nom'],
    'email' => $donnees['email'],
    'telephone' => $donnees['telephone'] ?? null,
    'motDePasse' => Hash::make($donnees['motDePasse']),
];

$request->session()->put(
    'inscription_etablissement',
    $inscription
);


    // Le compte et l'établissement ne seront créés
    // qu'après confirmation du paiement.
    // Nous ajouterons le traitement du paiement ensuite.

   return redirect()
    ->route('abonnement.recapitulatif');
}
}