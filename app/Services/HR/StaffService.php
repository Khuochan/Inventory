<?php

namespace App\Services\HR;

use App\Models\HR\Staff;
use App\Services\BaseService;

class StaffService extends BaseService
{
    public function __construct(Staff $staff)
    {
        $this->model = $staff;
    }
}
