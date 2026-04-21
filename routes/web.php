<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthenticateUsersController;
use App\Http\Controllers\Auth\LogoutUsersController;
use App\Models\Event;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use App\Enums\PagesComponents;
use App\Enums\EventStatus;

Route::get('/', fn () => Inertia::render('Welcome', [
    'events' => Event::query()
        ->orderBy('starts_at')
        ->active()
        ->limit(6)
        ->get(),
]))->name('home');

Route::get('/events', fn () => Inertia::render(PagesComponents::EVENTS_LANDING->value, [
    'events' => Event::query()
        ->orderBy('starts_at')
        ->active()
        ->get(),
]))->name('events.landing');

Route::get('/auth/redirect', fn () => Inertia::location(Socialite::driver('ivao')
    ->scopes(['profile', 'email'])
    ->redirect()))->name('auth.redirect');

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
    require __DIR__.'/events.php';
});
