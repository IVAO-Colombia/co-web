<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard\Staff;

use App\Enums\Role;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateUserRolesTest extends TestCase
{
    #[Test]
    public function guests_cannot_update_roles(): void
    {
        $target = User::factory()->create();

        $this->patch(route('dashboard.staff.users.roles.update', $target), ['roles' => []])
            ->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function users_without_permission_cannot_update_roles(): void
    {
        $actor = User::factory()->create();
        $target = User::factory()->trainer()->create();

        $this->actingAs($actor)
            ->patch(route('dashboard.staff.users.roles.update', $target), ['roles' => []])
            ->assertForbidden();

        $this->assertTrue($target->fresh()?->hasRole(Role::T0));
    }

    #[Test]
    public function directors_cannot_update_roles(): void
    {
        $director = User::factory()->director()->create();
        $target = User::factory()->trainer()->create();

        $this->actingAs($director)
            ->patch(route('dashboard.staff.users.roles.update', $target), ['roles' => []])
            ->assertForbidden();

        $this->assertTrue($target->fresh()?->hasRole(Role::T0));
    }

    #[Test]
    public function membership_coordinators_cannot_update_roles(): void
    {
        $coordinator = User::factory()->membershipCoordinator()->create();
        $target = User::factory()->trainer()->create();

        $this->actingAs($coordinator)
            ->patch(route('dashboard.staff.users.roles.update', $target), ['roles' => []])
            ->assertForbidden();

        $this->assertTrue($target->fresh()?->hasRole(Role::T0));
    }

    #[Test]
    public function webmasters_can_replace_the_roles_of_a_member(): void
    {
        $webmaster = User::factory()->webmaster()->create();
        $target = User::factory()->trainer()->create();

        $indexUrl = route('dashboard.staff.users.index');

        $this->actingAs($webmaster)
            ->from($indexUrl)
            ->patch(route('dashboard.staff.users.roles.update', $target), [
                'roles' => [Role::TC->value, Role::MC->value],
            ])
            ->assertRedirect($indexUrl);

        $this->assertEqualsCanonicalizing(
            [Role::TC->value, Role::MC->value],
            $target->fresh()?->getRoleNames()->all(),
        );
    }

    #[Test]
    public function assistant_webmasters_can_update_roles(): void
    {
        $actor = User::factory()->assistantWebmaster()->create();
        $target = User::factory()->create();

        $this->actingAs($actor)
            ->patch(route('dashboard.staff.users.roles.update', $target), [
                'roles' => [Role::T0->value],
            ])
            ->assertRedirect();

        $this->assertTrue($target->fresh()?->hasRole(Role::T0));
    }

    #[Test]
    public function webmaster_advisors_can_update_roles(): void
    {
        $actor = User::factory()->webmasterAdvisor()->create();
        $target = User::factory()->create();

        $this->actingAs($actor)
            ->patch(route('dashboard.staff.users.roles.update', $target), [
                'roles' => [Role::T0->value],
            ])
            ->assertRedirect();

        $this->assertTrue($target->fresh()?->hasRole(Role::T0));
    }

    #[Test]
    public function an_empty_list_removes_every_role(): void
    {
        $webmaster = User::factory()->webmaster()->create();
        $target = User::factory()->trainer()->create();

        $this->actingAs($webmaster)
            ->patch(route('dashboard.staff.users.roles.update', $target), ['roles' => []])
            ->assertRedirect();

        $this->assertEmpty($target->fresh()?->getRoleNames()->all());
    }

    #[Test]
    public function unknown_roles_are_rejected(): void
    {
        $webmaster = User::factory()->webmaster()->create();
        $target = User::factory()->trainer()->create();

        $this->actingAs($webmaster)
            ->patch(route('dashboard.staff.users.roles.update', $target), [
                'roles' => ['not_a_role'],
            ])
            ->assertSessionHasErrors('roles.0');

        $this->assertTrue($target->fresh()?->hasRole(Role::T0));
    }

    #[Test]
    public function the_roles_list_is_required(): void
    {
        $webmaster = User::factory()->webmaster()->create();
        $target = User::factory()->trainer()->create();

        $this->actingAs($webmaster)
            ->patch(route('dashboard.staff.users.roles.update', $target), [])
            ->assertSessionHasErrors('roles');

        $this->assertTrue($target->fresh()?->hasRole(Role::T0));
    }
}
