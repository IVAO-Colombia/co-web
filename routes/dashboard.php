<?php

declare(strict_types=1);

use App\Http\Controllers\Dashboard\EventsController;
use App\Http\Controllers\Dashboard\ImageGeneratorController;
use App\Http\Controllers\Dashboard\ReservationsController;
use App\Http\Controllers\Dashboard\Staff\TrainingRequestsController as StaffTrainingRequestsController;
use App\Http\Controllers\Dashboard\TrainingsController;
use App\Http\Controllers\Landing\AtcSlotReservationsController;
use App\Http\Controllers\Landing\PilotSlotReservationsController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Dashboard')->name('dashboard');

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

// User-facing training requests
Route::get('trainings', [TrainingsController::class, 'index'])->name('dashboard.trainings.index');
Route::post('trainings', [TrainingsController::class, 'store'])->name('dashboard.trainings.store');
Route::delete('trainings/{trainingRequest}', [TrainingsController::class, 'destroy'])->name('dashboard.trainings.destroy');

// Staff training requests
Route::middleware(['can:manage_training_requests'])->group(function (): void {
    Route::get('staff/training-requests', [StaffTrainingRequestsController::class, 'index'])->name('dashboard.staff.training-requests.index');
    Route::get('staff/training-requests/{trainingRequest}', [StaffTrainingRequestsController::class, 'show'])->name('dashboard.staff.training-requests.show');
    Route::patch('staff/training-requests/{trainingRequest}', [StaffTrainingRequestsController::class, 'update'])->name('dashboard.staff.training-requests.update');
    Route::delete('staff/training-requests/{trainingRequest}', [StaffTrainingRequestsController::class, 'destroy'])->name('dashboard.staff.training-requests.destroy');
});

Route::redirect('settings', '/settings/profile');

Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');

Route::inertia('settings/appearance', 'settings/Appearance')->name('appearance.edit');
