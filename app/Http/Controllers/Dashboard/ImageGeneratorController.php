<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Enums\PagesComponents;
use App\Enums\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Inertia\Response;

class ImageGeneratorController extends Controller
{
    public function index(): Response
    {
        Gate::authorize(Permission::GENERATE_EVENT_IMAGES);

        return inertia(PagesComponents::DASHBOARD_IMAGE_GENERATOR->value);
    }
}
