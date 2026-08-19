<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Staff;

use App\Enums\Permission;
use App\Http\Controllers\Controller;
use App\Mail\TrainingRequestIvaoReminder;
use App\Models\TrainingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class SendIvaoTrainingRequestReminderController extends Controller
{
    public function __invoke(TrainingRequest $trainingRequest): RedirectResponse
    {
        Gate::authorize(Permission::UPDATE_TRAINING_REQUESTS);

        abort_if($trainingRequest->status->isFinal(), 403);

        if (! $trainingRequest->canSendIvaoReminder()) {
            throw ValidationException::withMessages([
                'ivao_reminder' => __('A reminder was already sent recently. Please wait before sending another one.'),
            ]);
        }

        Mail::to($trainingRequest->trainee)
            ->locale(config('app.locale'))
            ->send(new TrainingRequestIvaoReminder($trainingRequest));

        $trainingRequest->markIvaoReminderSent();

        return redirect()->route('dashboard.staff.training-requests.show', $trainingRequest)
            ->with('success', __('IVAO reminder sent to the trainee.'));
    }
}
