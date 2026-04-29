<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Enums\PagesComponents;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Response;

class ReservationsController extends Controller
{
    public function index(Request $request): Response
    {
        /** @var User */
        $user = $request->user();

        $atcSlots = $user->atcSlots()->with('event')->reserved()->latest()->get();
        $pilotSlots = $user->pilotSlots()->with('event')->reserved()->latest()->get();

        return inertia(PagesComponents::DASHBOARD_RESERVATIONS->value, [
            'atcSlots' => $atcSlots,
            'pilotSlots' => $pilotSlots,
        ]);
    }
}
