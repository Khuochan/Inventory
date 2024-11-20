<?php 

namespace App\Services;

use App\Models\Customer;
use App\Services\BaseService;

class CustomerService extends BaseService
{
    public function __construct(Customer $customer)
    {
        $this->model = $customer;
    }
}