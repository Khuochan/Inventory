<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ParentModel extends Model
{
    use HasFactory;
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'dob'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
        'deleted_at' => 'date:Y-m-d',
        'dob' => 'date:Y-m-d',
    ];

    /* Overriding functions*/
    public static function boot()
    {
        static::saved(function () {
            Cache::forget('models' . with(new static)->getTable() . 'all');
        });
        parent::boot();
    }

    public static function all($columns = ['*'])
    {
        return Cache::remember('models' . with(new static)->getTable() . 'all', 10, function () use ($columns) {
            return parent::all($columns);
        });
    }

    public static function create(array $attributes = [])
    {
        $attributes["created_at"] = Carbon::now();
//        $attributes["create_uid"] = 1;
        $model = static::query()->create($attributes);
        return $model;
    }
}
