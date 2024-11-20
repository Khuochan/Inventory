<?php

namespace App\Models\Inventory;

use App\Models\Terminalmodel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SparePart extends Model
{
    use HasFactory;
    protected $table = 'spareparts';
    protected $fillable = [
        'sparepart_name',
        'part_number',
        'model_id',
        'serialized',

    ];

    public static function rulesToCreate(): array
    {
        return [
            'part_number' => 'required|unique:spareparts|regex:/^[a-zA-Z0-9]+$/',
            'model_id'  => 'required',

        ];
    }
    public static function rulesToCreateMessages(){
        return [

        ];
    }
    public static function rulesToUpdate($id = null): array
    {
        return [
            // 'dept_name' => 'required|unique:departments',
            // 'sort_name' => 'max:4'
        ];
    }
    public static function rulesToUpdateMessages(): array
    {
        return [];
    }

    public function terminalmodels(): BelongsTo
    {
        return $this->belongsTo(Terminalmodel::class, 'model_id', 'id');
    }

}
