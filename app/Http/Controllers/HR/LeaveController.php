<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\ParentController;
use App\Models\HR\Leave;
use App\Services\HR\LeaveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveController extends ParentController
{
    public function __construct(
        Leave $leave,
        LeaveService $leaveService,
    )
    {
        $this->model = $leave;
        $this->service = $leaveService;
    }
    public function all(): JsonResponse
    {
        return parent::all();
    }
    public function dataTable(Request $request, $query = null): JsonResponse
    {
        return parent::dataTable($request, $query);
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
   public function listTable()
   {
         $data = DB::select('SELECT * FROM leaves ORDER BY id');

        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $data
        ];
        return response()->json($response, 200);
   }
}
