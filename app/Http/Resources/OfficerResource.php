<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfficerResource extends JsonResource
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
            'phone' => $this->phone,
            'email' => $this->email,
            'role' => $this->role,
            'district' => [
                'id' => $this->district?->id,
                'name' => $this->district?->name,
            ],
            'status' => $this->status,
            'assignments_count' => $this->whenCounted('assignments'),
            'created_at' => $this->created_at,
        ];
    }
}
