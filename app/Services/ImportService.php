<?php

namespace App\Services;

use App\Imports\PollingStationImport;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ImportService
{
    /**
     * Import polling stations from an uploaded Excel file.
     *
     * @return array{imported: int, skipped: int, errors: list<string>}
     */
    public function importPollingStations(UploadedFile $file): array
    {
        $import = new PollingStationImport;

        Excel::import($import, $file);

        return [
            'imported' => $import->getImportedCount(),
            'skipped' => $import->getSkippedCount(),
            'errors' => $import->getErrors(),
        ];
    }
}
