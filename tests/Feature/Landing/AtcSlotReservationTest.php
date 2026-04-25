<?php

declare(strict_types=1);

namespace Tests\Feature\Landing;

use App\Enums\SlotStatus;
use App\Models\AtcSlot;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AtcSlotReservationTest extends TestCase
{
    #[Test]
    public function authenticated_user_can_reserve_an_available_atc_slot(): void
    {
        Http::fake([
            '*/v2/fras/check/*' => Http::response([], 200),
            '*/v2/atc/bookings' => Http::response(['id' => 'booking-123'], 201),
        ]);

        $user = User::factory()->create(['atc_rating' => 5, 'vid' => 123456]);
        $event = Event::factory()->create();
        $slot = AtcSlot::factory()->for($event)->create([
            'status' => SlotStatus::AVAILABLE,
            'starts_at' => now()->addDay()->setTime(14, 0),
            'ends_at' => now()->addDay()->setTime(15, 0),
        ]);

        $response = $this->actingAs($user)
            ->post(route('home.events.atc-slot.store', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertRedirect(route('home.events.show', $event));
        $response->assertSessionHas('success');

        $slot->refresh();
        $this->assertEquals(SlotStatus::RESERVED, $slot->status);
        $this->assertEquals($user->id, $slot->atc_id);
        $this->assertNotNull($slot->ivao_booking);
    }

    #[Test]
    public function unauthenticated_user_is_redirected_to_home(): void
    {
        $event = Event::factory()->create();
        $slot = AtcSlot::factory()->for($event)->create(['status' => SlotStatus::AVAILABLE]);

        $response = $this->post(route('home.events.atc-slot.store', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertRedirect(route('home'));
    }

    #[Test]
    public function returns_404_when_slot_does_not_belong_to_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $otherEvent = Event::factory()->create();
        $slot = AtcSlot::factory()->for($otherEvent)->create(['status' => SlotStatus::AVAILABLE]);

        $response = $this->actingAs($user)
            ->post(route('home.events.atc-slot.store', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertNotFound();
    }

    #[Test]
    public function returns_409_when_slot_is_already_reserved(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $slot = AtcSlot::factory()->for($event)->reserved()->create();

        $response = $this->actingAs($user)
            ->post(route('home.events.atc-slot.store', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertStatus(409);
    }

    #[Test]
    public function redirects_to_auth_when_ivao_returns_401(): void
    {
        Http::fake([
            '*/v2/fras/check/*' => Http::response(['message' => 'Unauthorized'], 401),
        ]);

        $user = User::factory()->create(['vid' => 123456]);
        $event = Event::factory()->create();
        $slot = AtcSlot::factory()->for($event)->create([
            'status' => SlotStatus::AVAILABLE,
            'starts_at' => now()->addDay()->setTime(14, 0),
            'ends_at' => now()->addDay()->setTime(15, 0),
        ]);

        $response = $this->actingAs($user)
            ->post(route('home.events.atc-slot.store', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertRedirect(route('auth.redirect'));
        $this->assertEquals(SlotStatus::AVAILABLE, $slot->fresh()->status);
    }

    #[Test]
    public function redirects_back_with_error_when_ivao_returns_403(): void
    {
        Http::fake([
            '*/v2/fras/check/*' => Http::response([
                'message' => 'Rating not sufficient',
                'statusCode' => 403,
                'error' => 'Forbidden',
            ], 403),
        ]);

        $user = User::factory()->create(['vid' => 123456]);
        $event = Event::factory()->create();
        $slot = AtcSlot::factory()->for($event)->create([
            'status' => SlotStatus::AVAILABLE,
            'starts_at' => now()->addDay()->setTime(14, 0),
            'ends_at' => now()->addDay()->setTime(15, 0),
        ]);

        $response = $this->actingAs($user)
            ->from(route('home.events.show', $event))
            ->post(route('home.events.atc-slot.store', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertRedirect(route('home.events.show', $event));
        $response->assertSessionHas('error', 'Rating not sufficient');
        $this->assertEquals(SlotStatus::AVAILABLE, $slot->fresh()->status);
    }

    #[Test]
    public function redirects_to_auth_when_booking_returns_401(): void
    {
        Http::fake([
            '*/v2/fras/check/*' => Http::response([], 200),
            '*/v2/atc/bookings' => Http::response([
                'error' => 'not_authenticated_as_user',
                'message' => 'This endpoint is only available from user authentication',
                'statusCode' => 401,
            ], 401),
        ]);

        $user = User::factory()->create(['vid' => 123456]);
        $event = Event::factory()->create();
        $slot = AtcSlot::factory()->for($event)->create([
            'status' => SlotStatus::AVAILABLE,
            'starts_at' => now()->addDay()->setTime(14, 0),
            'ends_at' => now()->addDay()->setTime(15, 0),
        ]);

        $response = $this->actingAs($user)
            ->post(route('home.events.atc-slot.store', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertRedirect(route('auth.redirect'));
        $this->assertEquals(SlotStatus::AVAILABLE, $slot->fresh()->status);
    }

    #[Test]
    public function redirects_back_with_error_when_booking_returns_400(): void
    {
        Http::fake([
            '*/v2/fras/check/*' => Http::response([], 200),
            '*/v2/atc/bookings' => Http::response([
                'message' => 'Slot already booked',
                'statusCode' => 400,
                'error' => 'bad_request',
            ], 400),
        ]);

        $user = User::factory()->create(['vid' => 123456]);
        $event = Event::factory()->create();
        $slot = AtcSlot::factory()->for($event)->create([
            'status' => SlotStatus::AVAILABLE,
            'starts_at' => now()->addDay()->setTime(14, 0),
            'ends_at' => now()->addDay()->setTime(15, 0),
        ]);

        $response = $this->actingAs($user)
            ->from(route('home.events.show', $event))
            ->post(route('home.events.atc-slot.store', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertRedirect(route('home.events.show', $event));
        $response->assertSessionHas('error', 'Slot already booked');
        $this->assertEquals(SlotStatus::AVAILABLE, $slot->fresh()->status);
    }

    #[Test]
    public function redirects_back_with_error_when_booking_returns_403(): void
    {
        Http::fake([
            '*/v2/fras/check/*' => Http::response([], 200),
            '*/v2/atc/bookings' => Http::response([
                'message' => 'Not authorized to book this position',
                'statusCode' => 403,
                'error' => 'forbidden',
            ], 403),
        ]);

        $user = User::factory()->create(['vid' => 123456]);
        $event = Event::factory()->create();
        $slot = AtcSlot::factory()->for($event)->create([
            'status' => SlotStatus::AVAILABLE,
            'starts_at' => now()->addDay()->setTime(14, 0),
            'ends_at' => now()->addDay()->setTime(15, 0),
        ]);

        $response = $this->actingAs($user)
            ->from(route('home.events.show', $event))
            ->post(route('home.events.atc-slot.store', ['event' => $event->slug, 'slot' => $slot->id]));

        $response->assertRedirect(route('home.events.show', $event));
        $response->assertSessionHas('error', 'Not authorized to book this position');
        $this->assertEquals(SlotStatus::AVAILABLE, $slot->fresh()->status);
    }
}
