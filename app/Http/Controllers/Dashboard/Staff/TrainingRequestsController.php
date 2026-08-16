<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Staff;

use App\Enums\PagesComponents;
use App\Enums\Permission;
use App\Enums\TrainingRequestStatus;
use App\Enums\TrainingRequestType;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTrainingRequestRequest;
use App\Models\TrainingRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Response;

class TrainingRequestsController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize(Permission::MANAGE_TRAINING_REQUESTS);

        $trainingRequests = TrainingRequest::query()
            ->with(['trainee', 'trainer'])
            ->when(
                $request->filled('status'),
                fn ($q) => $q->where('status', TrainingRequestStatus::from($request->string('status')->toString()))
            )
            ->when(
                $request->filled('type'),
                fn ($q) => $q->where('type', TrainingRequestType::from($request->string('type')->toString()))
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'pending' => TrainingRequest::pending()->count(),
            'scheduled' => TrainingRequest::where('status', TrainingRequestStatus::Scheduled)->count(),
        ];

        return inertia(PagesComponents::STAFF_TRAININGS_INDEX->value, [
            'trainingRequests' => $trainingRequests,
            'counts' => $counts,
            'filters' => $request->only(['status', 'type']),
        ]);
    }

    public function show(TrainingRequest $trainingRequest): Response
    {
        Gate::authorize(Permission::MANAGE_TRAINING_REQUESTS);

        $trainingRequest->load(['trainee', 'trainer', 'event']);

        $assignableStaff = User::permission(Permission::MANAGE_TRAINING_REQUESTS->value)
            ->select(['id', 'name', 'vid'])
            ->orderBy('name')
            ->get();

        return inertia(PagesComponents::STAFF_TRAININGS_SHOW->value, [
            'trainingRequest' => $trainingRequest,
            'assignableStaff' => $assignableStaff,
        ]);
    }

    public function update(UpdateTrainingRequestRequest $request, TrainingRequest $trainingRequest): RedirectResponse
    {
        $validated = $request->validated();

        $trainingRequest->fill([
            'trainer_id' => $validated['trainer_id'] ?? $trainingRequest->trainer_id,
            'occurs_at' => $validated['occurs_at'] ?? $trainingRequest->occurs_at,
            'internal_observations' => $validated['internal_observations'] ?? $trainingRequest->internal_observations,
            'public_observations' => $validated['public_observations'] ?? $trainingRequest->public_observations,
            'status' => isset($validated['status']) ? TrainingRequestStatus::from($validated['status']) : $trainingRequest->status,
        ]);

        $trainingRequest->save();

        return redirect()->route('dashboard.staff.training-requests.show', $trainingRequest)
            ->with('success', __('Training request updated.'));
    }

    public function destroy(TrainingRequest $trainingRequest): RedirectResponse
    {
        Gate::authorize(Permission::MANAGE_TRAINING_REQUESTS);

        $trainingRequest->cancel();

        return redirect()->route('dashboard.staff.training-requests.index')
            ->with('success', __('Training request cancelled.'));
    }
}
