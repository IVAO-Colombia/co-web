<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Staff;

use App\Enums\PagesComponents;
use App\Enums\Permission;
use App\Enums\TrainingNoteVisibility;
use App\Enums\TrainingRequestStatus;
use App\Enums\TrainingRequestType;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexTrainingRequestsRequest;
use App\Http\Requests\UpdateTrainingRequestRequest;
use App\Models\TrainingRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Response;

class TrainingRequestsController extends Controller
{
    public function index(IndexTrainingRequestsRequest $request): Response
    {
        $filters = $request->filters();

        $trainingRequests = TrainingRequest::query()
            ->with(['trainee', 'trainer'])
            ->whereIn('status', $filters['statuses'])
            ->when($filters['type'], fn ($q, TrainingRequestType $type) => $q->ofType($type))
            ->when($filters['trainer_id'] === 'unassigned', fn ($q) => $q->whereNull('trainer_id'))
            ->when(is_int($filters['trainer_id']), fn ($q) => $q->where('trainer_id', $filters['trainer_id']))
            ->orderByRaw(
                'CASE
                    WHEN status = ? AND trainer_id IS NULL THEN 0
                    WHEN status = ? THEN 1
                    WHEN status = ? THEN 2
                    ELSE 3
                END',
                [
                    TrainingRequestStatus::PENDING->value,
                    TrainingRequestStatus::PENDING->value,
                    TrainingRequestStatus::SCHEDULED->value,
                ],
            )
            ->orderBy('occurs_at')
            ->orderBy('created_at')
            ->paginate(15)
            ->withQueryString();

        return inertia(PagesComponents::STAFF_TRAININGS_INDEX->value, [
            'trainingRequests' => $trainingRequests,
            'assignableTrainers' => $this->assignableTrainers(),
            'unassignedPendingCount' => TrainingRequest::pending()->whereNull('trainer_id')->count(),
            'filters' => $filters,
        ]);
    }

    public function show(TrainingRequest $trainingRequest): Response
    {
        Gate::authorize(Permission::VIEW_TRAINING_REQUESTS);

        $trainingRequest->load(['trainee', 'trainer', 'event']);

        return inertia(PagesComponents::STAFF_TRAININGS_SHOW->value, [
            'trainingRequest' => $trainingRequest,
            'assignableTrainers' => $this->assignableTrainers(),
            'canSendIvaoReminder' => $trainingRequest->canSendIvaoReminder(),
        ]);
    }

    /**
     * Staff members who can be assigned as a trainer, with their current
     * (pending or scheduled) workload by training type.
     *
     * @return Collection<int, User>
     */
    private function assignableTrainers(): Collection
    {
        return User::assignableToTrainings()
            ->select(['id', 'name', 'vid'])
            ->withCount([
                'assignedTrainings as atc_trainings_count' => fn ($q) => $q->active()->ofType(TrainingRequestType::ATC),
                'assignedTrainings as pilot_trainings_count' => fn ($q) => $q->active()->ofType(TrainingRequestType::Pilot),
            ])
            ->orderBy('name')
            ->get();
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
                Rule::in(User::assignableToTrainings()->pluck('id')),
            ],
        ]);

        $trainer = isset($validated['trainer_id'])
            ? User::query()->whereKey($validated['trainer_id'])->firstOrFail()
            : null;

        $trainingRequest->assignTrainer($actor, $trainer);

        return redirect()->back()
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
