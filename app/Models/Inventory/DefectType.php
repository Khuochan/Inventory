<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefectType extends Model
{
    use HasFactory;
    protected $table = 'defect_types';
    protected $fillable = [
        'type_name',

    ];
}
