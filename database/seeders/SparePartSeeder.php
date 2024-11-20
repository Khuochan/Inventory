<?php

namespace Database\Seeders;

use App\Models\Sparepart;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SparePartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('spareparts')->delete();

        $spare = [
            [
                'sparepart_name' => 'Cable',
                'model_id' => 1,
                'part_number' => '00001',
                'serialized' => false
            ],
            [
                'sparepart_name' => 'Take wheel',
                'model_id' => 2,
                'part_number' => '00008',
                'serialized' => false
            ],
            [
                'sparepart_name' => 'Mainboard',
                'model_id' => 1,
                'part_number' => '00003',
                'serialized' => false
            ],
        ];

        Sparepart::insert($spare);
    }
}
