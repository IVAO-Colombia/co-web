<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Permission;
use App\Enums\TrainingRequestStatus;
use App\Enums\TrainingRequestType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexTrainingRequestsRequest extends FormRequest
{
    /**
     * The statuses shown when the request carries no explicit filter: the
     * ones staff still need to act on.
     *
     * @var list<string>
     */
    private const array DEFAULT_STATUSES = [
        TrainingRequestStatus::PENDING->value,
        TrainingRequestStatus::SCHEDULED->value,
    ];

    public function authorize(): bool
    {
        return $this->user()?->can(Permission::VIEW_TRAINING_REQUESTS) ?? false;
    }

    #[\Override]
    protected function prepareForValidation(): void
    {
        if (! $this->has('statuses')) {
            $this->merge(['statuses' => self::DEFAULT_STATUSES]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'statuses' => ['array', 'min:1'],
            'statuses.*' => [Rule::enum(TrainingRequestStatus::class)],
            'type' => ['nullable', Rule::enum(TrainingRequestType::class)],
            'trainer_id' => [
                'nullable',
                'string',
                Rule::in([
                    'unassigned',
                    ...User::assignableToTrainings()->pluck('id')->map(fn (int $id): string => (string) $id),
                ]),
            ],
        ];
    }

    /**
     * The validated filters, typed and defaulted for the controller.
     *
     * @return array{statuses: list<TrainingRequestStatus>, type: ?TrainingRequestType, trainer_id: int|'unassigned'|null}
     */
    public function filters(): array
    {
        $validated = $this->validated();

        $trainerId = $validated['trainer_id'] ?? null;

        return [
            'statuses' => array_values(array_map(
                TrainingRequestStatus::from(...),
                $validated['statuses'],
            )),
            'type' => isset($validated['type']) ? TrainingRequestType::from($validated['type']) : null,
            'trainer_id' => match (true) {
                $trainerId === null => null,
                $trainerId === 'unassigned' => 'unassigned',
                default => (int) $trainerId,
            },
        ];
    }
}
