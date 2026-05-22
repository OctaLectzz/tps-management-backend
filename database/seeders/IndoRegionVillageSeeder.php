<?php

namespace Database\Seeders;

use AzisHapidin\IndoRegion\RawDataGetter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IndoRegionVillageSeeder extends Seeder
{
    public function run()
    {
        $villages = collect(RawDataGetter::getVillages())
            ->filter(fn ($v) => Str::startsWith((string) $v['district_id'], '3311'))
            ->map(fn ($v) => [
                'id' => (string) $v['id'],
                'district_id' => (string) $v['district_id'],
                'name' => trim((string) $v['name']),
            ])
            ->values()
            ->toArray();

        foreach (array_chunk($villages, 1000) as $chunk) {
            DB::table('villages')->upsert(
                $chunk,
                ['id'],
                ['district_id', 'name']
            );
        }
    }
}
