<?php

namespace App\Imports;

use App\Enums\PollingStationStatus;
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

    private array $errors = [];

    // Cache untuk mempercepat proses & mencegah N+1 Query
    private array $districtCache = [];

    private array $villageCache = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            // Skip header (index 0)
            if ($index === 0 || $this->isEmptyRow($row)) {
                continue;
            }

            // Extract values based on image columns
            $districtName = Str::upper($this->cleanValue($row[1] ?? ''));
            $villageName = $this->normalizeVillageName($this->cleanValue($row[2] ?? ''));
            $stationNumber = (int) $this->cleanValue($row[3] ?? 0);
            $venueName = $this->cleanValue($row[4] ?? '');
            $address = $this->cleanValue($row[5] ?? '');
            $latitude = $this->cleanValue($row[6] ?? null);
            $longitude = $this->cleanValue($row[7] ?? null);
            $status = Str::lower($this->cleanValue($row[8] ?? 'active'));
            $notes = $this->cleanValue($row[9] ?? null);

            // Basic validation
            if (empty($districtName) || empty($villageName) || $stationNumber <= 0) {
                $this->skippedCount++;

                continue;
            }

            try {
                // Resolve IDs (with caching)
                $districtId = $this->resolveDistrict($districtName);
                $villageId = $this->resolveVillage($villageName, $districtId);

                // Insert or Update
                PollingStation::updateOrCreate(
                    [
                        'village_id' => $villageId,
                        'station_number' => $stationNumber,
                    ],
                    [
                        'district_id' => $districtId,
                        'venue_name' => $venueName ?: 'TPS '.$stationNumber,
                        'address' => $address ?: '-',
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'status' => PollingStationStatus::tryFrom($status) ?? PollingStationStatus::Active,
                        'notes' => $notes,
                    ]
                );

                $this->importedCount++;
            } catch (\Throwable $e) {
                $this->errors[] = "Row {$index} (TPS {$stationNumber}): {$e->getMessage()}";
                $this->skippedCount++;
            }
        }
    }

    private function resolveDistrict(string $name): string
    {
        // Gunakan cache memory untuk memotong Query ke database
        if (isset($this->districtCache[$name])) {
            return $this->districtCache[$name];
        }

        $district = District::where('name', $name)->first();

        if (! $district) {
            // Asumsi format 'id' di schema anda adalah char(7) (misal: 3311001)
            $count = District::count() + 1;
            $generatedId = sprintf('3311%03d', $count);

            $district = District::create([
                'id' => $generatedId,
                'name' => $name,
            ]);
        }

        $this->districtCache[$name] = $district->id;

        return $district->id;
    }

    private function resolveVillage(string $name, string $districtId): string
    {
        $cacheKey = $districtId.'_'.$name;

        if (isset($this->villageCache[$cacheKey])) {
            return $this->villageCache[$cacheKey];
        }

        $village = Village::where('district_id', $districtId)
            ->whereRaw('UPPER(TRIM(name)) = ?', [$name])
            ->first();

        if (! $village) {
            throw new \Exception("Desa/Kelurahan tidak ditemukan: {$name} di district_id {$districtId}");
        }

        $this->villageCache[$cacheKey] = $village->id;

        return $village->id;
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function isEmptyRow(Collection $row): bool
    {
        return $row->filter(fn ($cell) => trim((string) $cell) !== '')->isEmpty();
    }

    private function cleanValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $cleaned = trim((string) $value);

        return $cleaned === '' ? null : $cleaned;
    }

    private array $villageAliases = [
        'JATISOBOO' => 'JATISOBO',
        'BAKI PANDEYAN' => 'BAKIPANDEYAN',
    ];

    private function normalizeVillageName(?string $name): string
    {
        $name = Str::upper(trim((string) $name));

        return $this->villageAliases[$name] ?? $name;
    }
}
