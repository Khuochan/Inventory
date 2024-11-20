<?php

namespace Database\Seeders;

use App\Models\Inventory\RequestStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequestStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('request_statuses')->delete();

        $usage = [
            ['request_status' => 'Open'],
            ['request_status' => 'Close'],
        ];

        RequestStatus::insert($usage);
    }
}
