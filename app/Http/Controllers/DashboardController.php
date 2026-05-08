<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardResource;
use App\Services\DashboardService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    use ApiResponse;

    public function __construct(private DashboardService $service) {}

    /**
     * Display dashboard statistics.
     */
    public function index(): JsonResponse
    {
        try {
            $stats = $this->service->getStats();

            return $this->successResponse(
                new DashboardResource($stats),
                'Dashboard statistics retrieved successfully',
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to retrieve dashboard statistics', 500);
        }
    }
}
