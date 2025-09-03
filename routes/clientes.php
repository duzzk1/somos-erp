<?php 

use App\Http\Controllers\ClientesController;

Route::prefix('clientes')->group(function () {
    Route::get('/', [ClientesController::class, 'index'])
        ->name('clientes.index');

    Route::put('/{clientes}', [ClientesController::class, 'update'])
        ->name('clientes.update');

    Route::put('/create', [ClientesController::class, 'create'])
        ->name('clientes.create');

     Route::get('/searchClient', [ClientesController::class, 'searchClient'])->name('clientes.searchClient');
     Route::get('/searchModal', [ClientesController::class, 'searchModal'])->name('clientes.searchModal');
});

