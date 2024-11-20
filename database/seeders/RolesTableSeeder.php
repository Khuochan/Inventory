<?php

namespace Database\Seeders;

use App\Models\Auth\Roles;
use App\Models\HR\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id'    => 1,
                'role_name' => 'Admin',
            ],
            [
                'id'    => 2,
                'role_name' => 'Technology Specialist',
            ],
            [
                'id'    => 3,
                'role_name' => 'Director',
            ],
            [
                'id'    => 4,
                'role_name' => 'Account & Admin Officer',
            ],
            [
                'id'    => 5,
                'role_name' => 'Service Delivery Manager',
            ],
            [
                'id'    => 6,
                'role_name' => 'Head of Operations',
            ]
        ];
        UserRole::insert($roles);
    }
}
