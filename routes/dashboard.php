<?php 

use App\Http\Controllers\DashboardController;

Route::get('/dashboard/calls', [DashboardController::class, 'calls'])
->name('dashboard.calls');

Route::post('/dashboard/calls/filter', [DashboardController::class, 'filter'])->name('dashboard.calls.filter');
