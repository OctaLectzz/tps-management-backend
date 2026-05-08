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
     * @param  array{district_id?: int, village_id?: int, status?: string}  $filters
     */
    public function __construct(private array $filters = []) {}

    /**
     * Build the query for the export.
     */
    public function query(): Builder
    {
        return PollingStation::query()
            ->with(['district:id,name', 'village:id,name'])
            ->withCount('assignments')
            ->when($this->filters['district_id'] ?? null, fn (Builder $q, int $id) => $q->where('district_id', $id))
            ->when($this->filters['village_id'] ?? null, fn (Builder $q, int $id) => $q->where('village_id', $id))
            ->when($this->filters['status'] ?? null, fn (Builder $q, string $s) => $q->where('status', $s))
            ->latest();
    }

    /**
     * Define the column headings.
     *
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'ID',
            'District',
            'Village',
            'Station Number',
            'Venue Name',
            'Address',
            'Latitude',
            'Longitude',
            'Status',
            'Officers Assigned',
            'Notes',
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
            $station->assignments_count,
            $station->notes,
        ];
    }
}
