<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Banklocation extends Model
{
    use HasFactory;
    protected $table = 'banklocations';

    protected $fillable =[
        'bank_name_id',
        'site_name_id',
        'siteID',
        'address',
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

    public function banknames():BelongsTo
    {
        return $this->belongsTo(Customer::class,'bank_name_id','id');
    }
    public function sitenames():BelongsTo
    {
        return $this->belongsTo(Site::class,'site_name_id','id');
    }
    public function locations():HasMany
    {
        return $this->hasMany(Terminal::class,'banklocation_id','id');
    }
}
