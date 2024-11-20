<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Terminalmodel extends Model
{
    use HasFactory;
    protected $table= 'terminalmodels';

    protected $fillable = [
        'terminaltype_id',
        'terminal_model',
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
    public function terminalmodels(): BelongsTo
    {
        return $this->belongsTo(Terminaltype::class, 'terminaltye_id', 'id');
    }
    public function models():HasMany
    {
        return $this->hasMany(Terminal::class,'model_id','id');
    }
    public function spareparts():HasMany
    {
        return $this->hasMany(Sparepart::class,'model_id','id');
    }
    public function ordermodels():HasMany
    {
        return $this->hasMany(Ordermachine::class,'model_id','id');
    }

    public function sparepart(): BelongsTo
    {
        return $this->belongsTo(SparePart::class, 'model_id');
    }


}
