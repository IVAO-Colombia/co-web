<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Middleware;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SetLocaleTest extends TestCase
{
    #[Test]
    public function locale_defaults_to_es_without_cookie(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertInertia(fn ($page) => $page->where('locale', 'es'));
    }

    #[Test]
    public function locale_is_set_from_cookie(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withUnencryptedCookie('locale', 'en')
            ->get(route('dashboard'))
            ->assertInertia(fn ($page) => $page->where('locale', 'en'));
    }

    #[Test]
    public function spanish_locale_is_set_from_cookie(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withUnencryptedCookie('locale', 'es')
            ->get(route('dashboard'))
            ->assertInertia(fn ($page) => $page->where('locale', 'es'));
    }

    #[Test]
    public function invalid_locale_cookie_falls_back_to_es(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withUnencryptedCookie('locale', 'fr')
            ->get(route('dashboard'))
            ->assertInertia(fn ($page) => $page->where('locale', 'es'));
    }
}
