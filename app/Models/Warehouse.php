<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;
    protected $table = 'warehouses';
    protected $fillable =[
        'customer_id',
        'warehouse_name',
        'address'
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
    public function warehouses():BelongsTo
    {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
    public function orderwarehouse():HasMany
    {
        return $this->hasMany(Ordermachine::class,'warehouse_id','id');
    }
}
