<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Event;
use App\Models\PilotSlot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use RuntimeException;

class PilotSlotConfirmationReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly PilotSlot $slot) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Confirm your pilot slot reservation'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $event = $this->slot->event;
        throw_unless($event instanceof Event, new RuntimeException('Pilot slot is missing its event.'));

        return new Content(
            markdown: 'mail.pilot-slots.confirmation-reminder',
            with: [
                'eventName' => $event->name,
                'flightNumber' => $this->slot->airline_icao.$this->slot->flight_number,
                'origin' => $this->slot->origin,
                'destination' => $this->slot->destination,
                'departsAt' => $this->slot->departs_at,
                'arrivesAt' => $this->slot->arrives_at,
                'confirmationUrl' => route('dashboard.reservations.index', ['tab' => 'pilot']),
            ],
        );
    }
}
