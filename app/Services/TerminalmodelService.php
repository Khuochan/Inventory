<?php 

namespace App\Services;
use App\Models\Terminalmodel;
use App\Services\BaseService;

class TerminalmodelService extends BaseService
{
    public function __construct(Terminalmodel $terminalmodel)
    {
        $this->model = $terminalmodel;
    }
}