<?php

namespace App\Services;

use App\Models\PollingStation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PollingStationService
{
    /**
     * Get a filtered, paginated list of polling stations.
     *
     * @param  array{district_id?: int, village_id?: int, status?: string, search?: string, per_page?: int}  $filters
     */
    public function getFilteredList(array $filters): LengthAwarePaginator
    {
        return PollingStation::query()
            ->with(['district:id,name', 'village:id,name'])
            ->withCount('assignments')
            ->when($filters['district_id'] ?? null, fn (Builder $q, int $id) => $q->byDistrict($id))
            ->when($filters['village_id'] ?? null, fn (Builder $q, int $id) => $q->where('village_id', $id))
            ->when($filters['status'] ?? null, fn (Builder $q, string $status) => $q->where('status', $status))
            ->when($filters['search'] ?? null, function (Builder $q, string $search) {
                $q->where(function (Builder $q) use ($search) {
                    $q->where('venue_name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create a new polling station.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): PollingStation
    {
        return PollingStation::create($data);
    }

    /**
     * Update an existing polling station.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(PollingStation $pollingStation, array $data): PollingStation
    {
        $pollingStation->update($data);

        return $pollingStation->refresh();
    }

    /**
     * Soft delete a polling station.
     */
    public function delete(PollingStation $pollingStation): bool
    {
        return (bool) $pollingStation->delete();
    }

    /**
     * Get all polling stations formatted for map display.
     *
     * @param  array{district_id?: int, status?: string}  $filters
     * @return Collection<int, array<string, mixed>>
     */
    public function getMapData(array $filters = []): Collection
    {
        return PollingStation::query()
            ->with(['district:id,name', 'village:id,name'])
            ->withCount('assignments')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->when($filters['district_id'] ?? null, fn (Builder $q, int $id) => $q->byDistrict($id))
            ->when($filters['status'] ?? null, fn (Builder $q, string $status) => $q->where('status', $status))
            ->get()
            ->map(fn (PollingStation $station) => [
                'id' => $station->id,
                'lat' => $station->latitude,
                'lng' => $station->longitude,
                'station_number' => $station->station_number,
                'venue_name' => $station->venue_name,
                'district' => $station->district?->name,
                'village' => $station->village?->name,
                'status' => $station->status->value,
                'officer_count' => $station->assignments_count,
            ]);
    }

    /**
     * Get filtered query builder for export.
     *
     * @param  array{district_id?: int, village_id?: int, status?: string}  $filters
     */
    public function getExportQuery(array $filters = []): Builder
    {
        return PollingStation::query()
            ->with(['district:id,name', 'village:id,name'])
            ->withCount('assignments')
            ->when($filters['district_id'] ?? null, fn (Builder $q, int $id) => $q->byDistrict($id))
            ->when($filters['village_id'] ?? null, fn (Builder $q, int $id) => $q->where('village_id', $id))
            ->when($filters['status'] ?? null, fn (Builder $q, string $status) => $q->where('status', $status))
            ->latest();
    }
}
