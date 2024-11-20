<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveAnnual extends Model
{
    use HasFactory;
    protected $table = 'leave_annuals';

    protected $fillable = [
        'leave_id',
        'user_id',
        'year',
        'credit_leave'
    ];

    public static function rulesToCreate(): array
    {
        return [
            'leave_id' => 'required',
            'year' => 'required|integer',
            'credit_leave' => 'required|numeric|min:0'
        ];
    }
    public static function rulesToCreateMessages(){
        return [

        ];
    }
    public static function rulesToUpdate($id = null): array
    {
        return [
            // 'leave_id' => 'required',
            // 'year_id' => 'required',
            'credit_leave' => 'required|numeric|min:0'
        ];
    }
    public static function rulesToUpdateMessages(): array
    {
        return [];
    }
}
