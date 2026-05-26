<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use App\Enums\SlotStatus;
use App\Models\AtcSlot;
use App\Models\Event;
use App\Models\PilotSlot;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReservationsIndexTest extends TestCase
{
    #[Test]
    public function guests_are_redirected_from_reservations(): void
    {
        $this->get(route('dashboard.reservations.index'))
            ->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function authenticated_user_can_view_their_reservations(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $atcSlot = AtcSlot::factory()->for($event)->reserved()->create(['atc_id' => $user->id]);
        $pilotSlot = PilotSlot::factory()->for($event)->reserved()->create(['pilot_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('dashboard.reservations.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('atcSlots', 1, fn ($page) => $page
                    ->where('id', $atcSlot->id)
                    ->etc()
                )
                ->has('pilotSlots', 1, fn ($page) => $page
                    ->where('id', $pilotSlot->id)
                    ->etc()
                )
            );
    }

    #[Test]
    public function user_only_sees_their_own_slots(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $event = Event::factory()->create();

        AtcSlot::factory()->for($event)->reserved()->create(['atc_id' => $otherUser->id]);
        PilotSlot::factory()->for($event)->reserved()->create(['pilot_id' => $otherUser->id]);

        $this->actingAs($user)
            ->get(route('dashboard.reservations.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('atcSlots', 0)
                ->has('pilotSlots', 0)
            );
    }

    #[Test]
    public function available_slots_are_not_shown(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        AtcSlot::factory()->for($event)->create(['status' => SlotStatus::AVAILABLE, 'atc_id' => $user->id]);
        PilotSlot::factory()->for($event)->create(['status' => SlotStatus::AVAILABLE, 'pilot_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('dashboard.reservations.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('atcSlots', 0)
                ->has('pilotSlots', 0)
            );
    }

    #[Test]
    public function both_reserved_and_confirmed_slots_are_shown(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        AtcSlot::factory()->for($event)->reserved()->create(['atc_id' => $user->id]);
        AtcSlot::factory()->for($event)->confirmed()->create(['atc_id' => $user->id]);
        PilotSlot::factory()->for($event)->reserved()->create(['pilot_id' => $user->id]);
        PilotSlot::factory()->for($event)->confirmed()->create(['pilot_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('dashboard.reservations.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('atcSlots', 2)
                ->has('pilotSlots', 2)
            );
    }
}
