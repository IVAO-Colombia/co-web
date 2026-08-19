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

class TrainingRequestSubmitted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly TrainingRequest $trainingRequest) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Your training request has been submitted'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.training-requests.submitted',
            with: [
                'categoryLabel' => $this->trainingRequest->categoryLabel(),
                'ivaoRequestUrl' => config('training.ivao_request_url'),
                'trainingsUrl' => route('dashboard.trainings.index'),
            ],
        );
    }
}
