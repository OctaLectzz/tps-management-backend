<?php

namespace App\Exports;

use App\Models\PollingStation;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PollingStationExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * Build the query for the export.
     */
    public function query(): Builder
    {
        return PollingStation::query()
            ->with(['district:id,name', 'village:id,name'])
            ->withCount('assignments')
            ->latest();
    }

    public function headings(): array
    {
        return [
            'id',
            'district',
            'village',
            'station_number',
            'venue_name',
            'address',
            'latitude',
            'longitude',
            'status',
            'notes',
            'deleted_at',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * Map each row for the export.
     *
     * @param  PollingStation  $station
     * @return list<mixed>
     */
    public function map(mixed $station): array
    {
        return [
            $station->id,
            $station->district?->name,
            $station->village?->name,
            $station->station_number,
            $station->venue_name,
            $station->address,
            $station->latitude,
            $station->longitude,
            $station->status->value,
            $station->notes,
            $station->deleted_at?->format('Y-m-d H:i:s'),
            $station->created_at?->format('Y-m-d H:i:s'),
            $station->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
