<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
       ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Le webhook Flutterwave est appelé par un serveur distant (pas un navigateur),
        // il ne peut pas fournir de token CSRF. Sa sécurité repose sur la signature HMAC
        // vérifiée dans PaiementController@webhook. On l'exempte donc du contrôle CSRF.
        $middleware->validateCsrfTokens(except: [
            'paiements/webhook',
        ]);
    })

->withExceptions(function (Exceptions $exceptions) {
    //
})->create();