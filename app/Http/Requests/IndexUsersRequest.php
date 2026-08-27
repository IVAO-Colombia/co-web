<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\ATCRating;
use App\Enums\Permission;
use App\Enums\PilotRating;
use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexUsersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permission::VIEW_USERS) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'query' => ['nullable', 'string', 'max:255'],
            'role' => [
                'nullable',
                'string',
                Rule::in(['none', ...array_map(fn (Role $role): string => $role->value, Role::cases())]),
            ],
            'division' => ['nullable', 'string', 'size:2'],
            'atc_rating' => ['nullable', Rule::enum(ATCRating::class)],
            'pilot_rating' => ['nullable', Rule::enum(PilotRating::class)],
        ];
    }

    /**
     * The validated filters, typed and defaulted for the controller.
     *
     * @return array{query: ?string, role: Role|'none'|null, division: ?string, atc_rating: ?ATCRating, pilot_rating: ?PilotRating}
     */
    public function filters(): array
    {
        $validated = $this->validated();

        $role = $validated['role'] ?? null;

        return [
            'query' => $validated['query'] ?? null,
            'role' => match (true) {
                $role === null => null,
                $role === 'none' => 'none',
                default => Role::from($role),
            },
            'division' => $validated['division'] ?? null,
            'atc_rating' => isset($validated['atc_rating']) ? ATCRating::from((int) $validated['atc_rating']) : null,
            'pilot_rating' => isset($validated['pilot_rating']) ? PilotRating::from((int) $validated['pilot_rating']) : null,
        ];
    }
}
