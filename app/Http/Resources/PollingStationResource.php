<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PollingStationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'station_number' => $this->station_number,
            'venue_name' => $this->venue_name,
            'address' => $this->address,
            'district' => [
                'id' => $this->district?->id,
                'name' => $this->district?->name,
            ],
            'village' => [
                'id' => $this->village?->id,
                'name' => $this->village?->name,
            ],
            'status' => $this->status,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'notes' => $this->notes,
            'officer_count' => $this->whenCounted('assignments'),
            'assignments' => AssignmentResource::collection($this->whenLoaded('assignments')),
            'vote_result' => new VoteResultResource($this->whenLoaded('voteResult')),
            'created_at' => $this->created_at,
        ];
    }
}
