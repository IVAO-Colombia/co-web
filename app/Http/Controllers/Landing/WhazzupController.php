<?php

declare(strict_types=1);

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Services\Ivao\Ivao;
use Illuminate\Http\JsonResponse;

class WhazzupController extends Controller
{
    public function __invoke(Ivao $ivaoService): JsonResponse
    {
        $data = $ivaoService->getWhazzupFlights();

        return response()->json([
            'success' => true,
            'flights' => $data['flights'],
            'count' => count($data['flights']),
            'last_updated' => $data['last_updated'],
        ]);
    }
}
