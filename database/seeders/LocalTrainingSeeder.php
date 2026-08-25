<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AtcTraining;
use App\Enums\PilotTraining;
use App\Enums\Role;
use App\Enums\TrainingNoteVisibility;
use App\Enums\TrainingRequestStatus;
use App\Enums\TrainingRequestType;
use App\Models\TrainingRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Local-only demo data: staff members that can be assigned as trainers,
 * regular members, and a spread of training requests covering every status,
 * both types, assigned and unassigned.
 *
 * Run with: php artisan db:seed --class=LocalTrainingSeeder
 */
class LocalTrainingSeeder extends Seeder
{
    /**
     * Staff members created by this seeder, keyed by VID.
     *
     * @var array<int, array{name: string, role: Role}>
     */
    private const STAFF = [
        500001 => ['name' => 'Diana Directora', 'role' => Role::DIR],
        500002 => ['name' => 'Tomás Coordinador', 'role' => Role::TC],
        500003 => ['name' => 'Tania Asistente', 'role' => Role::TAC],
        500004 => ['name' => 'Andrés Asesor', 'role' => Role::TA],
        500005 => ['name' => 'Teo Entrenador', 'role' => Role::T0],
        500006 => ['name' => 'Tina Entrenadora', 'role' => Role::T0],
        // View-only staff: sees training requests but cannot be assigned.
        500007 => ['name' => 'Mario Membresías', 'role' => Role::MC],
    ];

    /**
     * @var list<string>
     */
    private const REQUEST_OBSERVATIONS = [
        'Tengo disponibilidad entre semana después de las 23z.',
        'Prefiero los fines de semana en la mañana, zona horaria de Bogotá.',
        'Ya revisé el material y quisiera agendar lo antes posible.',
        'Es mi primer entrenamiento en la división, agradezco la guía.',
        'Solicito reprogramar, no pude asistir a la sesión anterior.',
        'Disponible cualquier día después de las 00z.',
    ];

    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            $this->command->error('LocalTrainingSeeder only runs in the local environment.');

