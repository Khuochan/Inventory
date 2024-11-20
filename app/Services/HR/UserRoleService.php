<?php

namespace App\Services;

use App\Models\UserRole;

class UserRoleService extends BaseService
{
    public function __construct(UserRole $userRole)
    {
        $this->model = $userRole;
    }
}
