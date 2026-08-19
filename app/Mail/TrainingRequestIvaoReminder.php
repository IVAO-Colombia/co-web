<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\TrainingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrainingRequestIvaoReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly TrainingRequest $trainingRequest) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Reminder: request your training on the IVAO website'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.training-requests.ivao-reminder',
            with: [
                'categoryLabel' => $this->trainingRequest->categoryLabel(),
                'ivaoRequestUrl' => config('training.ivao_request_url'),
            ],
        );
    }
}
