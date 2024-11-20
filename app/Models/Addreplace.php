<?php

namespace App\Models;

use App\Http\Controllers\TerminalController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Addreplace extends Model
{
    use HasFactory;
    protected $table='addreplaces';

    protected $fillable=[
        'terminal_id',
        'sparepart_id',
        'replace_date',
        'engineer_id',
        'quantity',
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
    public function re_terminal():BelongsTo
    {
        return $this->belongsTo(Terminal::class,'terminal_id','id');
    }
    public function re_engineer():BelongsTo
    {
        return $this->belongsTo(Engineer::class,'engineer_id','id');
    }
    public function re_sparepart():BelongsTo
    {
        return $this->belongsTo(Sparepart::class,'sparepart_id','id');
    }
}
