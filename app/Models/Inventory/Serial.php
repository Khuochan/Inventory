<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serial extends Model
{
    use HasFactory;
    protected $table = 'serials';
    protected $fillable = [
        'sparepart_id',
        'condition_id',
        'serail_name',

    ];
}
