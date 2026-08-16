<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Exceptions\AtcReauthRequiredException;
use App\Models\UserOAuthToken;
use App\Services\Ivao\Ivao;

class RefreshOAuthToken
{
    public function __construct(private readonly Ivao $ivao) {}

    /**
     * @throws AtcReauthRequiredException
     */
    public function handle(UserOAuthToken $token): UserOAuthToken
    {
        throw_if($token->refresh_token === null, AtcReauthRequiredException::class);

        $response = $this->ivao->refreshAccessToken($token->refresh_token);

        throw_if($response->failed(), AtcReauthRequiredException::class);

        /** @var array{access_token: string, refresh_token?: string, expires_in?: int} $data */
        $data = $response->json();

        $token->update([
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'] ?? $token->refresh_token,
            'expires_at' => isset($data['expires_in']) ? now()->addSeconds($data['expires_in']) : null,
        ]);

        return $token->refresh();
    }
}
