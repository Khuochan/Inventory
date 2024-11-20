<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'ticket_id',
        'diagnosis',
        'terminal_id',
        'atm_id',
        'created_time',
        'end_time',
        'down_time',
        'model',
        'atm_type',
        'atm_classification',
        'atm_down',
        'issue',
        'action_taken',
    ];
}
