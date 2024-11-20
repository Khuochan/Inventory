<?php

namespace App\Services;

use App\Models\Warehouse;
use App\Services\BaseService;

class  WarehouseService extends BaseService
{
    public function __construct(Warehouse $warehouse)
    {
        return $this->model = $warehouse;
    }
}