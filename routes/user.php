<?php 

use App\Http\Controllers\UsersController;

Route::prefix('users')->group(function () {
     Route::get('/searchUsers', [UsersController::class, 'searchUsers'])->name('user.searchStatus');
});

