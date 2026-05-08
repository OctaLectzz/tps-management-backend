<?php

namespace App\Http\Controllers;

use App\Enums\ConfirmationStatus;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    use ApiResponse;

    /**
     * Display a paginated list of assignments.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $assignments = Assignment::query()
                ->with(['pollingStation:id,station_number,venue_name', 'officer:id,name'])
                ->when($request->polling_station_id, fn ($q, $id) => $q->where('polling_station_id', $id))
                ->when($request->officer_id, fn ($q, $id) => $q->where('officer_id', $id))
                ->when($request->confirmation_status, fn ($q, $s) => $q->where('confirmation_status', $s))
                ->latest()
                ->paginate($request->integer('per_page', 15));

            return $this->successResponse(
                AssignmentResource::collection($assignments),
                'Assignments retrieved successfully',
                meta: [
                    'current_page' => $assignments->currentPage(),
                    'last_page' => $assignments->lastPage(),
                    'per_page' => $assignments->perPage(),
                    'total' => $assignments->total(),
                ],
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to retrieve assignments', 500);
        }
    }

    /**
     * Store a newly created assignment.
     */
    public function store(StoreAssignmentRequest $request): JsonResponse
    {
        try {
            $assignment = Assignment::create($request->validated());

            return $this->successResponse(
                new AssignmentResource($assignment->load(['pollingStation:id,station_number,venue_name', 'officer:id,name'])),
                'Assignment created successfully',
                201,
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to create assignment', 500);
        }
    }

    /**
     * Display the specified assignment.
     */
    public function show(Assignment $assignment): JsonResponse
    {
        try {
            $assignment->load(['pollingStation:id,station_number,venue_name', 'officer:id,name']);

            return $this->successResponse(
                new AssignmentResource($assignment),
                'Assignment retrieved successfully',
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to retrieve assignment', 500);
        }
    }

    /**
     * Update the specified assignment.
     */
    public function update(UpdateAssignmentRequest $request, Assignment $assignment): JsonResponse
    {
        try {
            $assignment->update($request->validated());

            return $this->successResponse(
                new AssignmentResource($assignment->refresh()->load(['pollingStation:id,station_number,venue_name', 'officer:id,name'])),
                'Assignment updated successfully',
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to update assignment', 500);
        }
    }

    /**
     * Remove the specified assignment.
     */
    public function destroy(Assignment $assignment): JsonResponse
    {
        try {
            $assignment->delete();

            return $this->successResponse(null, 'Assignment deleted successfully');
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to delete assignment', 500);
        }
    }

    /**
     * Confirm an assignment (PATCH).
     */
    public function confirm(Assignment $assignment): JsonResponse
    {
        try {
            $assignment->update([
                'confirmation_status' => ConfirmationStatus::Confirmed,
                'confirmed_at' => now(),
            ]);

            return $this->successResponse(
                new AssignmentResource($assignment->refresh()->load(['pollingStation:id,station_number,venue_name', 'officer:id,name'])),
                'Assignment confirmed successfully',
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to confirm assignment', 500);
        }
    }
}
