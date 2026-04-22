<?php

declare(strict_types=1);

use App\Enums\PagesComponents;
use App\Http\Controllers\Dashboard\EventsController;
use Illuminate\Support\Facades\Route;

Route::get('events', [EventsController::class, 'index'])->name('dashboard.events.index');
Route::inertia('events/create', PagesComponents::EVENTS_CREATE)->name('dashboard.events.create');
Route::post('events', [EventsController::class, 'store'])->name('dashboard.events.store');
Route::get('events/{event:slug}', [EventsController::class, 'show'])->name('dashboard.events.show');
Route::get('events/{event:slug}/edit', [EventsController::class, 'edit'])->name('dashboard.events.edit');
Route::put('events/{event:slug}', [EventsController::class, 'update'])->name('dashboard.events.update');
Route::delete('events/{event:slug}', [EventsController::class, 'destroy'])->name('dashboard.events.destroy');
