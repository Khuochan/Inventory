<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestSpare extends Model
{
    use HasFactory;
    protected $table = 'request_spares';
    protected $fillable = [
        'terminal_id',
        'sparepart_id',
        'spare_qty',
        'ticket_no',
        'engineer_id',
        'remark',
        'request_date',
        'status_id',
        'request_name',
        'request_status_id'
    ];
}
