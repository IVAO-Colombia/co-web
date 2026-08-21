<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Enums\EventStatus;
use App\Enums\SlotStatus;
use App\Mail\PilotSlotCancelled;
use App\Mail\PilotSlotConfirmationReminder;
use App\Models\AtcSlot;
use App\Models\Event;
use App\Models\PilotSlot;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProcessPilotSlotConfirmationsTest extends TestCase
{
    #[Test]
    public function it_sends_a_reminder_for_a_reserved_slot_72_hours_before_the_event(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->addHours(70),
        ]);
        $slot = PilotSlot::factory()->for($event)->reserved()->create(['pilot_id' => $user->id]);

        $this->artisan('pilot-slots:process-confirmations')->assertSuccessful();

        Mail::assertQueued(PilotSlotConfirmationReminder::class, fn ($mail): bool => $mail->hasTo($user->email) && $mail->slot->is($slot));
        $this->assertNotNull($slot->fresh()->reminder_sent_at);
        $this->assertEquals(SlotStatus::RESERVED, $slot->fresh()->status);
    }

    #[Test]
    public function the_reminder_shows_the_arrival_only_when_the_slot_has_one(): void
    {
        $event = Event::factory()->create(['status' => EventStatus::ACTIVE]);

        $withArrival = PilotSlot::factory()->for($event)->reserved()->create([
            'departs_at' => '2026-06-01 18:00',
            'arrives_at' => '2026-06-01 20:15',
        ]);

        $rendered = (new PilotSlotConfirmationReminder($withArrival))->render();

        $this->assertStringContainsString('Jun 1, 2026 18:00 UTC', $rendered);
        $this->assertStringContainsString('Jun 1, 2026 20:15 UTC', $rendered);

        $withoutArrival = PilotSlot::factory()->for($event)->reserved()->create([
            'departs_at' => '2026-06-01 18:00',
            'arrives_at' => null,
        ]);

        $rendered = (new PilotSlotConfirmationReminder($withoutArrival))->render();

        $this->assertStringContainsString('Jun 1, 2026 18:00 UTC', $rendered);
        $this->assertStringNotContainsString('Arrival', $rendered);
    }

    #[Test]
    public function it_does_not_remind_a_confirmed_slot(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->addHours(70),
        ]);
        PilotSlot::factory()->for($event)->confirmed()->create(['pilot_id' => $user->id]);

        $this->artisan('pilot-slots:process-confirmations')->assertSuccessful();

        Mail::assertNothingOutgoing();
    }

    #[Test]
    public function it_does_not_send_a_reminder_twice(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->addHours(70),
        ]);
        PilotSlot::factory()->for($event)->reserved()->create(['pilot_id' => $user->id]);

        $this->artisan('pilot-slots:process-confirmations')->assertSuccessful();
        $this->artisan('pilot-slots:process-confirmations')->assertSuccessful();

        Mail::assertQueued(PilotSlotConfirmationReminder::class, 1);
    }

    #[Test]
    public function it_cancels_a_reminded_slot_that_is_still_unconfirmed_48_hours_before_the_event(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->addHours(40),
        ]);
        $slot = PilotSlot::factory()->for($event)->reserved()->create([
            'pilot_id' => $user->id,
            'reminder_sent_at' => now()->subDay(),
        ]);

        $this->artisan('pilot-slots:process-confirmations')->assertSuccessful();

        Mail::assertQueued(PilotSlotCancelled::class, fn ($mail) => $mail->hasTo($user->email));
        Mail::assertNotQueued(PilotSlotConfirmationReminder::class);

        $slot->refresh();
        $this->assertEquals(SlotStatus::AVAILABLE, $slot->status);
        $this->assertNull($slot->pilot_id);
        $this->assertNull($slot->reminder_sent_at);
    }

    #[Test]
    public function it_does_not_cancel_an_unreminded_slot_inside_the_cancellation_window_and_reminds_instead(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->addHours(40),
        ]);
        $slot = PilotSlot::factory()->for($event)->reserved()->create(['pilot_id' => $user->id]);

        $this->artisan('pilot-slots:process-confirmations')->assertSuccessful();

        Mail::assertNotQueued(PilotSlotCancelled::class);
        Mail::assertQueued(PilotSlotConfirmationReminder::class);

        $slot->refresh();
        $this->assertEquals(SlotStatus::RESERVED, $slot->status);
        $this->assertNotNull($slot->reminder_sent_at);
    }

    #[Test]
    public function it_never_touches_atc_slots(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->addHours(40),
        ]);
        $slot = AtcSlot::factory()->for($event)->reserved()->create(['atc_id' => $user->id]);

        $this->artisan('pilot-slots:process-confirmations')->assertSuccessful();

        Mail::assertNothingOutgoing();
        $this->assertEquals(SlotStatus::RESERVED, $slot->fresh()->status);
    }

    #[Test]
    public function it_ignores_slots_for_events_that_already_started(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create([
            'status' => EventStatus::ACTIVE,
            'starts_at' => now()->subHour(),
        ]);
        $slot = PilotSlot::factory()->for($event)->reserved()->create([
            'pilot_id' => $user->id,
            'reminder_sent_at' => now()->subDay(),
        ]);

        $this->artisan('pilot-slots:process-confirmations')->assertSuccessful();

        Mail::assertNothingOutgoing();
        $this->assertEquals(SlotStatus::RESERVED, $slot->fresh()->status);
    }

    #[Test]
    public function it_ignores_slots_for_non_active_events(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $event = Event::factory()->cancelled()->create([
            'starts_at' => now()->addHours(40),
        ]);
        $slot = PilotSlot::factory()->for($event)->reserved()->create([
            'pilot_id' => $user->id,
            'reminder_sent_at' => now()->subDay(),
        ]);

        $this->artisan('pilot-slots:process-confirmations')->assertSuccessful();

        Mail::assertNothingOutgoing();
        $this->assertEquals(SlotStatus::RESERVED, $slot->fresh()->status);
    }
}
