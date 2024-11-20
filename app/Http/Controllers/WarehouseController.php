<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Services\WarehouseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends ParentController
{
    public function __construct(
        Warehouse $warehouse,
        WarehouseService $warehouseService
    ) {
        $this->model = $warehouse;
        $this->service = $warehouseService;
    }

    public function AddWarehouse(Request $request)
    {
        $create = [
            'customer_id' => $request->input('customer_id'),
            'warehouse_name' => $request->input('warehouse_name'),
            'address' => $request->input('address')
        ];

        $validator=Validator::make($request->all(),[
            'customer_id' =>'required',
            'warehouse_name' =>'required',
        ]);
        if($validator->fails()) {
            $response =[
                'success' =>false,
                'message' =>"Please input all information"
            ];
            return response()->json($response, 404);
        }
        $data = DB::table('warehouses')
            ->where('customer_id', '=', $request['customer_id'])
            ->where('warehouse_name', '=', $request['warehouse_name'])
            ->count();
        if ($data > 0) {
            $response = [
                'success' => false,
                'status' => 400,
                'message' => "This warehouse is exits",
            ];
            return response()->json($response, 400);
        }
        $data1 = Warehouse::create($create);
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Add new warehouse successfully",
            'data' => $data1,
        ];
        return response()->json($response);
    }
    public function  UpdateWarehouse(Request $request, $id)
    {
        $update = [
            'customer_id' => $request->input('customer_id'),
            'warehouse_name' => $request->input('warehouse_name'),
            'address' => $request->input('address')
        ];
        $validator=Validator::make($request->all(),[
            'customer_id' =>'required',
            'warehouse_name' =>'required',
        ]);
        if($validator->fails()) {
            $response =[
                'success' =>false,
                'message' =>"Please input all information"
            ];
            return response()->json($response, 404);
        }
        $data = DB::table('warehouses')
                ->where('customer_id', '=', $request['customer_id'])
                ->where('warehouse_name', '=', $request['warehouse_name'])
                ->count();
        if ($data > 0) {
            $response = [
                'success' => false,
                'status' => 400,
                'message' => "This warehouse is exits",
            ];
            return response()->json($response, 400);
        }
        Warehouse::where('id','=', $id)->update($update);
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Updated  warehouse successfully",
        ];
        return response()->json($response, 200);
    }
    public function GetallWarehouse()
    {
        $data = DB::table('warehouses')
                    ->join('customers','customers.id','=','warehouses.customer_id')
                    ->select('customers.customer_name','warehouses.*')
                    ->orderBy('warehouses.id')
                    ->get();

        return response()->json($data);
    }

}
