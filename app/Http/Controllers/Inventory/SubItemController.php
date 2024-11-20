<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Stock;
use Illuminate\Http\JsonResponse;
use App\Services\Inventory\StockService;
use App\Http\Controllers\ParentController;
use App\Models\HR\Status;
use App\Models\Inventory\Condition;
use App\Models\Inventory\DefectType;
use App\Models\Inventory\Usage;
use Illuminate\Support\Facades\DB;

class SubItemController extends Controller
{
    public function getCondtion(){
        $condition = Condition::all();

        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $condition
        ];
        return response()->json($response, 200);
    }

    public function getDefect(){
        $defect = DefectType::all();

        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $defect
        ];
        return response()->json($response, 200);
    }

    public function getStatus()
    {
        $status = Status::whereIn('id', [1, 3, 4])->get();

        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $status
        ];
        return response()->json($response, 200);
    }

    public function getUsage()
    {
        $usage = Usage::all();

        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $usage
        ];
        return response()->json($response, 200);
    }

    public function getStatusJob()
    {
        $status = Status::whereIn('id', [5, 6, 7, 8])->get();

        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $status
        ];
        return response()->json($response, 200);
    }

    public function getStatusRepaire()
    {
        $status = Status::whereIn('id', [ 5, 6])->get();

        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $status
        ];
        return response()->json($response, 200);
    }

    public function CountInventory(){

        $request = DB::table('request_spares')->where('status_id', '=', 1)->count();
        $return = DB::table('request_spares')->where('request_status_id', '=', 1)->count();
        $job = DB::table('return_spares')->where('status_id', '=', 7)->count();

        return response()->json([
            'request' => $request,
            'return' => $return,
            'job' => $job
        ]);
    }
}
