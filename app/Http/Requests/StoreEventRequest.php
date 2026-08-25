<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\EventTag;
use App\Enums\EventType;
use App\Enums\Permission;
use App\Enums\PilotSlotCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permission::CREATE_EVENTS) ?? false;
    }

    /**
     * Multipart submissions (any request carrying the image file) stringify every
     * scalar, so the weekdays arrive as `["4"]` and survive the `integer` rule,
     * which validates without casting. Cast numeric entries back so the stored
     * JSON holds real integers; leave anything else untouched for the rules to
     * reject.
     */
    #[\Override]
    protected function prepareForValidation(): void
    {
        $weekdays = $this->input('recurrence_weekdays');

        if (! is_array($weekdays)) {
            return;
        }

        $this->merge([
            'recurrence_weekdays' => array_map(
                fn (mixed $weekday): mixed => is_numeric($weekday) ? (int) $weekday : $weekday,
                $weekdays,
            ),
        ]);
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
            'pilot_slots_enabled' => ['boolean'],
            'atc_slots_enabled' => ['boolean'],
            'pilot_slots' => ['nullable', 'array', Rule::requiredIf($this->boolean('pilot_slots_enabled'))],
            'pilot_slots.*.airline_icao' => ['required_with:pilot_slots', 'string', 'max:10'],
            'pilot_slots.*.flight_number' => ['required_with:pilot_slots', 'string', 'max:20'],
            'pilot_slots.*.aircraft' => ['required_with:pilot_slots', 'string', 'max:10'],
            'pilot_slots.*.origin' => ['required_with:pilot_slots', 'string', 'size:4'],
            'pilot_slots.*.destination' => ['required_with:pilot_slots', 'string', 'size:4'],
            'pilot_slots.*.category' => ['required_with:pilot_slots', Rule::enum(PilotSlotCategory::class)],
            'pilot_slots.*.departs_at' => ['required_with:pilot_slots', 'date_format:Y-m-d H:i'],
            'pilot_slots.*.arrives_at' => ['nullable', 'date_format:Y-m-d H:i', 'after:pilot_slots.*.departs_at'],
            'pilot_slots.*.gate' => ['nullable', 'string', 'max:10'],
            'atc_slots' => ['nullable', 'array', Rule::requiredIf($this->boolean('atc_slots_enabled'))],
            'atc_slots.*.callsign' => ['required_with:atc_slots', 'string', 'max:20'],
            'atc_slots.*.starts_at' => ['required_with:atc_slots', 'date_format:Y-m-d H:i'],
            'atc_slots.*.ends_at' => ['required_with:atc_slots', 'date_format:Y-m-d H:i'],
            'is_recurring' => [
                'boolean',
                Rule::prohibitedIf(fn (): bool => $this->boolean('is_recurring') && $this->filled('training_request_id')),
            ],
            'recurrence_interval' => ['exclude_unless:is_recurring,true', 'required', 'integer', 'min:1'],
            'recurrence_weekdays' => ['exclude_unless:is_recurring,true', 'required', 'array', 'min:1'],
            'recurrence_weekdays.*' => ['integer', 'between:0,6'],
            'recurrence_ends_at' => ['exclude_unless:is_recurring,true', 'required', 'date', 'after:starts_at'],
            'training_request_id' => ['nullable', 'integer', Rule::exists('training_requests', 'id')],
        ];
    }

    // public function attributes(): array
    // {
    //     return [
    //         'atc_slots.*.callsign' => __('atc slots callsign'),
    //         'atc_slots.*.starts_at' => __('atc slots start time'),
    //         'atc_slots.*.ends_at' => __('atc slots end time'),
    //         'pilot_slots.*.callsign' => __('pilot slots callsign'),
    //         'pilot_slots.*.flight_number' => __('pilot slots flight number'),
    //         'pilot_slots.*.aircraft' => __('pilot slots aircraft'),
    //         'pilot_slots.*.origin' => __('pilot slots origin'),
    //         'pilot_slots.*.destination' => __('pilot slots destination'),
    //         'pilot_slots.*.departs_at' => __('pilot slots departure time'),
    //         'pilot_slots.*.gate' => __('pilot slots gate'),
    //     ];
    // }
}
