<?php

namespace Database\Seeders;

use App\Models\HR\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->delete();

        $depts = [
            [
                'id' => 1,
                'dept_name' => 'Human Resource',
                'sort_name' => 'HR',
            ],
            [
                'id' => 2,
                'dept_name' => 'Service Delivery',
                'sort_name' => 'Service Delivery',
            ],
            [
                'id' => 3,
                'dept_name' => 'Finance',
                'sort_name' => 'Finance',
            ],
            [
                'id' => 4,
                'dept_name' => 'Sale',
                'sort_name' => 'Sale',
            ],
            [
                'id' => 5,
                'dept_name' => 'Marketing',
                'sort_name' => 'Marketing',
            ],
            [
                'id' => 6,
                'dept_name' => 'Operation',
                'sort_name' => 'Operation',
            ],
        ];
        Department::insert($depts);
    }
}
