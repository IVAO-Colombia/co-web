<?php

declare(strict_types=1);

use App\Enums\PagesComponents;
use App\Http\Controllers\EventsController;
use Illuminate\Support\Facades\Route;

Route::get('events', [EventsController::class, 'index'])->name('events.index');
Route::inertia('events/create', PagesComponents::EVENTS_CREATE)->name('events.create');
Route::post('events', [EventsController::class, 'store'])->name('events.store');
Route::get('events/{event:slug}', [EventsController::class, 'show'])->name('events.show');
Route::get('events/{event:slug}/edit', [EventsController::class, 'edit'])->name('events.edit');
Route::put('events/{event:slug}', [EventsController::class, 'update'])->name('events.update');
Route::delete('events/{event:slug}', [EventsController::class, 'destroy'])->name('events.destroy');
