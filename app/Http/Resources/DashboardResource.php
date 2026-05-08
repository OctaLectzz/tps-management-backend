<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_tps' => $this['total_tps'],
            'active_tps' => $this['active_tps'],
            'covered_tps' => $this['covered_tps'],
            'total_officers' => $this['total_officers'],
            'assignment_completion_rate' => $this['assignment_completion_rate'],
            'by_district' => $this['by_district'],
        ];
    }
}
