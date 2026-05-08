<?php

namespace App\Services;

use App\Enums\ConfirmationStatus;
use App\Models\Assignment;
use App\Models\District;
use App\Models\Officer;
use App\Models\PollingStation;
use Illuminate\Support\Collection;

class DashboardService
{
    /**
     * Get all dashboard statistics.
     *
     * @return array{
     *     total_tps: int,
     *     active_tps: int,
     *     covered_tps: int,
     *     total_officers: int,
     *     assignment_completion_rate: float,
     *     by_district: Collection<int, array<string, mixed>>
     * }
     */
    public function getStats(): array
    {
        $totalTps = PollingStation::count();
        $activeTps = PollingStation::active()->count();
        $coveredTps = PollingStation::has('assignments')->count();
        $totalOfficers = Officer::count();

        $totalAssignments = Assignment::count();
        $confirmedAssignments = Assignment::where('confirmation_status', ConfirmationStatus::Confirmed)->count();
        $assignmentCompletionRate = $totalAssignments > 0
            ? round(($confirmedAssignments / $totalAssignments) * 100, 2)
            : 0;

        $byDistrict = District::query()
            ->withCount([
                'pollingStations as total',
                'pollingStations as covered' => function ($query) {
                    $query->has('assignments');
                },
            ])
            ->withCount(['pollingStations as officers' => function ($query) {
                $query->withCount('assignments');
            }])
            ->get()
            ->map(function (District $district) {
                $officerCount = Assignment::whereHas('pollingStation', function ($q) use ($district) {
                    $q->where('district_id', $district->id);
                })->distinct('officer_id')->count('officer_id');

                return [
                    'name' => $district->name,
                    'total' => $district->total,
                    'covered' => $district->covered,
                    'officers' => $officerCount,
                ];
            });

        return [
            'total_tps' => $totalTps,
            'active_tps' => $activeTps,
            'covered_tps' => $coveredTps,
            'total_officers' => $totalOfficers,
            'assignment_completion_rate' => $assignmentCompletionRate,
            'by_district' => $byDistrict,
        ];
    }
}
