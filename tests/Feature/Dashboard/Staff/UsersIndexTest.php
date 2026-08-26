<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard\Staff;

use App\Enums\ATCRating;
use App\Enums\PilotRating;
use App\Enums\Role;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UsersIndexTest extends TestCase
{
    #[Test]
    public function guests_are_redirected_from_staff_users(): void
    {
        $this->get(route('dashboard.staff.users.index'))
            ->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function users_without_permission_cannot_access_staff_users(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard.staff.users.index'))
            ->assertForbidden();
    }

    #[Test]
    public function directors_can_list_users(): void
    {
        $director = User::factory()->director()->create();
        User::factory()->create();

        $this->actingAs($director)
            ->get(route('dashboard.staff.users.index'))
            ->assertOk();
    }

    #[Test]
    public function membership_coordinators_can_list_users(): void
    {
        $viewer = User::factory()->membershipCoordinator()->create();

        $this->actingAs($viewer)
            ->get(route('dashboard.staff.users.index'))
            ->assertOk();
    }

    #[Test]
    public function webmasters_can_list_users(): void
    {
        $viewer = User::factory()->webmaster()->create();

        $this->actingAs($viewer)
            ->get(route('dashboard.staff.users.index'))
            ->assertOk();
    }

    #[Test]
    public function listing_exposes_roles_and_ratings_without_raw_data(): void
    {
        $director = User::factory()->director()->create();
        $trainer = User::factory()->trainer()->create([
            'atc_rating' => ATCRating::ACC,
            'pilot_rating' => PilotRating::CP,
        ]);

        $this->actingAs($director)
            ->get(route('dashboard.staff.users.index', ['role' => Role::T0->value]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('users.data', 1, fn ($page) => $page
                    ->where('id', $trainer->id)
                    ->where('roles', [Role::T0->value])
                    ->where('atc_rating', ATCRating::ACC->value)
                    ->where('pilot_rating', PilotRating::CP->value)
                    ->missing('raw_data')
                    ->etc()
                )
            );
    }

    #[Test]
    public function hours_come_from_the_users_raw_data_snapshot(): void
    {
        $director = User::factory()->director()->create();
        $user = User::factory()->create([
            'name' => 'Zzz Snapshot User',
            'raw_data' => [
                'hours' => [
                    ['type' => 'pilot', 'hours' => 3600],
                    ['type' => 'atc', 'hours' => 7200],
                ],
            ],
        ]);

        $this->actingAs($director)
            ->get(route('dashboard.staff.users.index', ['query' => $user->name]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('users.data', 1, fn ($page) => $page
                    ->where('id', $user->id)
                    ->where('atc_hours', 7200)
                    ->where('pilot_hours', 3600)
                    ->etc()
                )
            );
    }

    #[Test]
    public function hours_are_null_when_the_user_has_no_snapshot(): void
    {
        $director = User::factory()->director()->create();
        $user = User::factory()->create(['name' => 'Zzz No Snapshot', 'raw_data' => null]);

        $this->actingAs($director)
            ->get(route('dashboard.staff.users.index', ['query' => $user->name]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('users.data', 1, fn ($page) => $page
                    ->where('id', $user->id)
                    ->where('atc_hours', null)
                    ->where('pilot_hours', null)
                    ->etc()
                )
            );
    }

    #[Test]
    public function staff_can_search_by_name(): void
    {
        $director = User::factory()->director()->create();
        $match = User::factory()->create(['name' => 'Unique Search Name']);
        User::factory()->create(['name' => 'Someone Else']);

        $this->actingAs($director)
            ->get(route('dashboard.staff.users.index', ['query' => 'Unique Search']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('users.data', 1, fn ($page) => $page
                    ->where('id', $match->id)
                    ->etc()
                )
            );
    }

    #[Test]
    public function staff_can_search_by_vid(): void
    {
        $director = User::factory()->director()->create();
        $match = User::factory()->create(['vid' => 654321]);
        User::factory()->create(['vid' => 111111]);

        $this->actingAs($director)
            ->get(route('dashboard.staff.users.index', ['query' => '654321']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('users.data', 1, fn ($page) => $page
                    ->where('id', $match->id)
                    ->etc()
                )
            );
    }

    #[Test]
    public function staff_can_filter_by_role(): void
    {
        $director = User::factory()->director()->create();
        $trainer = User::factory()->trainer()->create();
        User::factory()->membershipCoordinator()->create();

        $this->actingAs($director)
            ->get(route('dashboard.staff.users.index', ['role' => Role::T0->value]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('users.data', 1, fn ($page) => $page
                    ->where('id', $trainer->id)
                    ->etc()
                )
            );
    }

    #[Test]
    public function staff_can_filter_by_no_role(): void
    {
        $director = User::factory()->director()->create();
        $noRole = User::factory()->create();
        User::factory()->trainer()->create();

        $this->actingAs($director)
            ->get(route('dashboard.staff.users.index', ['role' => 'none']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('users.data', 1, fn ($page) => $page
                    ->where('id', $noRole->id)
                    ->etc()
                )
            );
    }

    #[Test]
    public function staff_can_filter_by_division(): void
    {
        $director = User::factory()->director()->create(['division_id' => 'CO']);
        User::factory()->create(['division_id' => 'CO']);
        User::factory()->create(['division_id' => 'US']);

        $this->actingAs($director)
            ->get(route('dashboard.staff.users.index', ['division' => 'CO']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->has('users.data', 2));
    }

    #[Test]
    public function staff_can_filter_by_atc_rating(): void
    {
        $director = User::factory()->director()->create(['atc_rating' => ATCRating::AS1]);
        $match = User::factory()->create(['atc_rating' => ATCRating::CAI]);
        User::factory()->create(['atc_rating' => ATCRating::AS2]);

        $this->actingAs($director)
            ->get(route('dashboard.staff.users.index', ['atc_rating' => ATCRating::CAI->value]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('users.data', 1, fn ($page) => $page
                    ->where('id', $match->id)
                    ->etc()
                )
            );
    }

    #[Test]
    public function staff_can_filter_by_pilot_rating(): void
    {
        $director = User::factory()->director()->create(['pilot_rating' => PilotRating::FS1]);
        $match = User::factory()->create(['pilot_rating' => PilotRating::CFI]);
        User::factory()->create(['pilot_rating' => PilotRating::FS2]);

        $this->actingAs($director)
            ->get(route('dashboard.staff.users.index', ['pilot_rating' => PilotRating::CFI->value]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('users.data', 1, fn ($page) => $page
                    ->where('id', $match->id)
                    ->etc()
                )
            );
    }

    #[Test]
    public function an_invalid_role_is_rejected_instead_of_erroring(): void
    {
        $director = User::factory()->director()->create();

        $this->actingAs($director)
            ->get(route('dashboard.staff.users.index', ['role' => 'not-a-real-role']))
            ->assertSessionHasErrors('role');
    }
}
