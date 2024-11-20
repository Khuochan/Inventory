<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;
    
    protected $table = 'customers';

    protected $fillable = [
        'customer_name',
    ];
    public static function rulesToCreate(): array
    {
        return[];
    }
    public static function rulesToCreateMessages(){
        return [];
    }
    public static function rulesToUpdate($id = null): array
    {
        return [];
    }
    public static function rulesToUpdateMessages(): array
    {
        return [];
    }
    public function warehouse():HasMany
    {
        return $this->hasMany(Warehouse::class,'customer_id','id');
    }
    public function banknames():HasMany
    {
        return $this->hasMany(Banklocation::class,'bank_name_id','id');
    }
    public function customers():HasMany
    {
        return $this->hasMany(Ordermachine::class,'bank_id','id');
    }
 
}
