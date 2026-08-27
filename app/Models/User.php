<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\ATCRating;
use App\Enums\PilotRating;
use Carbon\CarbonImmutable;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $vid
 * @property string|null $country_id
 * @property string|null $division_id
 * @property string|null $language_id
 * @property int|null $network_rating
 * @property ATCRating|null $atc_rating
 * @property PilotRating|null $pilot_rating
 * @property array<array-key, mixed>|null $raw_data
 * @property string|null $remember_token
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, AtcSlot> $atcSlots
 * @property-read int|null $atc_slots_count
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read UserOAuthToken|null $oauthToken
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection<int, PilotSlot> $pilotSlots
 * @property-read int|null $pilot_slots_count
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, TrainingRequest> $trainingRequests
 * @property-read int|null $training_requests_count
 * @property-read Collection<int, TrainingRequest> $assignedTrainings
 * @property-read int|null $assigned_trainings_count
 *
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User assignableToTrainings()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, ?string $guard = null, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, ?string $guard = null)
 *
 * @mixin \Eloquent
 */
#[Hidden('raw_data')]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles,  Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array{
     *  raw_data: 'json',
     * }
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'raw_data' => 'json',
            'atc_rating' => ATCRating::class,
            'pilot_rating' => PilotRating::class,
        ];
    }

    /** @return HasOne<UserOAuthToken, $this> */
    public function oauthToken(): HasOne
    {
        return $this->hasOne(UserOAuthToken::class);
    }

    /** @return HasMany<AtcSlot, $this> */
    public function atcSlots(): HasMany
    {
        return $this->hasMany(AtcSlot::class, 'atc_id');
    }

    /** @return HasMany<PilotSlot, $this> */
    public function pilotSlots(): HasMany
    {
        return $this->hasMany(PilotSlot::class, 'pilot_id');
    }

    /** @return HasMany<TrainingRequest, $this> */
    public function trainingRequests(): HasMany
    {
        return $this->hasMany(TrainingRequest::class, 'trainee_id');
    }

    /** @return HasMany<TrainingRequest, $this> */
    public function assignedTrainings(): HasMany
    {
        return $this->hasMany(TrainingRequest::class, 'trainer_id');
    }

    /**
     * Staff members who may be assigned as a trainer on a training request.
     *
     * @param  Builder<static>  $query
     */
    #[Scope]
    protected function assignableToTrainings(Builder $query): void
    {
        $query->permission(\App\Enums\Permission::BE_ASSIGNED_TO_TRAININGS->value);
    }

    /**
     * Online seconds from the user's last IVAO profile snapshot.
     * Null for a type the snapshot does not carry (including when there is no snapshot at all).
     *
     * @return array{pilot: ?int, atc: ?int, staff: ?int}
     */
    public function onlineHours(): array
    {
        $result = ['pilot' => null, 'atc' => null, 'staff' => null];

        /** @var array<int, array{type?: string, hours: int}> $hoursData */
        $hoursData = $this->raw_data['hours'] ?? [];

        foreach ($hoursData as $entry) {
            $type = $entry['type'] ?? null;

            if ($type !== null && array_key_exists($type, $result)) {
                $result[$type] = (int) $entry['hours'];
            }
        }

        return $result;
    }
}
