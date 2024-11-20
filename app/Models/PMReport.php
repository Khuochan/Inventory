<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PMReport extends Model
{
    use HasFactory;
    protected $table ='pm_report';
    protected $fillable = [
        'pm_id',
        'service',
        'terminal_id',
        'atm_id',
        'date',
        'end_time',
        'spare_part',
    ];

    public static function rulesToCreate(): array
    {
        return[
            'pm_id' => 'required|unique:pm_report'
        ];
    }

    public static function rulesToCreateMessages(){
        return [];
    }

    public static function rulesToUpdate($id = null): array
    {
        return [
            // 'pm_id' => 'required|unique:pm_report'
        ];
    }
    public static function rulesToUpdateMessages(): array
    {
        return [];
    }
}
