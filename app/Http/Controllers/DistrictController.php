<?php

namespace App\Http\Controllers;

use App\Http\Resources\DistrictResource;
use App\Models\District;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class DistrictController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of all districts with village and station counts.
     */
    public function index(): JsonResponse
    {
        try {
            $districts = District::query()
                ->withCount(['villages', 'pollingStations'])
                ->orderBy('name')
                ->get();

            return $this->successResponse(
                DistrictResource::collection($districts),
                'Districts retrieved successfully',
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to retrieve districts', 500);
        }
    }

    /**
     * Display the specified district with its villages.
     */
    public function show(District $district): JsonResponse
    {
        try {
            $district->load(['villages' => fn ($q) => $q->withCount('pollingStations')->orderBy('name')])
                ->loadCount(['villages', 'pollingStations']);

            return $this->successResponse(
                new DistrictResource($district),
                'District retrieved successfully',
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to retrieve district', 500);
        }
    }
}
