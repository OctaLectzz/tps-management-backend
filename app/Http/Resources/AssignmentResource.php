<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
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
            'officer' => [
                'id' => $this->officer?->id,
                'name' => $this->officer?->name,
            ],
            'role' => $this->role,
            'confirmation_status' => $this->confirmation_status,
            'notes' => $this->notes,
            'assigned_at' => $this->assigned_at,
            'confirmed_at' => $this->confirmed_at,
            'created_at' => $this->created_at,
        ];
    }
}
