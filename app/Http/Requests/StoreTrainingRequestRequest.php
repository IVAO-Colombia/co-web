<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\AtcTraining;
use App\Enums\PilotTraining;
use App\Enums\TrainingRequestType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreTrainingRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(TrainingRequestType::class)],
            'category' => ['required', 'string'],
            'request_observations' => ['required', 'string', 'max:2000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $type = TrainingRequestType::tryFrom((string) $this->input('type'));
            $category = (string) $this->input('category');

            if ($type === null) {
                return;
            }

            /** @var User $user */
            $user = $this->user();

            if ($type === TrainingRequestType::ATC) {
                $training = AtcTraining::tryFrom($category);

                if ($training === null) {
                    $validator->errors()->add('category', __('Invalid ATC training category.'));

                    return;
                }

                $userAtcRating = $user->atc_rating;

                if ($userAtcRating === null || $userAtcRating->value < $training->minimumAtcRating()->value) {
                    $validator->errors()->add('category', __('Your ATC rating is not sufficient for this training.'));
                }
            } elseif ($type === TrainingRequestType::Pilot) {
                $training = PilotTraining::tryFrom($category);

                if ($training === null) {
                    $validator->errors()->add('category', __('Invalid pilot training category.'));

                    return;
                }

                $userPilotRating = $user->pilot_rating;

                if ($userPilotRating === null || $userPilotRating->value < $training->minimumPilotRating()->value) {
                    $validator->errors()->add('category', __('Your pilot rating is not sufficient for this training.'));
                }
            }
        });
    }
}
