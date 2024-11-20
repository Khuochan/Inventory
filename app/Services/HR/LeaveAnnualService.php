<?php

namespace App\Services\HR;

use App\Models\HR\LeaveAnnual;
use App\Services\BaseService;

class LeaveAnnualService extends BaseService
{
    public function __construct(LeaveAnnual $leave)
    {
        $this->model = $leave;
    }
}
