<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoteResultRequest;
use App\Http\Requests\UpdateVoteResultRequest;
use App\Http\Resources\VoteResultResource;
use App\Models\District;
use App\Models\VoteResult;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoteResultController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of vote results with optional district aggregation.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            if ($request->boolean('aggregate')) {
                $aggregation = District::query()
                    ->with(['pollingStations' => function ($q) {
                        $q->has('voteResult')->with('voteResult');
                    }])
                    ->get()
                    ->map(function (District $district) {
                        $results = $district->pollingStations->pluck('voteResult')->filter();

                        return [
                            'district_id' => $district->id,
                            'district_name' => $district->name,
                            'total_stations' => $district->pollingStations->count(),
                            'stations_reported' => $results->count(),
                            'total_party_votes' => $results->sum('party_votes'),
                            'total_votes' => $results->sum('total_votes'),
                            'total_dpt' => $results->sum('dpt'),
                            'total_voters_present' => $results->sum('voters_present'),
                        ];
                    });

                return $this->successResponse($aggregation, 'Vote result aggregation retrieved successfully');
            }

            $results = VoteResult::query()
                ->with(['pollingStation:id,station_number,venue_name,district_id', 'submitter:id,name'])
                ->when($request->district_id, function ($q, $id) {
                    $q->whereHas('pollingStation', fn ($q) => $q->where('district_id', $id));
                })
                ->when($request->has('verified'), fn ($q) => $q->where('verified', $request->boolean('verified')))
                ->latest()
                ->paginate($request->integer('per_page', 15));

            return $this->successResponse(
                VoteResultResource::collection($results),
                'Vote results retrieved successfully',
                meta: [
                    'current_page' => $results->currentPage(),
                    'last_page' => $results->lastPage(),
                    'per_page' => $results->perPage(),
                    'total' => $results->total(),
                ],
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to retrieve vote results', 500);
        }
    }

    /**
     * Store a newly created vote result.
     */
    public function store(StoreVoteResultRequest $request): JsonResponse
    {
        try {
            $result = VoteResult::create([
                ...$request->validated(),
                'submitted_by' => $request->user()->id,
                'submitted_at' => now(),
            ]);

            return $this->successResponse(
                new VoteResultResource($result->load(['pollingStation:id,station_number,venue_name', 'submitter:id,name'])),
                'Vote result submitted successfully',
                201,
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to submit vote result', 500);
        }
    }

    /**
     * Display the specified vote result.
     */
    public function show(VoteResult $voteResult): JsonResponse
    {
        try {
            $voteResult->load(['pollingStation:id,station_number,venue_name', 'submitter:id,name']);

            return $this->successResponse(
                new VoteResultResource($voteResult),
                'Vote result retrieved successfully',
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to retrieve vote result', 500);
        }
    }

    /**
     * Update the specified vote result.
     */
    public function update(UpdateVoteResultRequest $request, VoteResult $voteResult): JsonResponse
    {
        try {
            $voteResult->update($request->validated());

            return $this->successResponse(
                new VoteResultResource($voteResult->refresh()->load(['pollingStation:id,station_number,venue_name', 'submitter:id,name'])),
                'Vote result updated successfully',
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to update vote result', 500);
        }
    }

    /**
     * Verify the specified vote result.
     */
    public function verify(VoteResult $voteResult): JsonResponse
    {
        try {
            $voteResult->update(['verified' => true]);

            return $this->successResponse(
                new VoteResultResource($voteResult->load(['pollingStation:id,station_number,venue_name', 'submitter:id,name'])),
                'Vote result verified successfully',
            );
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to verify vote result', 500);
        }
    }
}
