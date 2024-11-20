<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory\Usage;
use Illuminate\Support\Facades\DB;

class UsageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usage')->delete();

        $usage = [
            ['usage_name' => 'Used'],
            ['usage_name' => 'Unused'],
        ];

        Usage::insert($usage);
    }
}
