<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthenticateUsersController;
use App\Http\Controllers\Auth\LogoutUsersController;
use App\Http\Controllers\Auth\RedirectToIvaoLoginController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/landing.php';

Route::get('/auth/redirect', RedirectToIvaoLoginController::class)->name('auth.redirect');

Route::get('/auth/callback', AuthenticateUsersController::class)
    ->name('auth.callback');

Route::middleware(['auth'])->group(function (): void {
    Route::post('/auth/logout', LogoutUsersController::class)
        ->name('auth.logout');
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    require __DIR__.'/ivao.php';
});

Route::prefix('dashboard')->middleware(['auth'])->group(function (): void {
    require __DIR__.'/settings.php';
    require __DIR__.'/dashboard.php';
});
