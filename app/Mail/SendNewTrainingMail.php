<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\Training;

class SendNewTrainingMail extends Mailable
{
    use Queueable, SerializesModels;

    public Training $training;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Training $training)
    {
        $this->training = $training;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown("mail.newtraining")->subject(
            "New training request. VID: " . $this->training->member->id
        );
    }
}