            return;
        }

        $this->call(SpatieRolesAndPermissionsSeeder::class);

        $staff = $this->createStaff();
        $trainees = $this->createTrainees();

        $director = $staff->get(500001);
        assert($director instanceof User);

        $this->createTrainingRequests(
            $director,
            $staff->only([500002, 500003, 500004, 500005, 500006])->values(),
            $trainees,
        );

        $this->command->info('Seeded '.$staff->count().' staff members, '.$trainees->count().' members and their training requests.');
    }

    /**
     * @return Collection<int, User>
     */
    private function createStaff(): Collection
    {
        return collect(self::STAFF)->map(function (array $staff, int $vid): User {
            $user = $this->firstOrCreateUser($vid, $staff['name']);
            $user->syncRoles([$staff['role']->value]);

            return $user;
        });
    }

    /**
     * @return Collection<int, User>
     */
    private function createTrainees(): Collection
    {
        return collect(range(1, 14))
            ->map(fn (int $index): User => $this->firstOrCreateUser(
                510000 + $index,
                fake()->name(),
            ))
            ->values();
    }

    private function firstOrCreateUser(int $vid, string $name): User
    {
        $existing = User::query()->firstWhere('vid', $vid);

        if ($existing instanceof User) {
            return $existing;
        }

        return User::factory()->create([
            'name' => $name,
            'vid' => $vid,
            'email' => Str::slug($name, '.').'.'.$vid.'@example.test',
            'country_id' => 'CO',
            'division_id' => 'CO',
            'language_id' => 'es',
        ]);
    }

    /**
     * @param  Collection<int, User>  $trainers
     * @param  Collection<int, User>  $trainees
     */
    private function createTrainingRequests(User $director, Collection $trainers, Collection $trainees): void
    {
        /** @var list<array{type: TrainingRequestType, status: TrainingRequestStatus, count: int, assigned: bool}> $specs */
        $specs = [
            ['type' => TrainingRequestType::ATC, 'status' => TrainingRequestStatus::PENDING, 'count' => 8, 'assigned' => false],
            ['type' => TrainingRequestType::Pilot, 'status' => TrainingRequestStatus::PENDING, 'count' => 4, 'assigned' => false],
            ['type' => TrainingRequestType::ATC, 'status' => TrainingRequestStatus::PENDING, 'count' => 4, 'assigned' => true],
            ['type' => TrainingRequestType::Pilot, 'status' => TrainingRequestStatus::PENDING, 'count' => 2, 'assigned' => true],
            ['type' => TrainingRequestType::ATC, 'status' => TrainingRequestStatus::SCHEDULED, 'count' => 5, 'assigned' => true],
            ['type' => TrainingRequestType::Pilot, 'status' => TrainingRequestStatus::SCHEDULED, 'count' => 3, 'assigned' => true],
            ['type' => TrainingRequestType::ATC, 'status' => TrainingRequestStatus::COMPLETED, 'count' => 3, 'assigned' => true],
            ['type' => TrainingRequestType::Pilot, 'status' => TrainingRequestStatus::COMPLETED, 'count' => 2, 'assigned' => true],
            ['type' => TrainingRequestType::ATC, 'status' => TrainingRequestStatus::CANCELLED, 'count' => 2, 'assigned' => false],
            ['type' => TrainingRequestType::Pilot, 'status' => TrainingRequestStatus::CANCELLED, 'count' => 2, 'assigned' => true],
        ];

        foreach ($specs as $spec) {
            for ($i = 0; $i < $spec['count']; $i++) {
                $this->createTrainingRequest(
                    $director,
                    $spec['assigned'] ? $trainers->random() : null,
                    $trainees->random(),
                    $spec['type'],
                    $spec['status'],
                );
            }
        }

        $this->addReassignedRequest($director, $trainers, $trainees->random());
        $this->addRequestsAwaitingIvaoReminder($trainees);
    }

    private function createTrainingRequest(
        User $director,
        ?User $trainer,
        User $trainee,
        TrainingRequestType $type,
        TrainingRequestStatus $status,
    ): TrainingRequest {
        $trainingRequest = TrainingRequest::factory()->create([
            'type' => $type,
            'category' => $this->randomCategory($type),
            'status' => $status,
            'occurs_at' => match ($status) {
                TrainingRequestStatus::SCHEDULED => now()->addDays(fake()->numberBetween(1, 21))->setTime(fake()->numberBetween(0, 3), 0),
                TrainingRequestStatus::COMPLETED => now()->subDays(fake()->numberBetween(3, 60))->setTime(fake()->numberBetween(0, 3), 0),
                default => null,
            },
            'request_observations' => fake()->randomElement(self::REQUEST_OBSERVATIONS),
            'trainee_id' => $trainee->id,
            'created_at' => now()->subDays(fake()->numberBetween(1, 90)),
        ]);

        if ($trainer instanceof User) {
            $trainingRequest->assignTrainer($director, $trainer);
        }

        if ($status === TrainingRequestStatus::SCHEDULED && $trainer instanceof User) {
            $trainingRequest->appendNote($trainer, TrainingNoteVisibility::PublicNote, 'Nos vemos en el canal de entrenamiento 10 minutos antes.');
        }

        if ($status === TrainingRequestStatus::COMPLETED && $trainer instanceof User) {
            $trainingRequest->appendNote($trainer, TrainingNoteVisibility::PublicNote, 'Sesión completada, buen desempeño general.');
            $trainingRequest->appendNote($trainer, TrainingNoteVisibility::InternalNote, 'Repasar fraseología en la siguiente sesión.');
        }

        return $trainingRequest;
    }

    /**
     * A request that changed hands twice, so the assignment history has more
     * than one entry.
     *
     * @param  Collection<int, User>  $trainers
     */
    private function addReassignedRequest(User $director, Collection $trainers, User $trainee): void
    {
        $first = $trainers->first();
        $last = $trainers->last();
        assert($first instanceof User && $last instanceof User);

        $trainingRequest = $this->createTrainingRequest(
            $director,
            $first,
            $trainee,
            TrainingRequestType::ATC,
            TrainingRequestStatus::SCHEDULED,
        );

        $trainingRequest->assignTrainer($director, null);
        $trainingRequest->assignTrainer($director, $last);
    }

    /**
     * One request whose IVAO reminder is still on cooldown and one whose
     * cooldown has already elapsed.
     *
     * @param  Collection<int, User>  $trainees
     */
    private function addRequestsAwaitingIvaoReminder(Collection $trainees): void
    {
        $cooldownHours = (int) config('training.ivao_reminder_cooldown_hours');

        TrainingRequest::factory()->create([
            'type' => TrainingRequestType::ATC,
            'category' => $this->randomCategory(TrainingRequestType::ATC),
            'request_observations' => 'Ya la solicité en IVAO, quedo atento.',
            'trainee_id' => $trainees->random()->id,
            'ivao_reminder_sent_at' => now()->subHours($cooldownHours - 1),
        ]);

        TrainingRequest::factory()->create([
            'type' => TrainingRequestType::Pilot,
            'category' => $this->randomCategory(TrainingRequestType::Pilot),
            'request_observations' => 'No he recibido respuesta desde la última vez.',
            'trainee_id' => $trainees->random()->id,
            'ivao_reminder_sent_at' => now()->subHours($cooldownHours + 24),
        ]);
    }

    private function randomCategory(TrainingRequestType $type): string
    {
        $cases = $type === TrainingRequestType::ATC
            ? AtcTraining::cases()
            : PilotTraining::cases();

        $case = fake()->randomElement($cases);
        assert($case instanceof AtcTraining || $case instanceof PilotTraining);

        return $case->value;
    }
}
