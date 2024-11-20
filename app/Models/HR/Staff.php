<?php

namespace App\Models\HR;

use App\Models\ParentModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Staff extends ParentModel
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'users';

    protected $fillable = [
        'dept_id',
        'last_name',
        'first_name',
        'role_id',
        'name',
        'gender',
        'email',
        'phone',
        'password',
        'address',
        'start_date',
        'family_status',
        'family_phone',
        'family_name',
        'family_member',
        'em_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function rulesToCreate(): array
    {
        return [
            'last_name' => 'required|max:255',
            'first_name' => 'required|max:255',
            'name' => 'required|max:255',
            'gender' => 'required|in:Male,Female',
            'email' => 'required|email|unique:users|regex:/(.+)@(.+)\.(.+)/i',
            'password' => 'required|min:6',
            'start_date'=> 'date_format:Y-m-d',
            'address' => 'required',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'family_status' => 'in:Single,Married',
            'family_phone',
            'family_name',
            'family_member',
        ];
    }
    public static function rulesToCreateMessages(){
        return [];
    }
    public static function rulesToUpdate($id = null): array
    {
        return [
            'last_name' => 'nullable|max:255',
            'first_name' => 'nullable|max:255',
            'name' => 'nullable|max:255',
            'gender' => 'nullable|in:Male,Female',
            'email' => 'nullable|email|regex:/(.+)@(.+)\.(.+)/i',
            'start_date'=> 'date_format:Y-m-d',
            'address' => 'nullable',
            'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'password' => 'required|min:6',
            'family_status' => 'in:Single,Married',
            'family_phone',
            'family_name',
            'family_member',
        ];
    }
    public static function rulesToUpdateMessages(): array
    {
        return [];
    }

    public function departments(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id', 'id');
    }

    public function attendences(): BelongsTo
    {
        return $this->belongsTo(Attendance::class, 'user_id', 'id');
    }

    // public function roles(): BelongsToMany
    // {

    // }
}
