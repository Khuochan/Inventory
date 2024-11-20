<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;
    protected $table = 'leaves';

    protected $fillable = [
        'leave_name',
    ];

    public static function rulesToCreate(): array
    {
        return [
            'leave_name' => 'required|unique:leaves',
            // 'credit_leave' => 'required|integer'
        ];
    }
    public static function rulesToCreateMessages(){
        return [

        ];
    }
    public static function rulesToUpdate($id = null): array
    {
        return [
            'credit_leave' => 'required|integer'
        ];
    }
    public static function rulesToUpdateMessages(): array
    {
        return [];
    }

    // public function staff(): BelongsTo
    // {
    //     return $this->belongsTo(Staff::class, 'dept_id');
    // }
}
