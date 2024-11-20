<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ParentController;
use App\Models\Issue;
use App\Services\IssueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IssueController extends ParentController
{
    public function __construct( Issue $issue, IssueService $issueService,)
    {
        $this->model= $issue;
        $this->service = $issueService;
    }
    public function all(): JsonResponse
    {
        $issue = Issue::orderBy('issue_name')->get();
        return response()->json($issue);
    }
    public function create(Request $request): JsonResponse
    {

        return parent::create($request);
    }
    public function update(Request $request, $id): JsonResponse
    {

        return parent::update($request, $id);
    }
    public function delete($id): JsonResponse
    {
        return parent::delete($id);
    }
}
