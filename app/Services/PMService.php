<?php
 namespace App\Services;

 use App\Models\PMReport;
 use App\Services\BaseService;

 class PMService extends BaseService
 {
    public function __construct(PMReport $pmReport)
    {
        $this->model = $pmReport;
    }
 }
