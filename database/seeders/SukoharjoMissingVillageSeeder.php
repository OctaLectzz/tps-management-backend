<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SukoharjoMissingVillageSeeder extends Seeder
{
    public function run(): void
    {
        $villages = [
            // 3311010 - Weru
            ['id' => '3311010009', 'district_id' => '3311010', 'name' => 'WERU'],

            // 3311020 - Bulu
            ['id' => '3311020006', 'district_id' => '3311020', 'name' => 'KARANGASEM'],
            ['id' => '3311020007', 'district_id' => '3311020', 'name' => 'BULU'],
            ['id' => '3311020008', 'district_id' => '3311020', 'name' => 'KUNDEN'],
            ['id' => '3311020009', 'district_id' => '3311020', 'name' => 'PURON'],
            ['id' => '3311020010', 'district_id' => '3311020', 'name' => 'MALANGAN'],
            ['id' => '3311020011', 'district_id' => '3311020', 'name' => 'LENGKING'],
            ['id' => '3311020012', 'district_id' => '3311020', 'name' => 'NGASINAN'],

            // 3311040 - Sukoharjo
            ['id' => '3311040001', 'district_id' => '3311040', 'name' => 'KENEP'],
            ['id' => '3311040011', 'district_id' => '3311040', 'name' => 'DUKUH'],

            // 3311050 - Nguter
            ['id' => '3311050002', 'district_id' => '3311050', 'name' => 'JANGGLENGAN'],
            ['id' => '3311050003', 'district_id' => '3311050', 'name' => 'SERUT'],
            ['id' => '3311050005', 'district_id' => '3311050', 'name' => 'CELEP'],
            ['id' => '3311050006', 'district_id' => '3311050', 'name' => 'PENGKOL'],
            ['id' => '3311050008', 'district_id' => '3311050', 'name' => 'PLESAN'],
            ['id' => '3311050010', 'district_id' => '3311050', 'name' => 'NGUTER'],
            ['id' => '3311050011', 'district_id' => '3311050', 'name' => 'BARAN'],
            ['id' => '3311050014', 'district_id' => '3311050', 'name' => 'TANJUNG'],
            ['id' => '3311050016', 'district_id' => '3311050', 'name' => 'KEPUH'],

            // 3311060 - Bendosari
            ['id' => '3311060003', 'district_id' => '3311060', 'name' => 'MULUR'],
            ['id' => '3311060006', 'district_id' => '3311060', 'name' => 'CABEYAN'],
            ['id' => '3311060009', 'district_id' => '3311060', 'name' => 'BENDOSARI'],

            // 3311070 - Polokarto
            ['id' => '3311070002', 'district_id' => '3311070', 'name' => 'KARANGWUNI'],
            ['id' => '3311070003', 'district_id' => '3311070', 'name' => 'BUGEL'],
            ['id' => '3311070010', 'district_id' => '3311070', 'name' => 'BULU'],
            ['id' => '3311070015', 'district_id' => '3311070', 'name' => 'JATISOBO'],

            // 3311080 - Mojolaban
            ['id' => '3311080009', 'district_id' => '3311080', 'name' => 'JOHO'],
            ['id' => '3311080010', 'district_id' => '3311080', 'name' => 'DEMAKAN'],
            ['id' => '3311080011', 'district_id' => '3311080', 'name' => 'DUKUH'],
            ['id' => '3311080012', 'district_id' => '3311080', 'name' => 'PLUMBON'],
            ['id' => '3311080013', 'district_id' => '3311080', 'name' => 'GADINGAN'],
            ['id' => '3311080014', 'district_id' => '3311080', 'name' => 'PALUR'],
            ['id' => '3311080015', 'district_id' => '3311080', 'name' => 'TRIYAGAN'],

            // 3311100 - Baki
            ['id' => '3311100006', 'district_id' => '3311100', 'name' => 'KUDU'],
            ['id' => '3311100008', 'district_id' => '3311100', 'name' => 'BAKIPANDEYAN'],
            ['id' => '3311100010', 'district_id' => '3311100', 'name' => 'DUWET'],

            // 3311110 - Gatak
            ['id' => '3311110005', 'district_id' => '3311110', 'name' => 'GENENG'],
            ['id' => '3311110010', 'district_id' => '3311110', 'name' => 'TEMPEL'],
        ];

        DB::table('villages')->upsert(
            $villages,
            ['id'],
            ['district_id', 'name']
        );
    }
}
