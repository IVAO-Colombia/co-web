<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Permission;
use App\Enums\TrainingRequestStatus;
use App\Models\TrainingRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTrainingRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permission::UPDATE_TRAINING_REQUESTS) ?? false;
    }

    /**
     * Drop payload the current user or the request's status does not allow:
     * notes may only be rewritten by users allowed to edit them, and the
     * schedule is frozen once the request reaches a final status.
     */
    #[\Override]
    protected function prepareForValidation(): void
    {
        if (! $this->user()?->can(Permission::EDIT_TRAINING_NOTES)) {
            $this->request->remove('internal_observations');
            $this->request->remove('public_observations');
        }

        $trainingRequest = $this->route('trainingRequest');

        if ($trainingRequest instanceof TrainingRequest && $trainingRequest->status->isFinal()) {
            $this->request->remove('occurs_at');
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'occurs_at' => ['nullable', 'date'],
            'internal_observations' => ['nullable', 'string', 'max:5000'],
            'public_observations' => ['nullable', 'string', 'max:5000'],
            'status' => ['sometimes', Rule::enum(TrainingRequestStatus::class)],
        ];
    }
}
