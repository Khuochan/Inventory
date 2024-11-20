<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory\Condition;
use Illuminate\Support\Facades\DB;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('conditions')->delete();

        $condition = [
            ['condition_name' => 'Good'],
            ['condition_name' => 'Defect'],
        ];

        Condition::insert($condition);
    }
}
