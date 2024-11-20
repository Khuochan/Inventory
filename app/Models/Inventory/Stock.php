<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $table = 'stocks';
    protected $fillable = [
        'sparepart_id',
        'quantity',
        'used_qty',
        'using_qty',
        'broken_qty',
        'remark',
        'condition_id',
        'warehouse_id',
        'defect_id',
        'serial_part',
        'return_date',
        'usage_id'

    ];

    public static function rulesToCreate(): array
    {
        return [
            // 'serial_part' => 'required|array',
            // 'serial_part.*' => 'required|string|max:255',
        ];
    }
    public static function rulesToCreateMessages(){
        return [

        ];
    }
    public static function rulesToUpdate($id = null): array
    {
        return [
            // 'dept_name' => 'required|unique:departments',
            // 'sort_name' => 'max:4'
        ];
    }
    public static function rulesToUpdateMessages(): array
    {
        return [];
    }
}
