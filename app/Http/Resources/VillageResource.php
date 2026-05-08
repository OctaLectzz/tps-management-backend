<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VillageResource extends JsonResource
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
            'name' => $this->name,
            'district' => [
                'id' => $this->district?->id,
                'name' => $this->district?->name,
            ],
            'polling_stations_count' => $this->whenCounted('pollingStations'),
        ];
    }
}
