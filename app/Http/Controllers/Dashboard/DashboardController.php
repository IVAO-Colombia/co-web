<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Enums\PagesComponents;
use App\Enums\TrainingRequestStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Ivao\Ivao;
use Illuminate\Http\Request;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        /** @var User */
        $user = $request->user();
        $hours = array_map(fn (?int $seconds): int => $seconds ?? 0, $user->onlineHours());

        $trackerSessions = rescue(
            fn () => app(Ivao::class)->getTrackerSessions($user->vid),
            [],
        );

        $activeTrainingRequestsCount = $user->trainingRequests()
            ->whereIn('status', [TrainingRequestStatus::PENDING->value, TrainingRequestStatus::SCHEDULED->value])
            ->count();

        $reservationsCount = [
            'atc' => $user->atcSlots()->reserved()->count(),
            'pilot' => $user->pilotSlots()->reserved()->count(),
        ];

        return inertia(PagesComponents::DASHBOARD->value, [
            'hours' => $hours,
            'trackerSessions' => $trackerSessions,
            'activeTrainingRequestsCount' => $activeTrainingRequestsCount,
            'reservationsCount' => $reservationsCount,
            'atcRating' => $user->atc_rating,
            'pilotRating' => $user->pilot_rating,
        ]);
    }
}
