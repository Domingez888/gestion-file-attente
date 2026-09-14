<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaiementController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register']);

    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login']);
});

Route::post('/deconnexion', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
    Route::post('/paiements/webhook', [PaiementController::class, 'webhook'])
    ->name('paiements.webhook');
     Route::get('/tickets/suivre', function () {
    return view('tickets.suivre');
})->name('tickets.suivre');
Route::post('/tickets/suivre', [TicketController::class, 'suivre'])
    ->name('tickets.suivre.rechercher');

Route::middleware('auth')->group(function () {

    // ---- Routes CLIENT (tout utilisateur connecté) ----
    Route::get('/tickets/creer', [TicketController::class, 'create'])
    ->middleware('role:client')
    ->name('tickets.create');
    Route::get('/paiements/creer/{service}', [PaiementController::class, 'creer'])
    ->middleware('role:client')
    ->name('paiements.creer');
    Route::post('/paiements/payer/{service}', [PaiementController::class, 'payer'])
    ->middleware('role:client')
    ->name('paiements.payer');
    Route::get('/paiements/confirmation', [PaiementController::class, 'confirmation'])
    ->middleware('role:client')
    ->name('paiements.confirmation');
    Route::post('/paiements/rediriger', [PaiementController::class, 'rediriger'])
    ->middleware('role:client')
    ->name('paiements.rediriger');

Route::post('/tickets', [TicketController::class, 'store'])
    ->middleware('role:client')
    ->name('tickets.store');
    

    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/position', [FileController::class, 'position'])->name('tickets.position');
   
    // ---- Routes EMPLOYÉ (rôle "employe" requis) ----
    Route::middleware('role:employe')->group(function () {
        Route::get('/employe/tableau', [EmployeController::class, 'tableauDeBord'])->name('employe.tableau');
        Route::post('/employe/files/{file}/appeler-suivant', [EmployeController::class, 'appelerSuivant'])->name('employe.appelerSuivant');
        Route::post('/employe/tickets/{ticket}/absent', [EmployeController::class, 'marquerAbsent'])->name('employe.marquerAbsent');
        Route::post('/employe/tickets/{ticket}/traite', [EmployeController::class, 'marquerTraite'])->name('employe.marquerTraite');

        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('/services/creer', [ServiceController::class, 'create'])->name('services.create');
        Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    });
});
