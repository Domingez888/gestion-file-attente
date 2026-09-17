<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\AdminController;
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

    });

Route::middleware('role:admin')->group(function () {
    Route::get('/admin/tableau', [AdminController::class, 'tableauDeBord'])
        ->name('admin.tableau');
        Route::get('/admin/etablissements', [AdminController::class, 'etablissements'])
    ->name('admin.etablissements.index');
    Route::get('/admin/etablissements/creer', [AdminController::class, 'creerEtablissement'])
    ->name('admin.etablissements.creer');
    Route::post('/admin/etablissements', [AdminController::class, 'enregistrerEtablissement'])
    ->name('admin.etablissements.store');
    Route::get('/admin/employes', [AdminController::class, 'employes'])
    ->name('admin.employes.index');
    Route::get('/admin/employes/creer', [AdminController::class, 'creerEmploye'])
    ->name('admin.employes.creer');
    Route::post('/admin/employes', [AdminController::class, 'enregistrerEmploye'])
    ->name('admin.employes.store');
    Route::get('/admin/employes/{employe}/modifier', [AdminController::class, 'modifierEmploye'])
    ->name('admin.employes.modifier');
    Route::put('/admin/employes/{employe}', [AdminController::class, 'mettreAJourEmploye'])
    ->name('admin.employes.update');
    Route::get('/admin/etablissements/{etablissement}/modifier', [AdminController::class, 'modifierEtablissement'])
    ->name('admin.etablissements.modifier');
    Route::put('/admin/etablissements/{etablissement}', [AdminController::class, 'mettreAJourEtablissement'])
    ->name('admin.etablissements.update');
    Route::delete('/admin/etablissements/{etablissement}', [AdminController::class, 'supprimerEtablissement'])
    ->name('admin.etablissements.destroy');
    Route::delete('/admin/employes/{employe}', [AdminController::class, 'supprimerEmploye'])
    ->name('admin.employes.destroy');
    Route::get('/admin/services', [AdminController::class, 'services'])
    ->name('admin.services.index');

Route::get('/admin/services/creer', [AdminController::class, 'creerService'])
    ->name('admin.services.creer');

Route::post('/admin/services', [AdminController::class, 'enregistrerService'])
    ->name('admin.services.store');
});
});
