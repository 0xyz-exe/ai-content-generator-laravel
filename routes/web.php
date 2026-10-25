<?php

use App\Http\Controllers\CreditWalletController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\GeneratorController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/generator', [GeneratorController::class, 'index'])->name('generator.index');
    Route::get('/generator/create', [GeneratorController::class, 'create'])->name('generator.create');
    Route::get('/generator/template/{template:slug}', [GeneratorController::class, 'showTemplate'])->name('generator.template');
    Route::post('/generator/generate', [GeneratorController::class, 'generate'])->name('generator.generate');

    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::post('/documents/{document}/favorite', [DocumentController::class, 'toggleFavorite'])->name('documents.favorite');

    Route::get('/wallet', [CreditWalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/topup', [CreditWalletController::class, 'topup'])->name('wallet.topup');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
