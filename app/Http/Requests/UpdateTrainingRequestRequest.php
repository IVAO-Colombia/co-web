<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Permission;
use App\Enums\TrainingRequestStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTrainingRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permission::MANAGE_TRAINING_REQUESTS) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'trainer_id' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'occurs_at' => ['nullable', 'date'],
            'internal_observations' => ['nullable', 'string', 'max:5000'],
            'public_observations' => ['nullable', 'string', 'max:5000'],
            'status' => ['sometimes', Rule::enum(TrainingRequestStatus::class)],
        ];
    }
}
