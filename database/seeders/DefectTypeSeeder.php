<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory\DefectType;
use Illuminate\Support\Facades\DB;

class DefectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('defect_types')->delete();

        $defect = [
            ['type_name' => 'Reworkable'],
            ['type_name' => 'Nonreworkable'],
        ];

        DefectType::insert($defect);
    }
}
