<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthenticateUsersController;
use App\Http\Controllers\Auth\LogoutUsersController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

Route::inertia('/', 'Welcome')->name('home');

Route::get('/auth/redirect', function () {
    return Inertia::location(Socialite::driver('ivao')
        ->scopes(['profile', 'email'])
        ->redirect());
})->name('auth.redirect');

Route::get('/auth/callback', AuthenticateUsersController::class)
    ->name('auth.callback');

Route::middleware(['auth'])->group(function () {
    Route::post('/auth/logout', LogoutUsersController::class)
        ->name('auth.logout');
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
