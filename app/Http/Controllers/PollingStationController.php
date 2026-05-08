<?php

namespace App\Http\Controllers;

use App\Exports\PollingStationExport;
use App\Http\Requests\ImportPollingStationRequest;
use App\Http\Requests\StorePollingStationRequest;
use App\Http\Requests\UpdatePollingStationRequest;
use App\Http\Resources\PollingStationResource;
use App\Models\PollingStation;
use App\Services\ImportService;
use App\Services\PollingStationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PollingStationController extends Controller
{
    use ApiResponse;

    public function __construct(
        private PollingStationService $service,
        private ImportService $importService,
    ) {}

    /**
     * Display a paginated list of polling stations with filters.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $stations = $this->service->getFilteredList($request->only([
                'district_id', 'village_id', 'status', 'search', 'per_page',
            ]));

            return $this->successResponse(
                PollingStationResource::collection($stations),
                'Polling stations retrieved successfully',
                meta: [
                    'current_page' => $stations->currentPage(),
                    'last_page' => $stations->lastPage(),
                    'per_page' => $stations->perPage(),
                    'total' => $stations->total(),
                ],
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to retrieve polling stations', 500);
        }
    }

    /**
     * Store a newly created polling station.
     */
    public function store(StorePollingStationRequest $request): JsonResponse
    {
        try {
            $station = $this->service->create($request->validated());

            return $this->successResponse(
                new PollingStationResource($station->load(['district:id,name', 'village:id,name'])),
                'Polling station created successfully',
                201,
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to create polling station', 500);
        }
    }

    /**
     * Display the specified polling station with its officers and assignments.
     */
    public function show(PollingStation $pollingStation): JsonResponse
    {
        try {
            $pollingStation->load([
                'district:id,name',
                'village:id,name',
                'assignments.officer:id,name,phone,role',
                'voteResult',
            ])->loadCount('assignments');

            return $this->successResponse(
                new PollingStationResource($pollingStation),
                'Polling station retrieved successfully',
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to retrieve polling station', 500);
        }
    }

    /**
     * Update the specified polling station.
     */
    public function update(UpdatePollingStationRequest $request, PollingStation $pollingStation): JsonResponse
    {
        try {
            $station = $this->service->update($pollingStation, $request->validated());

            return $this->successResponse(
                new PollingStationResource($station->load(['district:id,name', 'village:id,name'])),
                'Polling station updated successfully',
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to update polling station', 500);
        }
    }

    /**
     * Soft delete the specified polling station.
     */
    public function destroy(PollingStation $pollingStation): JsonResponse
    {
        try {
            $this->service->delete($pollingStation);

            return $this->successResponse(null, 'Polling station deleted successfully');
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to delete polling station', 500);
        }
    }

    /**
     * Import polling stations from an Excel file.
     */
    public function import(ImportPollingStationRequest $request): JsonResponse
    {
        try {
            $summary = $this->importService->importPollingStations($request->file('file'));

            return $this->successResponse($summary, 'Import completed successfully');
        } catch (\Throwable $e) {
            return $this->errorResponse('Import failed: '.$e->getMessage(), 422);
        }
    }

    /**
     * Return all polling stations as GeoJSON-like data for Leaflet map.
     */
    public function mapData(Request $request): JsonResponse
    {
        try {
            $data = $this->service->getMapData($request->only(['district_id', 'status']));

            return $this->successResponse($data, 'Map data retrieved successfully');
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to retrieve map data', 500);
        }
    }

    /**
     * Export filtered polling stations as an Excel file.
     */
    public function export(Request $request): BinaryFileResponse|JsonResponse
    {
        try {
            return Excel::download(
                new PollingStationExport,
                'polling-stations-'.now()->format('Y-m-d').'.xlsx',
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to export polling stations', 500);
        }
    }
}
