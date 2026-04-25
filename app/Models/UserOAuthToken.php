<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\UserOAuthTokenFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $access_token
 * @property string|null $refresh_token
 * @property CarbonImmutable|null $expires_at
 * @property array<array-key, mixed>|null $scopes
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\UserOAuthTokenFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOAuthToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOAuthToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOAuthToken query()
 * @mixin \Eloquent
 */
class UserOAuthToken extends Model
{
    /** @use HasFactory<UserOAuthTokenFactory> */
    use HasFactory;

    protected $table = 'user_oauth_tokens';

    /**
     * @return array{
     *   access_token: 'encrypted',
     *   refresh_token: 'encrypted',
     *   expires_at: 'immutable_datetime',
     *   scopes: 'array',
     * }
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'access_token' => 'encrypted',
            'refresh_token' => 'encrypted',
            'expires_at' => 'immutable_datetime',
            'scopes' => 'array',
        ];
    }

    /**
     * Returns true when the access token has expired or will expire within 60 seconds.
     */
    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->subSeconds(60)->isPast();
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
