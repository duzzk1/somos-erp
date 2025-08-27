<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OportunidadesController;
use App\Http\Controllers\ProfileController;
use App\Jobs\ImportarOportunidadesJob;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/oportunidades', [OportunidadesController::class, 'index'])
    ->name('oportunidades.index')
    ->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::put('/oportunidades/{oportunidades}', [OportunidadesController::class, 'update'])->name('oportunidades.update');

require __DIR__.'/auth.php';
