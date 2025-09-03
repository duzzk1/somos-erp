<?php

use App\Http\Controllers\IntegracoesController;

Route::prefix('integracoes')->group(function () {
    Route::get('/', [IntegracoesController::class, 'index'])
        ->name('integracoes.index');

    Route::post('/{api}', [IntegracoesController::class, 'search'])
        ->name('integracoes.search');
});