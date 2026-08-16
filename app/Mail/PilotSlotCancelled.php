<?php

declare(strict_types=1);

namespace App\Mail;

use Carbon\CarbonImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PilotSlotCancelled extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $eventName,
        public readonly string $flightNumber,
        public readonly string $origin,
        public readonly string $destination,
        public readonly CarbonImmutable $departsAt,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Your pilot slot reservation has been cancelled'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.pilot-slots.cancelled',
            with: [
                'eventName' => $this->eventName,
                'flightNumber' => $this->flightNumber,
                'origin' => $this->origin,
                'destination' => $this->destination,
                'departsAt' => $this->departsAt,
                'reservationsUrl' => route('dashboard.reservations.index', ['tab' => 'pilot']),
            ],
        );
    }
}
