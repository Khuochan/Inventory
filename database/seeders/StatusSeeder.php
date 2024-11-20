<?php

namespace Database\Seeders;

use App\Models\HR\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statuses')->delete();

        $statuses = [
            ['status_name' => 'Pending'],
            ['status_name' => 'Pending'],
            ['status_name' => 'Approved'],
            ['status_name' => 'Rejected'],
            // status job sheet
            ['status_name' => 'Repaire Success'],
            ['status_name' => 'Cannot Repaire'],
            ['status_name' => 'Open'],
            ['status_name' => 'Close'],
        ];

        Status::insert($statuses);
    }
}
