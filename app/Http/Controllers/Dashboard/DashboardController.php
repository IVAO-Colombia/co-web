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
        $hours = $this->getHours($user->raw_data);

        $trackerSessions = rescue(
            fn () => app(Ivao::class)->getTrackerSessions($user->vid),
            [],
        );

        $activeTrainingRequestsCount = $user->trainingRequests()
            ->whereIn('status', [TrainingRequestStatus::Pending->value, TrainingRequestStatus::Scheduled->value])
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

    /**
     * @param  array<array-key, mixed>|null  $rawData
     * @return array<string, int>
     */
    private function getHours(?array $rawData): array
    {
        $result = ['pilot' => 0, 'atc' => 0, 'staff' => 0];

        /** @var array<int, array{type?: string, hours: int}> $hoursData */
        $hoursData = $rawData['hours'] ?? [];

        foreach ($hoursData as $entry) {
            $type = $entry['type'] ?? null;
            $result[$type] = (int) $entry['hours'];
        }

        return $result;
    }
}
