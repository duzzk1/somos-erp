<?php 

use App\Http\Controllers\StatusController;

Route::prefix('status')->group(function () {
    Route::get('/', [StatusController::class, 'status'])
        ->name('status.index');

    Route::put('/{status}', [StatusController::class, 'update'])
        ->name('status.update');

    Route::put('/create', [StatusController::class, 'create'])
        ->name('status.create');

     Route::get('/searchStatus', [StatusController::class, 'searchStatus'])->name('status.searchStatus');
});

