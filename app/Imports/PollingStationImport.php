<?php

namespace App\Imports;

use App\Models\District;
use App\Models\PollingStation;
use App\Models\Village;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class PollingStationImport implements ToCollection
{
    private int $importedCount = 0;

    private int $skippedCount = 0;

    /** @var list<string> */
    private array $errors = [];

    /**
     * Process the imported collection of rows.
     *
     * Handles merged-cell carry-forward for district and village columns.
     * Expected columns: B = district, C/D = village, E = station_number, F = venue_name, G = address.
     */
    public function collection(Collection $rows): void
    {
        $currentDistrict = null;
        $currentVillage = null;

        foreach ($rows as $index => $row) {
            // Skip header rows (first few rows) and empty rows
            if ($index < 2 || $this->isEmptyRow($row)) {
                $this->skippedCount++;

                continue;
            }

            // Skip summary rows containing "JUMLAH"
            if ($this->isSummaryRow($row)) {
                $this->skippedCount++;

                continue;
            }

            // Carry-forward district from merged cells
            $districtName = $this->cleanValue($row[1] ?? null);
            if (! empty($districtName)) {
                $currentDistrict = $districtName;
            }

            // Carry-forward village from merged cells (column C or D)
            $villageName = $this->cleanValue($row[2] ?? $row[3] ?? null);
            if (! empty($villageName)) {
                $currentVillage = $villageName;
            }

            $stationNumber = $this->cleanValue($row[4] ?? null);
            $venueName = $this->cleanValue($row[5] ?? null);
            $address = $this->cleanValue($row[6] ?? null);

            // Skip if essential data is missing
            if (empty($currentDistrict) || empty($currentVillage) || empty($stationNumber)) {
                $this->skippedCount++;

                continue;
            }

            try {
                $district = District::firstOrCreate(
                    ['name' => Str::upper($currentDistrict)],
                    [
                        'code' => $this->generateDistrictCode($currentDistrict),
                        'name' => Str::upper($currentDistrict),
                    ],
                );

                $village = Village::firstOrCreate(
                    ['name' => Str::upper($currentVillage), 'district_id' => $district->id],
                    [
                        'code' => $this->generateVillageCode($district, $currentVillage),
                        'district_id' => $district->id,
                        'name' => Str::upper($currentVillage),
                    ],
                );

                PollingStation::firstOrCreate(
                    [
                        'village_id' => $village->id,
                        'station_number' => (int) $stationNumber,
                    ],
                    [
                        'village_id' => $village->id,
                        'district_id' => $district->id,
                        'station_number' => (int) $stationNumber,
                        'venue_name' => $venueName ?? 'TPS '.$stationNumber,
                        'address' => $address ?? '-',
                    ],
                );

                $this->importedCount++;
            } catch (\Throwable $e) {
                $this->errors[] = "Row {$index}: {$e->getMessage()}";
                $this->skippedCount++;
            }
        }
    }

    /**
     * Get the number of successfully imported records.
     */
    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    /**
     * Get the number of skipped rows.
     */
    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }

    /**
     * Get any errors encountered during import.
     *
     * @return list<string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Check if a row is empty (all cells null or empty).
     */
    private function isEmptyRow(Collection $row): bool
    {
        return $row->filter(fn ($cell) => ! empty(trim((string) $cell)))->isEmpty();
    }

    /**
     * Check if a row is a summary row (contains "JUMLAH").
     */
    private function isSummaryRow(Collection $row): bool
    {
        return $row->contains(fn ($cell) => Str::contains(Str::upper((string) $cell), 'JUMLAH'));
    }

    /**
     * Clean a cell value by trimming whitespace.
     */
    private function cleanValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $cleaned = trim((string) $value);

        return $cleaned === '' ? null : $cleaned;
    }

    /**
     * Generate a district code from the district name.
     */
    private function generateDistrictCode(string $name): string
    {
        $existing = District::count();

        return sprintf('33.11.%02d', $existing + 1);
    }

    /**
     * Generate a village code from the district and village name.
     */
    private function generateVillageCode(District $district, string $name): string
    {
        $existing = Village::where('district_id', $district->id)->count();

        return sprintf('%s.%04d', $district->code, $existing + 1);
    }
}
