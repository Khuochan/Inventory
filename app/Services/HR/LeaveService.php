<?php

namespace App\Services\HR;

use App\Models\HR\Leave;
use App\Services\BaseService;

class LeaveService extends BaseService
{
    public function __construct(Leave $leave)
    {
        $this->model = $leave;
    }
}
