<?php

namespace Database\Seeders;

use App\Models\HR\Leave;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('leaves')->delete();

        $leaves = [
            [
                'leave_name' => 'Sick Leave',
            ],
            [
                'leave_name' => 'Annual Leave',
            ],
            [
                'leave_name' => 'Unpaid Leave',
            ],
            [
                'leave_name' => 'Personal Leave',
            ],
            [
                'leave_name' => 'Maternity/ Paternity Leave',
            ],
            [
                'leave_name' => 'Other',
            ],
        ];

        Leave::insert($leaves);
    }
}
