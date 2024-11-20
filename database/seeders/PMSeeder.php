<?php

namespace Database\Seeders;

use App\Models\PMReport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PMReport::truncate();
        $csvData = fopen(base_path('database/csv/new_pm.csv'), 'r');

        $transRaw = true;

        while (($row = fgetcsv($csvData, '1000', ',')) !== false) {
            if (! $transRaw) {
                PMReport::create([
                    'pm_id' => $row[0],
                    'service' => $row[1],
                    'terminal_id' => $row[2],
                    'atm_id' => $row[3],
                    'date' => $row[4],
                    'end_time' => $row[5],
                    'spare_part' => $row[6]
                ]);
            }
            $transRaw = false;
        }
        fclose($csvData);
    }
}
