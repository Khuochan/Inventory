<?php

namespace App\Imports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TicketsImport implements ToModel, WithHeadingRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Ticket([
            'ticket_id' => $row[0],
            'device' => $row[1],
            'model' => $row[2],
            'terminal_id' => $row[3],
            'atm_type' => $row[4],
            'created_time' => $row[5],
            'issue' => $row[6],
            'closed_time' => $row[7],
            'action_taken' => $row[8],
            'engineer' => $row[9],
            'state' => $row[10],
            'diagnosis' => $row[11],
            // 'ticket_id' => $row['ticket_id'],
            // 'device' => $row['device'],
            // 'model' => $row['model'],
            // 'terminal_id' => $row['terminal_id'],
            // 'atm_type' => $row[ 'atm_type' ],
            // 'created_time' => $row['created_time'],
            // 'issue' => $row['issue'],
            // 'closed_time' => $row['closed_time'],
            // 'action_taken' => $row['action_taken'],
            // 'engineer' => $row['engineer' ],
            // 'state' => $row['state'],
            // 'diagnosis' => $row['diagnosis'],
        ]);
    }
}
