<?php

namespace App\Services;


use App\Models\Ordermachine;
use App\Services\BaseService;

class OrdermachineService extends BaseService
{
    public function __construct(Ordermachine $order)
    {
        $this->model = $order;
    }
}