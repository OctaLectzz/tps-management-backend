<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoteResultResource extends JsonResource
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
            'polling_station' => [
                'id' => $this->pollingStation?->id,
                'station_number' => $this->pollingStation?->station_number,
                'venue_name' => $this->pollingStation?->venue_name,
            ],
            'party_votes' => $this->party_votes,
            'total_votes' => $this->total_votes,
            'dpt' => $this->dpt,
            'voters_present' => $this->voters_present,
            'submitter' => [
                'id' => $this->submitter?->id,
                'name' => $this->submitter?->name,
            ],
            'submitted_at' => $this->submitted_at,
            'verified' => $this->verified,
            'created_at' => $this->created_at,
        ];
    }
}
