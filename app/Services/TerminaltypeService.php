<?php

namespace App\Services;

use App\Models\Terminaltype;
use App\Services\BaseService;

class TerminaltypeService extends BaseService
{
    public function __construct(Terminaltype $terminaltype)
    {
        $this->model = $terminaltype;
    }
}