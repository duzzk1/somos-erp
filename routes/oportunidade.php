<?php 

use App\Http\Controllers\OportunidadesController;

Route::prefix('oportunidades')->group(function () {
    Route::get('/', [OportunidadesController::class, 'index'])
        ->name('oportunidades.index');

    Route::put('/{oportunidades}', [OportunidadesController::class, 'update'])
        ->name('oportunidades.update');

    Route::post('/create', [OportunidadesController::class, 'create'])
        ->name('oportunidades.create');
});