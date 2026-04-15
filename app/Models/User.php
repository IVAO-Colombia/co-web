<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

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
        ];
    }
}
