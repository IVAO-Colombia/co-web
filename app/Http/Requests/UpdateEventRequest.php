<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\EventStatus;
use App\Enums\EventTag;
use App\Enums\EventType;
use App\Enums\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permission::UPDATE_EVENTS) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'description_en' => ['nullable', 'string'],
            'type' => ['required', Rule::enum(EventType::class)],
            'locations' => ['required', 'string', 'max:255'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'image' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'tags' => ['nullable', 'array'],
            'tags.*' => [Rule::enum(EventTag::class)],
            'status' => ['required', Rule::in([EventStatus::ACTIVE->value, EventStatus::CANCELLED->value])],
            'pilot_slots_enabled' => ['boolean'],
            'atc_slots_enabled' => ['boolean'],
            'pilot_slots' => ['nullable', 'array', Rule::requiredIf($this->boolean('pilot_slots_enabled'))],
            'pilot_slots.*.callsign' => ['required_with:pilot_slots', 'string', 'max:20'],
            'pilot_slots.*.flight_number' => ['nullable', 'string', 'max:10'],
            'pilot_slots.*.aircraft' => ['required_with:pilot_slots', 'string', 'max:10'],
            'pilot_slots.*.origin' => ['required_with:pilot_slots', 'string', 'size:4'],
            'pilot_slots.*.destination' => ['required_with:pilot_slots', 'string', 'size:4'],
            'pilot_slots.*.departs_at' => ['required_with:pilot_slots', 'date_format:Y-m-d H:i'],
            'pilot_slots.*.gate' => ['nullable', 'string', 'max:10'],
            'atc_slots' => ['nullable', 'array', Rule::requiredIf($this->boolean('atc_slots_enabled'))],
            'atc_slots.*.callsign' => ['required_with:atc_slots', 'string', 'max:20'],
            'atc_slots.*.starts_at' => ['required_with:atc_slots', 'date_format:H:i'],
            'atc_slots.*.ends_at' => ['required_with:atc_slots', 'date_format:H:i'],
        ];
    }
}
