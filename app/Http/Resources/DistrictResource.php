<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistrictResource extends JsonResource
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
            'villages_count' => $this->whenCounted('villages'),
            'polling_stations_count' => $this->whenCounted('pollingStations'),
            'villages' => VillageResource::collection($this->whenLoaded('villages')),
        ];
    }
}
