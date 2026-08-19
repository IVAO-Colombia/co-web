<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | IVAO Training Request URL
    |--------------------------------------------------------------------------
    |
    | Submitting a request on this site is not enough: the member must also
    | open the request on the IVAO website. This is the URL we point them to.
    |
    */

    'ivao_request_url' => env('IVAO_TRAINING_REQUEST_URL', 'https://ivao.aero/training/training/statustraining.asp'),

    /*
    |--------------------------------------------------------------------------
    | Training Coordinator Mailboxes
    |--------------------------------------------------------------------------
    |
    | Every new training request is forwarded to these addresses (CO-TC and
    | CO-TAC).
    |
    */

    'coordinator_emails' => [
        'co-tc@ivao.aero',
        'co-tac@ivao.aero',
    ],

    /*
    |--------------------------------------------------------------------------
    | IVAO Reminder Cooldown
    |--------------------------------------------------------------------------
    |
    | How many hours staff must wait before sending a trainee another reminder
    | to open their request on the IVAO website.
    |
    */

    'ivao_reminder_cooldown_hours' => 24,

];
