<?php

declare(strict_types=1);

use App\Http\Controllers\EventsController;
use Illuminate\Support\Facades\Route;

Route::get('events', [EventsController::class, 'index'])->name('events.index');
