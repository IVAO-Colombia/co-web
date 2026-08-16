<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\EventStatus;
use App\Enums\SlotStatus;
use App\Mail\PilotSlotCancelled;
use App\Mail\PilotSlotConfirmationReminder;
use App\Models\PilotSlot;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Throwable;

#[Signature('pilot-slots:process-confirmations')]
#[Description('Sends confirmation reminders for pilot slots 72 hours before an event and cancels any that are still unconfirmed 48 hours before.')]
class ProcessPilotSlotConfirmations extends Command
{
    public function handle(): int
    {
        $this->cancelUnconfirmedSlots();
        $this->sendConfirmationReminders();

        return self::SUCCESS;
    }

    /**
     * Cancel reserved pilot slots that were reminded but remain unconfirmed
     * within 48 hours of their event starting.
     */
    private function cancelUnconfirmedSlots(): void
    {
        PilotSlot::query()
            ->where('status', SlotStatus::RESERVED)
            ->whereNotNull('reminder_sent_at')
            ->whereHas('event', fn (Builder $query) => $query
                ->where('status', EventStatus::ACTIVE)
                ->whereBetween('starts_at', [now(), now()->addHours(48)]))
            ->with(['event', 'pilot'])
            ->each(function (PilotSlot $slot): void {
                $pilot = $slot->pilot;
                $event = $slot->event;

                if ($pilot === null || $event === null) {
                    return;
                }

                try {
                    $eventName = $event->name;
                    $flightNumber = $slot->airline_icao.$slot->flight_number;
                    $origin = $slot->origin;
                    $destination = $slot->destination;
                    $departsAt = $slot->departs_at;

                    $slot->cancel();

                    Mail::to($pilot)->send(new PilotSlotCancelled(
                        eventName: $eventName,
                        flightNumber: $flightNumber,
                        origin: $origin,
                        destination: $destination,
                        departsAt: $departsAt,
                    ));
                } catch (Throwable $exception) {
                    $this->error("Failed to cancel pilot slot {$slot->id}: {$exception->getMessage()}");
                }
            });
    }

    /**
     * Send confirmation reminders for reserved pilot slots within 72 hours
     * of their event starting that have not been reminded yet.
     */
    private function sendConfirmationReminders(): void
    {
        PilotSlot::query()
            ->where('status', SlotStatus::RESERVED)
            ->whereNull('reminder_sent_at')
            ->whereHas('event', fn (Builder $query) => $query
                ->where('status', EventStatus::ACTIVE)
                ->whereBetween('starts_at', [now(), now()->addHours(72)]))
            ->with(['event', 'pilot'])
            ->each(function (PilotSlot $slot): void {
                $pilot = $slot->pilot;

                if ($pilot === null || $slot->event === null) {
                    return;
                }

                try {
                    Mail::to($pilot)->send(new PilotSlotConfirmationReminder($slot));

                    $slot->update(['reminder_sent_at' => now()]);
                } catch (Throwable $exception) {
                    $this->error("Failed to send confirmation reminder for pilot slot {$slot->id}: {$exception->getMessage()}");
                }
            });
    }
}
