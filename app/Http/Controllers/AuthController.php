<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Affiche le formulaire d'inscription
    public function showRegister()
    {
        return view('auth.register');
    }

    // Traite l'inscription
    public function register(Request $request)
    {
        $donnees = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'motDePasse' => 'required|string|min:6|confirmed',
            'telephone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'nom' => $donnees['nom'],
            'email' => $donnees['email'],
            'motDePasse' => Hash::make($donnees['motDePasse']),
            'telephone' => $donnees['telephone'] ?? null,
            'role' => 'client',
        ]);

        Auth::login($user);

        return redirect('/');
    }

    // Affiche le formulaire de connexion
    public function showLogin()
    {
        return view('auth.login');
    }

    // Traite la connexion
    public function login(Request $request)
    {
        $donnees = $request->validate([
            'email' => 'required|string|email',
            'motDePasse' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $donnees['email'], 'password' => $donnees['motDePasse']])) {
            $request->session()->regenerate();
            if (Auth::user()->role === 'employe') {
    return redirect('/employe/tableau');
}

return redirect('/tickets/creer');
        }

        throw ValidationException::withMessages([
            'email' => 'Identifiants incorrects.',
        ]);
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}