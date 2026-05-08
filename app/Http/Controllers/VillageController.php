<?php

namespace App\Http\Controllers;

use App\Http\Resources\VillageResource;
use App\Models\Village;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VillageController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of villages, optionally filtered by district.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $villages = Village::query()
                ->with('district:id,name')
                ->withCount('pollingStations')
                ->when($request->district_id, fn (Builder $q, $id) => $q->where('district_id', $id))
                ->orderBy('name')
                ->get();

            return $this->successResponse(
                VillageResource::collection($villages),
                'Villages retrieved successfully',
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to retrieve villages', 500);
        }
    }

    /**
     * Display the specified village with station count.
     */
    public function show(Village $village): JsonResponse
    {
        try {
            $village->load('district:id,name')->loadCount('pollingStations');

            return $this->successResponse(
                new VillageResource($village),
                'Village retrieved successfully',
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to retrieve village', 500);
        }
    }
}
