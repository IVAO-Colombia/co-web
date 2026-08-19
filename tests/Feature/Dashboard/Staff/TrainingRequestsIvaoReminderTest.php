<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard\Staff;

use App\Enums\TrainingRequestStatus;
use App\Mail\TrainingRequestIvaoReminder;
use App\Models\TrainingRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TrainingRequestsIvaoReminderTest extends TestCase
{
    #[Test]
    public function guests_cannot_send_an_ivao_reminder(): void
    {
        $request = TrainingRequest::factory()->pending()->create();

        $this->post(route('dashboard.staff.training-requests.ivao-reminder.store', $request))
            ->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function view_only_staff_cannot_send_an_ivao_reminder(): void
    {
        Mail::fake();

        $viewer = User::factory()->membershipCoordinator()->create();
        $request = TrainingRequest::factory()->pending()->create();

        $this->actingAs($viewer)
            ->post(route('dashboard.staff.training-requests.ivao-reminder.store', $request))
            ->assertForbidden();

        Mail::assertNothingOutgoing();
    }

    #[Test]
    public function training_coordinators_can_send_an_ivao_reminder(): void
    {
        Mail::fake();

        $coordinator = User::factory()->trainingCoordinator()->create();
        $request = TrainingRequest::factory()->pending()->create();

        $this->actingAs($coordinator)
            ->post(route('dashboard.staff.training-requests.ivao-reminder.store', $request))
            ->assertRedirect(route('dashboard.staff.training-requests.show', $request));

        Mail::assertQueued(
            TrainingRequestIvaoReminder::class,
            fn ($mail): bool => $mail->hasTo($request->trainee->email)
        );

        $request->refresh();
        $this->assertNotNull($request->ivao_reminder_sent_at);
    }

    #[Test]
    public function sending_a_second_reminder_immediately_is_rejected(): void
    {
        Mail::fake();

        $coordinator = User::factory()->trainingCoordinator()->create();
        $request = TrainingRequest::factory()->pending()->create([
            'ivao_reminder_sent_at' => now(),
        ]);

        $this->actingAs($coordinator)
            ->post(route('dashboard.staff.training-requests.ivao-reminder.store', $request))
            ->assertSessionHasErrors('ivao_reminder');

        Mail::assertNothingOutgoing();
    }

    #[Test]
    public function a_reminder_can_be_sent_again_after_the_cooldown_elapses(): void
    {
        Mail::fake();

        $coordinator = User::factory()->trainingCoordinator()->create();
        $request = TrainingRequest::factory()->pending()->create([
            'ivao_reminder_sent_at' => now()->subHours(25),
        ]);

        $this->actingAs($coordinator)
            ->post(route('dashboard.staff.training-requests.ivao-reminder.store', $request))
            ->assertRedirect(route('dashboard.staff.training-requests.show', $request));

        Mail::assertQueued(TrainingRequestIvaoReminder::class);
    }

    #[Test]
    public function a_reminder_cannot_be_sent_for_a_cancelled_request(): void
    {
        Mail::fake();

        $coordinator = User::factory()->trainingCoordinator()->create();
        $request = TrainingRequest::factory()->cancelled()->create();

        $this->actingAs($coordinator)
            ->post(route('dashboard.staff.training-requests.ivao-reminder.store', $request))
            ->assertForbidden();

        Mail::assertNothingOutgoing();
    }

    #[Test]
    public function a_reminder_cannot_be_sent_for_a_completed_request(): void
    {
        Mail::fake();

        $coordinator = User::factory()->trainingCoordinator()->create();
        $request = TrainingRequest::factory()->create([
            'status' => TrainingRequestStatus::COMPLETED,
        ]);

        $this->actingAs($coordinator)
            ->post(route('dashboard.staff.training-requests.ivao-reminder.store', $request))
            ->assertForbidden();

        Mail::assertNothingOutgoing();
    }
}
