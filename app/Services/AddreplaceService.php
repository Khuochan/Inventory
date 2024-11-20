<?php

namespace App\Services;

use App\Models\Addreplace;
use App\Services\BaseService;

class AddreplaceService extends BaseService
{
    public function __construct(Addreplace $addreplace)
    {
        $this->model = $addreplace;
    }
}