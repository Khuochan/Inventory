<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnSpare extends Model
{
    use HasFactory;
    protected $table = 'return_spares';
    protected $fillable = [
        'return_date',
        'return_id',
        'user_id',
        'usage_id',
        'engineer_id',
        'spare_qty',
        'follow_up',
        'remark',
        'date_repaire',
        'sparepart_id',
        'warehouse_id',
        'status_id',
        'serial_part',
        'defect_id',
        'request_id'
    ];
}
