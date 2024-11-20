<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;
    protected $table = 'attendances';

    protected $fillable = [
        'user_id',
        'leave_annual_id',
        'approve_id',
        'accept_id',
        'status_id',
        'date_request',
        'from_date',
        'to_date',
        'reason',
        'attachment',
        'desc_reject',
        'shiftime'
    ];

    public static function rulesToCreate(): array
    {
        return [
            'from_date'=> 'required|date_format:Y-m-d',
            'to_date'=> 'required|date_format:Y-m-d',
            'date_request'=> 'required|date_format:Y-m-d',
            'attachment' => 'nullable|images|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'time' => 'required|in:morning,afternoon',
        ];
    }
    public static function rulesToCreateMessages(){
        return [

        ];
    }
    public static function rulesToUpdate($id = null): array
    {
        return [
            'from_date'=> 'date_format:Y-m-d',
            'to_date'=> 'date_format:Y-m-d',
            'date_request'=> 'date_format:Y-m-d',
        ];
    }
    public static function rulesToUpdateMessages(): array
    {
        return [];
    }

    public function statuses(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }
    public function acceptBy(){
        return $this->belongsTo(User::class,"accept_id");
    }
    public function user(){
        return $this->belongsTo(User::class,"user_id");
    }
}
