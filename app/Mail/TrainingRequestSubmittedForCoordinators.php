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

class TrainingRequestSubmittedForCoordinators extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly TrainingRequest $trainingRequest) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('New training request from :name', ['name' => $this->trainingRequest->trainee->name]),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $trainee = $this->trainingRequest->trainee;

        return new Content(
            markdown: 'mail.training-requests.coordinators',
            with: [
                'traineeName' => $trainee->name,
                'traineeVid' => $trainee->vid,
                'traineeEmail' => $trainee->email,
                'typeLabel' => $this->trainingRequest->type->label(),
                'categoryLabel' => $this->trainingRequest->categoryLabel(),
                'requestObservations' => $this->trainingRequest->request_observations,
                'requestedAt' => $this->trainingRequest->created_at,
                'showUrl' => route('dashboard.staff.training-requests.show', $this->trainingRequest),
            ],
        );
    }
}
