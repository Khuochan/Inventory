<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;
    protected $table = 'user_roles';

    protected $fillable = [
        'role_name',
        'permission',
    ];

    public static function rulesToCreate(): array
    {
        return [
            'role_name' => 'required|unique:user_roles',
        ];
    }
    public static function rulesToCreateMessages(){
        return [

        ];
    }
    public static function rulesToUpdate($id = null): array
    {
        return [
            'role_name' => 'required|unique:user_roles',
        ];
    }
    public static function rulesToUpdateMessages(): array
    {
        return [];
    }
}
