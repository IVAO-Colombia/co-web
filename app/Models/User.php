<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        "id",
        "firstname",
        "lastname",
        "email",
        "rating",
        "ratingatc",
        "ratingpilot",
        "division",
        "country",
        "staff",
        "va_staff_ids",
        "va_member_ids",
        "password",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        "password",
        "remember_token",
        "two_factor_recovery_codes",
        "two_factor_secret",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        "email_verified_at" => "datetime",
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ["profile_photo_url"];

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        $name = trim(
            collect(explode(" ", $this->firstname))
                ->map(function ($segment) {
                    return mb_substr($segment, 0, 1);
                })
                ->join(" ")
        );

        return "https://ui-avatars.com/api/?name=" .
            urlencode($name) .
            "&color=7F9CF5&background=EBF4FF";
    }
}
