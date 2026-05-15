<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Enums\AtcTraining;
use App\Enums\PagesComponents;
use App\Enums\PilotTraining;
use App\Enums\TrainingRequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrainingRequestRequest;
use App\Models\TrainingRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Response;

class TrainingsController extends Controller
{
    public function index(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        $trainingRequests = $user->trainingRequests()
            ->with(['trainer', 'event'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $availableAtcTrainings = AtcTraining::forRating($user->atc_rating)
            ->map(fn (AtcTraining $t): array => ['value' => $t->value, 'label' => $t->label()])
            ->values();

        $availablePilotTrainings = PilotTraining::forRating($user->pilot_rating)
            ->map(fn (PilotTraining $t): array => ['value' => $t->value, 'label' => $t->label()])
            ->values();

        return inertia(PagesComponents::DASHBOARD_TRAININGS->value, [
            'trainingRequests' => $trainingRequests,
            'availableAtcTrainings' => $availableAtcTrainings,
            'availablePilotTrainings' => $availablePilotTrainings,
        ]);
    }

    public function store(StoreTrainingRequestRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validated();

        TrainingRequest::create([
            'type' => $validated['type'],
            'category' => $validated['category'],
            'request_observations' => $validated['request_observations'],
            'trainee_id' => $user->id,
            'status' => TrainingRequestStatus::Pending,
        ]);

        return redirect()->route('dashboard.trainings.index');
    }

    public function destroy(Request $request, TrainingRequest $trainingRequest): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        abort_if($trainingRequest->trainee_id !== $user->id, 403);

        if ($trainingRequest->status !== TrainingRequestStatus::Pending) {
            throw ValidationException::withMessages([
                'status' => __('Only pending training requests can be cancelled.'),
            ]);
        }

        $trainingRequest->cancel();

        return redirect()->route('dashboard.trainings.index')
            ->with('success', __('Training request cancelled.'));
    }
}
