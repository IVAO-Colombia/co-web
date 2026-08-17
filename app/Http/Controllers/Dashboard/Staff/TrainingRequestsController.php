<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Staff;

use App\Enums\PagesComponents;
use App\Enums\Permission;
use App\Enums\TrainingNoteVisibility;
use App\Enums\TrainingRequestStatus;
use App\Enums\TrainingRequestType;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTrainingRequestRequest;
use App\Models\TrainingRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Response;

class TrainingRequestsController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize(Permission::VIEW_TRAINING_REQUESTS);

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
            'scheduled' => TrainingRequest::where('status', TrainingRequestStatus::SCHEDULED)->count(),
        ];

        return inertia(PagesComponents::STAFF_TRAININGS_INDEX->value, [
            'trainingRequests' => $trainingRequests,
            'counts' => $counts,
            'filters' => $request->only(['status', 'type']),
        ]);
    }

    public function show(TrainingRequest $trainingRequest): Response
    {
        Gate::authorize(Permission::VIEW_TRAINING_REQUESTS);

        $trainingRequest->load(['trainee', 'trainer', 'event']);

        $assignableStaff = User::permission(Permission::BE_ASSIGNED_TO_TRAININGS->value)
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
            'occurs_at' => $validated['occurs_at'] ?? $trainingRequest->occurs_at,
            'internal_observations' => $validated['internal_observations'] ?? $trainingRequest->internal_observations,
            'public_observations' => $validated['public_observations'] ?? $trainingRequest->public_observations,
            'status' => isset($validated['status']) ? TrainingRequestStatus::from($validated['status']) : $trainingRequest->status,
        ]);

        $trainingRequest->save();

        return redirect()->route('dashboard.staff.training-requests.show', $trainingRequest)
            ->with('success', __('Training request updated.'));
    }

    public function assignTrainer(
        Request $request,
        TrainingRequest $trainingRequest,
        #[CurrentUser] User $actor,
    ): RedirectResponse {
        Gate::authorize(Permission::ASSIGN_TRAINING_REQUESTS);

        abort_if($trainingRequest->status->isFinal(), 403);

        $validated = $request->validate([
            'trainer_id' => [
                'nullable',
                'integer',
                Rule::in(User::permission(Permission::BE_ASSIGNED_TO_TRAININGS->value)->pluck('id')),
            ],
        ]);

        $trainer = isset($validated['trainer_id'])
            ? User::query()->whereKey($validated['trainer_id'])->firstOrFail()
            : null;

        $trainingRequest->assignTrainer($actor, $trainer);

        return redirect()->route('dashboard.staff.training-requests.show', $trainingRequest)
            ->with('success', $trainer instanceof User
                ? __('Trainer assigned.')
                : __('Trainer unassigned.'));
    }

    public function storeNote(
        Request $request,
        TrainingRequest $trainingRequest,
        #[CurrentUser] User $author,
    ): RedirectResponse {
        abort_if(
            $author->cannot(Permission::EDIT_TRAINING_NOTES) && $trainingRequest->trainer_id !== $author->id,
            403
        );

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'visibility' => ['required', Rule::enum(TrainingNoteVisibility::class)],
        ]);

        $trainingRequest->appendNote(
            $author,
            TrainingNoteVisibility::from($validated['visibility']),
            $validated['body'],
        );

        return redirect()->route('dashboard.staff.training-requests.show', $trainingRequest)
            ->with('success', __('Note added.'));
    }

    public function destroy(TrainingRequest $trainingRequest): RedirectResponse
    {
        Gate::authorize(Permission::UPDATE_TRAINING_REQUESTS);

        $trainingRequest->cancel();

        return redirect()->route('dashboard.staff.training-requests.index')
            ->with('success', __('Training request cancelled.'));
    }
}
