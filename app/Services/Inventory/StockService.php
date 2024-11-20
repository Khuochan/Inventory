<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Stock;
use App\Services\BaseService;

class StockService extends BaseService
{
    public function __construct(Stock $stock)
    {
        $this->model = $stock;
    }
}
