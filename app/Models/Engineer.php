<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Engineer extends Model
{
    use HasFactory;
    protected $table = 'engineers';
    
    protected $fillable=[
        'engineer_name',
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
    public function re_engineer():HasMany
    {
        return $this->hasMany(Addreplace::class,'engineer_id','id');
    }
}
