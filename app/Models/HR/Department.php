<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Department extends Model
{
    use HasFactory;
    protected $table = 'departments';

    protected $fillable = [
        'dept_name',
        'sort_name',
    ];

    public static function rulesToCreate(): array
    {
        return [
            'dept_name' => 'required|unique:departments',
            'sort_name' => 'required|max:4'
        ];
    }
    public static function rulesToCreateMessages(){
        return [

        ];
    }
    public static function rulesToUpdate($id = null): array
    {
        return [
            'dept_name' => 'required|unique:departments',
            'sort_name' => 'max:4'
        ];
    }
    public static function rulesToUpdateMessages(): array
    {
        return [];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'dept_id');
    }
}
