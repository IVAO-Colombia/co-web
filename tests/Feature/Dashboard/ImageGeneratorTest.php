<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ImageGeneratorTest extends TestCase
{
    #[Test]
    public function guests_are_redirected_from_image_generator(): void
    {
        $this->get(route('dashboard.image-generator'))
            ->assertRedirect(route('auth.redirect'));
    }

    #[Test]
    public function authenticated_user_without_permission_cannot_access_image_generator(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard.image-generator'))
            ->assertForbidden();
    }

    #[Test]
    public function staff_user_can_access_image_generator(): void
    {
        $user = User::factory()->director()->create();

        $this->actingAs($user)
            ->get(route('dashboard.image-generator'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('dashboard/ImageGenerator')
            );
    }
}
