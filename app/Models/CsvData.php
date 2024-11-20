<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvData extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket_id',
        'atm_id',
        'atm_type',
        'model',
        'terminal_id',
        'created_time',
        'atm_down',
        'end_time',
        'issue',
        'action_taken',
        'diagnosis',
        'atm_classification',
        'down_time'
    ];
}
