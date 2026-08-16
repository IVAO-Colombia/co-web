<?php

declare(strict_types=1);

namespace Tests\Feature\Landing;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TrainingPageTest extends TestCase
{
    #[Test]
    public function guests_can_visit_the_public_training_page(): void
    {
        $this->get(route('home.training'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('landing/Training'));
    }
}
