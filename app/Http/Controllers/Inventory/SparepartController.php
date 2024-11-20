<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\SparePart;
use App\Services\Inventory\SparepartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ParentController;

class SparepartController extends ParentController
{
    public function __construct(
        SparePart $spare,
        SparepartService $spareService,
    )
    {
        $this->model = $spare;
        $this->service = $spareService;
    }

    public function GetSpare()
    {
        $data = DB::table('terminalmodels')
        ->join('spareparts', 'spareparts.model_id', '=', 'terminalmodels.id')
        ->orderBy('spareparts.id')
        ->select('spareparts.*', 'terminalmodels.terminal_model')
        ->get();

        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $data
        ];
        return response()->json($response, 200);
    }

    public function all(): JsonResponse
    {
        return parent::all();
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
