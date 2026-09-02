<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Permission;
use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permission::MANAGE_USER_ROLES) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'roles' => ['present', 'array'],
            'roles.*' => [Rule::enum(Role::class)],
        ];
    }

    /**
     * The roles the user should end up with, as enum cases.
     *
     * @return array<int, Role>
     */
    public function roles(): array
    {
        /** @var array<int, string> $roles */
        $roles = $this->validated()['roles'];

        return array_map(Role::from(...), $roles);
    }
}
