<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DeviceTokenController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SouscriptionController;
use Illuminate\Support\Facades\Route;

// ======== PUBLIC ========
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:10,1')
    ->name('api.login');

// ======== AUTHENTIFIÉ (souscripteur via jeton Sanctum) ========
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('api.me');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::get('/souscriptions', [SouscriptionController::class, 'index'])->name('api.souscriptions.index');
    Route::get('/souscriptions/{souscription}', [SouscriptionController::class, 'show'])->name('api.souscriptions.show');

    Route::post('/device-tokens', [DeviceTokenController::class, 'store'])->name('api.device-tokens.store');
    Route::delete('/device-tokens', [DeviceTokenController::class, 'destroy'])->name('api.device-tokens.destroy');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('api.notifications.index');
    Route::post('/notifications/read', [NotificationController::class, 'markRead'])->name('api.notifications.read');
});
