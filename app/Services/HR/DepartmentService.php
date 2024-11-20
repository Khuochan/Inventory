<?php

namespace App\Services\HR;

use App\Models\HR\Department;
use App\Services\BaseService;

class DepartmentService extends BaseService
{
    public function __construct(Department $department)
    {
        $this->model = $department;
    }
}
