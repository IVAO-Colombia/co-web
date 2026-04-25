<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Socialite\Facades\Socialite;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use SocialiteProviders\Ivao\Provider;
use SocialiteProviders\Manager\OAuth2\User as SocialiteUser;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use WithFaker;

    #[Test]
    public function it_redirects_to_ivao_sso(): void
    {
        $this->get(route('auth.redirect'))
            ->assertRedirectContains('ivao.aero');
    }

    #[Test]
    public function it_stores_the_previous_url_as_intended_before_redirecting(): void
    {
        $previousUrl = route('dashboard');

        $this->from($previousUrl)
            ->get(route('auth.redirect'));

        $this->assertEquals($previousUrl, session('url.intended'));
    }

    #[Test]
    public function it_stores_an_external_url_as_intended_before_redirecting(): void
    {
        $externalUrl = 'http://co-web.test/some/page';

        $this->from($externalUrl)
            ->get(route('auth.redirect'));

        $this->assertEquals($externalUrl, session('url.intended'));
    }

    #[Test]
    public function a_user_successfully_registers_in_the_division_site_using_sso(): void
    {
        $ivaoUser = $this->mockSocialiteIvao();
        $this->assertDatabaseCount('users', 0);

        $this->get(route('auth.callback', 'ivao'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount(User::class, 1);
        $this->assertEquals($ivaoUser['id'], auth()->user()->vid);
        $this->assertDatabaseHas('users', [
            'name' => "{$ivaoUser['firstName']} {$ivaoUser['lastName']}",
            'email' => $ivaoUser['email'],
            'division_id' => $ivaoUser['divisionId'],
            'pilot_rating' => $ivaoUser['rating']['pilotRating']['id'],
            'atc_rating' => $ivaoUser['rating']['atcRating']['id'],
            'raw_data' => json_encode($ivaoUser),
        ]);
    }

    #[Test]
    public function it_assigns_roles_from_ivao_staff_positions_on_registration(): void
    {
        $this->mockSocialiteIvao();

        $this->get(route('auth.callback', 'ivao'));

        $this->assertTrue(auth()->user()->hasRole(Role::WMA->value));
    }

    #[Test]
    public function it_syncs_roles_from_ivao_staff_positions_on_login(): void
    {
        $ivaoUser = $this->mockSocialiteIvao();
        User::factory()->create([
            'vid' => $ivaoUser['id'],
            'email' => $ivaoUser['email'],
        ]);

        $this->get(route('auth.callback', 'ivao'));

        $this->assertTrue(auth()->user()->hasRole(Role::WMA->value));
    }

    #[Test]
    public function it_doesnt_registers_a_new_user_if_already_exists(): void
    {
        $ivaoUser = $this->mockSocialiteIvao();
        User::factory()->create([
            'name' => "{$ivaoUser['firstName']} {$ivaoUser['lastName']}",
            'email' => $ivaoUser['email'],
            'vid' => $ivaoUser['id'],
            'division_id' => $ivaoUser['divisionId'],
            'pilot_rating' => $ivaoUser['rating']['pilotRating']['id'],
            'atc_rating' => $ivaoUser['rating']['atcRating']['id'],
        ]);

        $this->assertDatabaseCount('users', 1);

        $this->get(route('auth.callback', 'ivao'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasNoErrors();

        $this->assertEquals($ivaoUser['id'], auth()->user()->vid);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'name' => "{$ivaoUser['firstName']} {$ivaoUser['lastName']}",
            'email' => $ivaoUser['email'],
            'division_id' => $ivaoUser['divisionId'],
            'pilot_rating' => $ivaoUser['rating']['pilotRating']['id'],
            'atc_rating' => $ivaoUser['rating']['atcRating']['id'],
        ]);
    }

    private function mockSocialiteIvao(): array
    {
        $ivaoUser = $this->getIvaoUser();
        $token = $this->faker->sha256();
        $refreshToken = $this->faker->sha256();

        $socialiteUser = $this->mock(SocialiteUser::class, function (MockInterface&SocialiteUser $mock) use ($ivaoUser, $token, $refreshToken): void {
            $mock->id = $ivaoUser['id'];
            $mock->nickname = $ivaoUser['publicNickname'];
            $mock->name = "{$ivaoUser['firstName']} {$ivaoUser['lastName']}";
            $mock->email = $ivaoUser['email'];
            $mock->avatar = null;
            $mock->token = $token;
            $mock->refreshToken = $refreshToken;
            $mock->expiresIn = 1800;
            $mock->approvedScopes = ['email', 'profile'];
            $mock->user = $ivaoUser;
            $mock->attributes = [
                'id' => $ivaoUser['id'],
                'name' => "{$ivaoUser['firstName']} {$ivaoUser['lastName']}",
                'email' => $ivaoUser['email'],
                'nickname' => $ivaoUser['publicNickname'],
                'division' => $ivaoUser['divisionId'],
                'atc_rating' => $ivaoUser['rating']['atcRating']['id'],
                'pilot_rating' => $ivaoUser['rating']['pilotRating']['id'],
            ];
            $mock->accessTokenResponseBody = [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => 1800,
                'refresh_token' => $refreshToken,
                'scope' => 'email profile',
            ];
        });

        $socialiteProvider = $this->mock(Provider::class, fn (MockInterface $mock) => $mock
            ->shouldReceive('user')
            ->andReturn($socialiteUser)
        );

        Socialite::shouldReceive('driver')->with('ivao')->andReturn($socialiteProvider);

        return $ivaoUser;
    }

    private function getIvaoUser(): array
    {
        return [
            'id' => $id = $this->faker->numberBetween(100000, 999999),
            'firstName' => $firstName = $this->faker->firstName(),
            'lastName' => $this->faker->lastName(),
            'centerId' => null,
            'countryId' => $countryId = $this->faker->countryCode(),
            'createdAt' => $this->faker->dateTime()->format('Y-m-d\TH:i:s.000\Z'),
            'divisionId' => $countryId,
            'isStaff' => $this->faker->boolean(),
            'isSupervisor' => false,
            'languageId' => $this->faker->languageCode(),
            'email' => $this->faker->safeEmail(),
            'rating' => [
                'isPilot' => true,
                'isAtc' => true,
                'pilotRating' => [
                    'id' => $this->faker->randomDigitNotZero(),
                    'name' => $this->faker->words(3, true),
                    'shortName' => $this->faker->lexify('FS?'),
                    'description' => $this->faker->sentence(),
                ],
                'atcRating' => [
                    'id' => $this->faker->randomDigitNotZero(),
                    'name' => $this->faker->words(3, true),
                    'shortName' => $this->faker->lexify('AS?'),
                    'description' => $this->faker->sentence(),
                ],
                'networkRating' => [
                    'id' => $this->faker->randomDigitNotZero(),
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->sentence(),
                ],
            ],
            'gcas' => [],
            'hours' => [
                ['type' => 'pilot', 'hours' => $this->faker->numberBetween(0, 9999)],
                ['type' => 'atc', 'hours' => $this->faker->numberBetween(0, 9999)],
                ['type' => 'staff', 'hours' => $this->faker->numberBetween(0, 9999)],
            ],
            'userStaffPositions' => [
                [
                    'id' => "{$countryId}-WMA2",
                    'staffPositionId' => '-WMA2',
                    'divisionId' => $countryId,
                    'centerId' => null,
                    'connectAs' => "{$countryId}-WMA2",
                    'onTrial' => true,
                    'description' => null,
                    'staffPosition' => [],
                ],
            ],
            'userStaffDetails' => [
                'email' => $this->faker->userName(),
                'note' => null,
                'description' => null,
                'remark' => null,
            ],
            'prCreator' => null,
            'ownedVirtualAirlines' => [],
            'publicNickname' => "{$firstName} ({$id})",
        ];
    }
}
