<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Staff;

use App\Enums\ATCRating;
use App\Enums\PagesComponents;
use App\Enums\PilotRating;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexUsersRequest;
use App\Models\User;
use Inertia\Response;

class UsersController extends Controller
{
    public function index(IndexUsersRequest $request): Response
    {
        $filters = $request->filters();

        $users = User::query()
            ->select(['id', 'name', 'email', 'vid', 'division_id', 'country_id', 'atc_rating', 'pilot_rating', 'raw_data'])
            ->with('roles:id,name')
            ->when($filters['query'], fn ($q, string $search) => $q->where(
                fn ($q) => $q
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('vid', 'like', "%{$search}%")
            ))
            ->when($filters['role'] === 'none', fn ($q) => $q->whereDoesntHave('roles'))
            ->when(
                $filters['role'] instanceof Role ? $filters['role'] : null,
                fn ($q, Role $role) => $q->role($role->value),
            )
            ->when($filters['division'], fn ($q, string $division) => $q->where('division_id', $division))
            ->when($filters['atc_rating'], fn ($q, ATCRating $rating) => $q->where('atc_rating', $rating))
            ->when($filters['pilot_rating'], fn ($q, PilotRating $rating) => $q->where('pilot_rating', $rating))
            ->orderBy('name')
            ->orderBy('vid')
            ->paginate(10)
            ->withQueryString()
            ->through(function (User $user): array {
                $hours = $user->onlineHours();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'vid' => $user->vid,
                    'division_id' => $user->division_id,
                    'country_id' => $user->country_id,
                    'atc_rating' => $user->atc_rating,
                    'pilot_rating' => $user->pilot_rating,
                    'atc_hours' => $hours['atc'],
                    'pilot_hours' => $hours['pilot'],
                    'roles' => $user->roles->pluck('name')->all(),
                ];
            });

        return inertia(PagesComponents::STAFF_USERS_INDEX->value, [
            'users' => $users,
            'filters' => $filters,
            'divisions' => User::query()
                ->whereNotNull('division_id')
                ->distinct()
                ->orderBy('division_id')
                ->pluck('division_id'),
        ]);
    }
}
