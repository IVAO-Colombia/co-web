<?php

declare(strict_types=1);

use App\Http\Controllers\Dashboard\EventsController;
use App\Http\Controllers\Dashboard\ImageGeneratorController;
use App\Http\Controllers\Dashboard\ReservationsController;
use App\Http\Controllers\Landing\AtcSlotReservationsController;
use App\Http\Controllers\Landing\PilotSlotReservationsController;
use Illuminate\Support\Facades\Route;

Route::get('reservations', [ReservationsController::class, 'index'])->name('dashboard.reservations.index');

Route::get('image-generator', [ImageGeneratorController::class, 'index'])->name('dashboard.image-generator');

Route::post('events/{event:slug}/reservations/atc-slot/{slot}', [AtcSlotReservationsController::class, 'store'])->name('dashboard.events.atc-slot.store');
Route::patch('events/{event:slug}/reservations/atc-slot/{slot}', [AtcSlotReservationsController::class, 'update'])->name('dashboard.events.atc-slot.update');
Route::delete('events/{event:slug}/reservations/atc-slot/{slot}', [AtcSlotReservationsController::class, 'destroy'])->name('dashboard.events.atc-slot.destroy');

Route::post('/events/{event:slug}/reservations/pilot-slot/{slot}', [PilotSlotReservationsController::class, 'store'])->name('dashboard.events.pilot-slot.store');
Route::patch('events/{event:slug}/reservations/pilot-slot/{slot}', [PilotSlotReservationsController::class, 'update'])->name('dashboard.events.pilot-slot.update');
Route::delete('events/{event:slug}/reservations/pilot-slot/{slot}', [PilotSlotReservationsController::class, 'destroy'])->name('dashboard.events.pilot-slot.destroy');

Route::resource('events', EventsController::class)
    ->scoped(['event' => 'slug'])
    ->names([
        'index' => 'dashboard.events.index',
        'create' => 'dashboard.events.create',
        'store' => 'dashboard.events.store',
        'show' => 'dashboard.events.show',
        'edit' => 'dashboard.events.edit',
        'update' => 'dashboard.events.update',
        'destroy' => 'dashboard.events.destroy',
    ]);
