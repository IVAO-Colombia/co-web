<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use App\Enums\ATCRating;
use App\Enums\AtcTraining;
use App\Enums\PilotTraining;
use App\Enums\TrainingRequestStatus;
use App\Enums\TrainingRequestType;
use App\Mail\TrainingRequestSubmitted;
use App\Mail\TrainingRequestSubmittedForCoordinators;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TrainingsStoreTest extends TestCase
{
    #[Test]
    public function guests_cannot_submit_training_request(): void
    {
        $this->post(route('dashboard.trainings.store'), [
            'type' => TrainingRequestType::ATC->value,
            'category' => AtcTraining::AdcTheory1->value,
            'request_observations' => 'Available on weekends.',
        ])->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function user_can_submit_an_atc_training_request(): void
    {
        Mail::fake();

        $user = User::factory()->create(['atc_rating' => ATCRating::AS3]);

        $this->actingAs($user)
            ->post(route('dashboard.trainings.store'), [
                'type' => TrainingRequestType::ATC->value,
                'category' => AtcTraining::AdcTheory1->value,
                'request_observations' => 'Available on weekends.',
            ])
            ->assertRedirect(route('dashboard.trainings.index'));

        $this->assertDatabaseHas('training_requests', [
            'trainee_id' => $user->id,
            'type' => TrainingRequestType::ATC->value,
            'category' => AtcTraining::AdcTheory1->value,
            'status' => TrainingRequestStatus::PENDING->value,
        ]);
    }

    #[Test]
    public function submitting_a_request_emails_the_trainee_and_the_coordinators(): void
    {
        Mail::fake();

        $user = User::factory()->create(['atc_rating' => ATCRating::AS3]);

        $this->actingAs($user)
            ->post(route('dashboard.trainings.store'), [
                'type' => TrainingRequestType::ATC->value,
                'category' => AtcTraining::AdcTheory1->value,
                'request_observations' => 'Available on weekends.',
            ])
            ->assertRedirect(route('dashboard.trainings.index'));

        Mail::assertQueued(
            TrainingRequestSubmitted::class,
            fn ($mail): bool => $mail->hasTo($user->email)
        );

        Mail::assertQueued(
            TrainingRequestSubmittedForCoordinators::class,
            fn ($mail): bool => $mail->hasTo('co-tc@ivao.aero') && $mail->hasTo('co-tac@ivao.aero')
        );
    }

    #[Test]
    public function user_can_submit_a_pilot_training_request(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('dashboard.trainings.store'), [
                'type' => TrainingRequestType::Pilot->value,
                'category' => PilotTraining::Fs2Fs3Intro->value,
                'request_observations' => 'Available on weekday evenings.',
            ])
            ->assertRedirect(route('dashboard.trainings.index'));

        $this->assertDatabaseHas('training_requests', [
            'trainee_id' => $user->id,
            'type' => TrainingRequestType::Pilot->value,
        ]);
    }

    #[Test]
    public function invalid_category_for_type_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('dashboard.trainings.store'), [
                'type' => TrainingRequestType::ATC->value,
                'category' => PilotTraining::Fs2Fs3Intro->value,
                'request_observations' => 'Available on weekends.',
            ])
            ->assertSessionHasErrors('category');
    }

    #[Test]
    public function request_observations_is_required(): void
    {
        $user = User::factory()->create(['atc_rating' => ATCRating::AS2]);

        $this->actingAs($user)
            ->post(route('dashboard.trainings.store'), [
                'type' => TrainingRequestType::ATC->value,
                'category' => AtcTraining::AdcTheory1->value,
                'request_observations' => '',
            ])
            ->assertSessionHasErrors('request_observations');
    }
}
