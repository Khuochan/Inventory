<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('warehouses')->delete();

        $usage = [
            [
                'warehouse_name' => 'HO',
                'customer_id' => 1,
                'address' => 'st 63'
            ],
            [
                'warehouse_name' => '371',
                'customer_id' => 1,
                'address' => 'st 371'
            ],
        ];

        Warehouse::insert($usage);
    }
}
