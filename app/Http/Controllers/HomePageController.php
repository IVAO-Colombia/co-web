<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PagesComponents;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Response;

class HomePageController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return inertia(PagesComponents::LANDING_HOME->value, [
            'events' => Event::query()
                ->active()
                ->orderBy('starts_at')
                ->limit(6)
                ->get(),
        ]);
    }
}
