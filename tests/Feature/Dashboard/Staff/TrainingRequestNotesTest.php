<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard\Staff;

use App\Enums\TrainingNoteVisibility;
use App\Models\TrainingRequest;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TrainingRequestNotesTest extends TestCase
{
    #[Test]
    public function guests_cannot_add_a_note(): void
    {
        $request = TrainingRequest::factory()->create();

        $this->post(route('dashboard.staff.training-requests.notes.store', $request), [])
            ->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function training_coordinators_can_add_a_note(): void
    {
        $coordinator = User::factory()->trainingCoordinator()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($coordinator)
            ->post(route('dashboard.staff.training-requests.notes.store', $request), [
                'body' => 'Trainee rescheduled the session.',
                'visibility' => TrainingNoteVisibility::InternalNote->value,
            ])
            ->assertRedirect(route('dashboard.staff.training-requests.show', $request));

        $request->refresh();
        $this->assertStringContainsString('Trainee rescheduled the session.', (string) $request->internal_observations);
        $this->assertStringContainsString($coordinator->name, (string) $request->internal_observations);
    }

    #[Test]
    public function the_assigned_trainer_can_add_a_note(): void
    {
        $trainer = User::factory()->trainer()->create();
        $request = TrainingRequest::factory()->create(['trainer_id' => $trainer->id]);

        $this->actingAs($trainer)
            ->post(route('dashboard.staff.training-requests.notes.store', $request), [
                'body' => 'Session completed successfully.',
                'visibility' => TrainingNoteVisibility::PublicNote->value,
            ])
            ->assertRedirect();

        $request->refresh();
        $this->assertStringContainsString('Session completed successfully.', (string) $request->public_observations);
        $this->assertStringContainsString($trainer->name, (string) $request->public_observations);
    }

    #[Test]
    public function an_unassigned_trainer_cannot_add_a_note(): void
    {
        $trainer = User::factory()->trainer()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($trainer)
            ->post(route('dashboard.staff.training-requests.notes.store', $request), [
                'body' => 'Should not be allowed.',
                'visibility' => TrainingNoteVisibility::InternalNote->value,
            ])
            ->assertForbidden();

        $request->refresh();
        $this->assertNull($request->internal_observations);
    }

    #[Test]
    public function notes_are_appended_and_never_replace_existing_ones(): void
    {
        $coordinator = User::factory()->trainingCoordinator()->create();
        $request = TrainingRequest::factory()->create([
            'internal_observations' => 'Original note.',
        ]);

        $this->actingAs($coordinator)
            ->post(route('dashboard.staff.training-requests.notes.store', $request), [
                'body' => 'Follow-up note.',
                'visibility' => TrainingNoteVisibility::InternalNote->value,
            ])
            ->assertRedirect();

        $request->refresh();
        $this->assertStringContainsString('Original note.', (string) $request->internal_observations);
        $this->assertStringContainsString('Follow-up note.', (string) $request->internal_observations);
    }

    #[Test]
    public function each_visibility_writes_to_its_own_column(): void
    {
        $coordinator = User::factory()->trainingCoordinator()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($coordinator)
            ->post(route('dashboard.staff.training-requests.notes.store', $request), [
                'body' => 'A public one.',
                'visibility' => TrainingNoteVisibility::PublicNote->value,
            ]);

        $request->refresh();
        $this->assertStringContainsString('A public one.', (string) $request->public_observations);
        $this->assertNull($request->internal_observations);
    }

    #[Test]
    public function notes_can_still_be_added_to_a_final_request(): void
    {
        $coordinator = User::factory()->trainingCoordinator()->create();
        $request = TrainingRequest::factory()->cancelled()->create();

        $this->actingAs($coordinator)
            ->post(route('dashboard.staff.training-requests.notes.store', $request), [
                'body' => 'Closed out after the trainee withdrew.',
                'visibility' => TrainingNoteVisibility::InternalNote->value,
            ])
            ->assertRedirect();

        $request->refresh();
        $this->assertStringContainsString('Closed out after the trainee withdrew.', (string) $request->internal_observations);
    }

    #[Test]
    public function the_note_body_is_required(): void
    {
        $coordinator = User::factory()->trainingCoordinator()->create();
        $request = TrainingRequest::factory()->create();

        $this->actingAs($coordinator)
            ->post(route('dashboard.staff.training-requests.notes.store', $request), [
                'visibility' => TrainingNoteVisibility::InternalNote->value,
            ])
            ->assertSessionHasErrors('body');
    }
}
