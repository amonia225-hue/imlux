<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BienController;
use App\Http\Controllers\ChantierController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\IlotController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

// ======== SITE PUBLIC ========
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/presentation', [PublicController::class, 'presentation'])->name('presentation');
Route::get('/biens', [PublicController::class, 'biens'])->name('biens');
Route::get('/programmes', [PublicController::class, 'programmes'])->name('programmes');
Route::get('/adhesion', [PublicController::class, 'adhesion'])->name('adhesion');
Route::get('/faq', [PublicController::class, 'faq'])->name('faq');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:6,1')->name('contact.send');
Route::get('/inscription', [RegisterController::class, 'create'])->name('register.create');
Route::get('/application', [DownloadController::class, 'page'])->name('application');
Route::get('/telecharger-app', [DownloadController::class, 'apk'])->name('app.download');
Route::post('/inscription', [RegisterController::class, 'store'])
    ->middleware('throttle:6,1')->name('register.store');

// ======== AUTH ========
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.attempt')
    ->middleware(['guest', 'throttle:6,1']); // 6 tentatives / minute
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ======== ADMIN ========
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Programmes
    Route::post('/programmes', [AdminController::class, 'storeProgramme'])->name('programmes.store');
    Route::post('/programmes/{programme}', [AdminController::class, 'updateProgramme'])->name('programmes.update');
    Route::post('/programmes/{programme}/delete', [AdminController::class, 'destroyProgramme'])->name('programmes.destroy');

    // Biens publiés sur le site
    Route::post('/biens', [BienController::class, 'store'])->name('biens.store');
    Route::post('/biens/{bien}', [BienController::class, 'update'])->name('biens.update');
    Route::post('/biens/{bien}/delete', [BienController::class, 'destroy'])->name('biens.destroy');

    // Îlots
    Route::post('/ilots', [IlotController::class, 'store'])->name('ilots.store');
    Route::post('/ilots/{ilot}/delete', [IlotController::class, 'destroy'])->name('ilots.destroy');

    // Lots
    Route::post('/lots', [AdminController::class, 'storeLot'])->name('lots.store');
    Route::post('/lots/{lot}', [AdminController::class, 'updateLot'])->name('lots.update');
    Route::post('/lots/{lot}/delete', [AdminController::class, 'destroyLot'])->name('lots.destroy');

    // Souscripteurs
    Route::post('/souscripteurs', [AdminController::class, 'storeSouscripteur'])->name('souscripteurs.store');
    Route::post('/souscripteurs/{souscripteur}', [AdminController::class, 'updateSouscripteur'])->name('souscripteurs.update');
    Route::post('/souscripteurs/{souscripteur}/delete', [AdminController::class, 'destroySouscripteur'])->name('souscripteurs.destroy');
    Route::post('/adhesions/{souscripteur}/valider', [AdminController::class, 'validateAdherent'])->name('adherents.validate');
    Route::post('/adhesions/{souscripteur}/refuser', [AdminController::class, 'rejectAdherent'])->name('adherents.reject');

    // Souscriptions
    Route::post('/souscriptions', [AdminController::class, 'storeSouscription'])->name('souscriptions.store');
    Route::post('/souscriptions/{souscription}', [AdminController::class, 'updateSouscription'])->name('souscriptions.update');
    Route::post('/souscriptions/{souscription}/delete', [AdminController::class, 'destroySouscription'])->name('souscriptions.destroy');

    // Versements
    Route::post('/versements', [AdminController::class, 'storeVersement'])->name('versements.store');
    Route::post('/versements/{versement}/recu', [AdminController::class, 'uploadRecu'])->name('versements.recu');
    Route::post('/versements/{versement}/delete', [AdminController::class, 'destroyVersement'])->name('versements.destroy');

    // Messages de contact (site public)
    Route::get('/messages', [ContactController::class, 'index'])->name('messages.index');
    Route::post('/messages/{message}/read', [ContactController::class, 'markRead'])->name('messages.read');
    Route::post('/messages/{message}/delete', [ContactController::class, 'destroy'])->name('messages.destroy');

    // Paramètres (en-têtes documents)
    Route::post('/parametres', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/parametres/logo/reset', [SettingsController::class, 'resetLogo'])->name('settings.logo.reset');

    // Avancement des travaux (chantier)
    Route::get('/chantiers', [ChantierController::class, 'index'])->name('chantiers.index');
    Route::post('/chantiers/etapes', [ChantierController::class, 'storeEtape'])->name('chantiers.etapes.store');
    Route::post('/chantiers/etapes/{etape}', [ChantierController::class, 'updateEtape'])->name('chantiers.etapes.update');
    Route::post('/chantiers/etapes/{etape}/delete', [ChantierController::class, 'destroyEtape'])->name('chantiers.etapes.destroy');
    Route::post('/chantiers/photos/{photo}/delete', [ChantierController::class, 'destroyPhoto'])->name('chantiers.photos.destroy');
});

// ======== PDF (admin connecté OU URL signée — protection IDOR) ========
Route::get('/pdf/facture/{versement}', [PdfController::class, 'facture'])->name('pdf.facture');
Route::get('/pdf/attestation/{souscription}', [PdfController::class, 'attestation'])->name('pdf.attestation');
Route::get('/pdf/frais/{souscripteur}', [PdfController::class, 'fraisRecu'])->name('pdf.frais');
// Reçu de paiement uploadé par l'admin (admin connecté OU URL signée)
Route::get('/pdf/versement/{versement}/recu', [PdfController::class, 'versementRecu'])->name('pdf.versement.recu');

// ======== MÉDIAS (photos de chantier servies sans dépendre du symlink storage) ========
Route::get('/media/{path}', function (string $path) {
    $base = realpath(storage_path('app/public'));
    $full = realpath(storage_path('app/public/' . $path));
    abort_unless($full && $base && str_starts_with($full, $base) && is_file($full), 404);

    return response()->file($full);
})->where('path', '.*')->name('media');

// ======== ESPACE CONSULTANT (PUBLIC, throttlé) ========
Route::middleware('throttle:20,1')->group(function () {
    Route::get('/suivi', [ConsultationController::class, 'index'])->name('consultation.index');
    Route::get('/suivi/recherche', [ConsultationController::class, 'show'])->name('consultation.show');
    Route::post('/suivi/recherche-telephone', [ConsultationController::class, 'showByPhone'])->name('consultation.phone');
    Route::get('/suivi/{uid}', [ConsultationController::class, 'showDirect'])->name('consultation.direct');
});
