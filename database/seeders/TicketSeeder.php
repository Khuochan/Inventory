<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ticket::truncate();
        $csvData = fopen(base_path('database/csv/Incident.csv'), 'r');

        $transRaw = true;

        while (($row = fgetcsv($csvData, '1000', ',')) !== false) {
            if (! $transRaw) {
                Ticket::create([
                    'ticket_id' => $row[0],
                    'diagnosis' => $row[1],
                    'terminal_id' => $row[2],
                    'atm_id' => $row[3],
                    'created_time' => $row[4],
                    'end_time' => $row[5],
                    'down_time' => $row[6],
                    'model' => $row[7],
                    'atm_type' => $row[8],
                    'atm_classification' => $row[9],
                    'atm_down' => $row[10],
                    'issue' => $row[12],
                    'action_taken' => $row[11],
                ]);
            }
            $transRaw = false;
        }
        fclose($csvData);
    }
}
