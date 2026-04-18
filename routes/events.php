<?php

declare(strict_types=1);

use App\Enums\PagesComponents;
use App\Http\Controllers\EventsController;
use Illuminate\Support\Facades\Route;

Route::get('events', [EventsController::class, 'index'])->name('events.index');
Route::inertia('events/create', PagesComponents::EVENTS_CREATE)->name('events.create');
// Route::get('events/create', [EventsController::class, 'create'])->name('events.create');
Route::post('events', [EventsController::class, 'store'])->name('events.store');
Route::get('events/{event:slug}', [EventsController::class, 'show'])->name('events.show');
