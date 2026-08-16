<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    protected static bool $hasRun = false;

    public function boot(): void
    {
        if (! app()->runningUnitTests() || static::$hasRun) {
            return;
        }

        static::$hasRun = true;

        if (ParallelTesting::token()) {
            ParallelTesting::setUpTestDatabase(function (): void {
                Artisan::call('db:seed SpatieRolesAndPermissionsSeeder');
            });
        }
    }
}
