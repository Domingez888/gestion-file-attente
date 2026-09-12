<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Vérifie que l'utilisateur connecté possède l'un des rôles autorisés.
     * Utilisation : ->middleware('role:employe') ou ->middleware('role:employe,admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Cas 1 : personne de connecté (sécurité supplémentaire)
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Cas 2 : le rôle de l'utilisateur ne fait pas partie des rôles autorisés
        if (! in_array($request->user()->role, $roles)) {
            abort(403, 'Accès refusé : vous n\'avez pas le rôle requis.');
        }

        // Cas 3 : tout est OK, on laisse passer la requête
        return $next($request);
    }
}
