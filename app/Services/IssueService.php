<?php

namespace App\Services;

use App\Models\Issue;
use App\Services\BaseService;

class IssueService extends BaseService
{
    public function __construct(Issue $issue)
    {
        $this->model = $issue;
    }
}
