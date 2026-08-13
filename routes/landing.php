<?php

declare(strict_types=1);

use App\Enums\PagesComponents;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\Landing\EventsController;
use Illuminate\Support\Facades\Route;

if (time() > strtotime('August 16 2026 12:00:00')) {
    Route::get('/', HomePageController::class)->name('home');
} else {
    Route::view('/', 'countdown')->name('home');
}

Route::get('/about-us', fn () => inertia(PagesComponents::LANDING_ABOUT_US->value))->name('home.about');
Route::get('/training', fn () => inertia(PagesComponents::LANDING_TRAINING->value))->name('home.training');
Route::get('/events', [EventsController::class, 'index'])->name('home.events');
Route::get('/events/{event:slug}', [EventsController::class, 'show'])->name('home.events.show');
