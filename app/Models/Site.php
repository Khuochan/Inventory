<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;
    public $table = 'sites';
    
    public $fillable = [
        'site_name',
    ];
    public function sitenames():HasMany
    {
        return $this->hasMany(Banklocation::class,'site_name_id','id');
    }
}
