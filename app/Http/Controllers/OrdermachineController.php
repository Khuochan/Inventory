<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ordermachine;
use App\Services\OrdermachineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class OrdermachineController extends ParentController
{
    public function __construct(
        Ordermachine $ordermachine,
        OrdermachineService $ordermachineService,
    )
    {
        $this->model= $ordermachine;
        $this->service = $ordermachineService;
    }
   
    public function addorder(Request $request)
    {
        $validator =Validator::make($request->all(),[
            'bank_id'=>'required',
            'type_id'=>'required',
            'model_id'=>'required',
            'quantity'=>'required',
            'status_id'=>'required',
            'warehouse_id'=>'required',
        ]);
        if($validator->fails()) {
            $response =[
                'success' =>false,
                'status' => 402,
                'message' =>"Please input all information",
            ];
            return response()->json($response, 402);
        }
        $data = $request->all();
        Ordermachine::create($data);
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Add new order Successfully",
            'data' => $data
        ];
        return response()->json($response, 200);
    }
    public function updateorder(Request $request, $id): JsonResponse
    {
      return parent::update($request, $id);
    }
    public function getorder()
    {
        $data = DB::table('ordermachines')
                ->join('customers','customers.id','=','ordermachines.bank_id')
                ->join('terminaltypes','terminaltypes.id','=','ordermachines.type_id')
                ->join('terminalmodels','terminalmodels.id','=','ordermachines.model_id')
                ->join('terminalstatuses','terminalstatuses.id','=','ordermachines.status_id')
                ->join('warehouses','warehouses.id','=','ordermachines.warehouse_id')
                ->select('customers.customer_name',
                            'ordermachines.*',
                            'terminalmodels.terminal_model',
                            'terminalstatuses.status',
                            'warehouses.warehouse_name',
                            'terminaltypes.terminal_type')
                ->orderBy('ordermachines.id')->get();

        return response()->json($data);
    }
}
