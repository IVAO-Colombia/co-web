<?php

declare(strict_types=1);

use App\Http\Controllers\HomePageController;
use App\Http\Controllers\Landing\EventsController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePageController::class)->name('home');
Route::get('/events', [EventsController::class, 'index'])->name('home.events');
Route::get('/events/{event:slug}', [EventsController::class, 'show'])->name('home.events.show');
