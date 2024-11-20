<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Terminalstatus extends Model
{
    use HasFactory;
    protected $table ='terminalstatuses';
    protected $fillable=[
        'status',
    ];
    public static function rulesToCreate(): array
    {
        return[];
    }
    public static function rulesToCreateMessages()
    {
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
    public function statuses():HasMany
    {
        return $this->hasMany(Terminal::class,'status_id','id');
    }
    public function orderstatuses():HasMany
    {
        return $this->hasMany(Ordermachine::class,'status_id','id');
    }
}
