<?php 

namespace App\Services;

use App\Models\Banklocation;
use App\Services\BaseService;

class BanklocationService extends BaseService
{
    public function __construct(Banklocation $banklocation)
    {
        $this->model = $banklocation;
    }
}