<?php

namespace App\Services\HR;

use App\Models\HR\Attendance;
use App\Services\BaseService;

class AttendanceService extends BaseService
{
    public function __construct(Attendance $attendance)
    {
        $this->model = $attendance;
    }
}
