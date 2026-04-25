<?php

declare(strict_types=1);

use App\Enums\PagesComponents;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\Landing\AtcSlotReservationsController;
use App\Http\Controllers\Landing\EventsController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePageController::class)->name('home');
Route::get('/about-us', fn () => inertia(PagesComponents::LANDING_ABOUT_US->value))->name('home.about');
Route::get('/events', [EventsController::class, 'index'])->name('home.events');
Route::get('/events/{event:slug}', [EventsController::class, 'show'])->name('home.events.show');
Route::post('/events/{event:slug}/atc-slot/{slot}/reserve', [AtcSlotReservationsController::class, 'store'])
    ->middleware('auth')
    ->name('home.events.atc-slot.store');
Route::delete('/events/{event:slug}/atc-slot/{slot}/reserve', [AtcSlotReservationsController::class, 'destroy'])
    ->middleware('auth')
    ->name('home.events.atc-slot.destroy');
