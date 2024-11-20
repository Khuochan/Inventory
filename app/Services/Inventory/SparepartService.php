<?php

namespace App\Services\Inventory;

use App\Models\Inventory\SparePart;
use App\Services\BaseService;

class SparepartService extends BaseService
{
    public function __construct(SparePart $spare)
    {
        $this->model = $spare;
    }
}
