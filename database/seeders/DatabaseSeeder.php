<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Terminalmodel;
use App\Models\Terminaltype;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this -> call(DepartmentSeeder::class);
        $this -> call(UserSeeder::class);
        $this -> call(StatusSeeder::class);
        $this -> call(RolesTableSeeder::class);
        $this->call(UsingSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(SiteSeeder::class);
        $this->call(BanklocationSeeder::class);
        $this->call(TerminalTypeSeeder::class);
        $this->call(TerminalmodelSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(TerminalstatusSeeder::class);
        $this->call(TerminalSeeder::class);
        $this->call(EngineerSeeder::class);

        // inventory
        $this->call(UsageSeeder::class);
        $this->call(ConditionSeeder::class);
        $this->call(DefectTypeSeeder::class);
        $this->call(WarehouseSeeder::class);
        $this->call(SparePartSeeder::class);
        $this->call(RequestStatusSeeder::class);
    }
}
