<?php

namespace App\Models\LogFile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'time',
        'code',
        'thread',
        'log_message',
        'inputTrayEmpty',
        'rejectTrayEmpty',
        'escrowEmpty',
        'transportOk',
        'hoodDoorClosed',
        'hoodDoorOk',
        'baffleDoorOk',
        'countingModuleOk',
        'readyForSecondDeposit',
        'readyForFirstDeposit'
    ];
}
