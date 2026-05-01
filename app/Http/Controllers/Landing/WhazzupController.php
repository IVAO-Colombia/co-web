<?php

declare(strict_types=1);

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Services\Ivao\Ivao;
use Illuminate\Http\JsonResponse;

class WhazzupController extends Controller
{
    public function __construct(private readonly Ivao $ivaoService){}

    public function index() : JsonResponse
    {
        $flights = $this->ivaoService->getWhazzupFlights();

        return response()->json([
            'success' => true,
            'flights' => $flights,
            'count' => count($flights),
            'cached_at' => now()->toIso8601String(),
        ]);
    }
}
