<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOfficerRequest;
use App\Http\Requests\UpdateOfficerRequest;
use App\Http\Resources\OfficerResource;
use App\Models\Officer;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    use ApiResponse;

    /**
     * Display a paginated list of officers with filters.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $officers = Officer::query()
                ->with('district:id,name')
                ->withCount('assignments')
                ->when($request->district_id, fn (Builder $q, int $id) => $q->where('district_id', $id))
                ->when($request->role, fn (Builder $q, string $role) => $q->where('role', $role))
                ->when($request->status, fn (Builder $q, string $status) => $q->where('status', $status))
                ->when($request->search, function (Builder $q, string $search) {
                    $q->where(function (Builder $q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
                })
                ->latest()
                ->paginate($request->integer('per_page', 15));

            return $this->successResponse(
                OfficerResource::collection($officers),
                'Officers retrieved successfully',
                meta: [
                    'current_page' => $officers->currentPage(),
                    'last_page' => $officers->lastPage(),
                    'per_page' => $officers->perPage(),
                    'total' => $officers->total(),
                ],
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to retrieve officers', 500);
        }
    }

    /**
     * Store a newly created officer.
     */
    public function store(StoreOfficerRequest $request): JsonResponse
    {
        try {
            $officer = Officer::create($request->validated());

            return $this->successResponse(
                new OfficerResource($officer->load('district:id,name')),
                'Officer created successfully',
                201,
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to create officer', 500);
        }
    }

    /**
     * Display the specified officer.
     */
    public function show(Officer $officer): JsonResponse
    {
        try {
            $officer->load('district:id,name')->loadCount('assignments');

            return $this->successResponse(
                new OfficerResource($officer),
                'Officer retrieved successfully',
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to retrieve officer', 500);
        }
    }

    /**
     * Update the specified officer.
     */
    public function update(UpdateOfficerRequest $request, Officer $officer): JsonResponse
    {
        try {
            $officer->update($request->validated());

            return $this->successResponse(
                new OfficerResource($officer->refresh()->load('district:id,name')),
                'Officer updated successfully',
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to update officer', 500);
        }
    }

    /**
     * Remove the specified officer.
     */
    public function destroy(Officer $officer): JsonResponse
    {
        try {
            $officer->delete();

            return $this->successResponse(null, 'Officer deleted successfully');
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to delete officer', 500);
        }
    }
}
