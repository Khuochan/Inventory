<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Stock;
use Illuminate\Http\JsonResponse;
use App\Services\Inventory\StockService;
use App\Http\Controllers\ParentController;
use Illuminate\Support\Facades\DB;

class StockController extends ParentController
{
    public function __construct(
        Stock $stock,
        StockService $stockService,
    )
    {
        $this->model = $stock;
        $this->service = $stockService;
    }
    public function all(): JsonResponse //grop by sparepart_id
    {
        $data = DB::table('stocks')
        ->join('spareparts', 'spareparts.id', '=', 'stocks.sparepart_id')
        ->join('terminalmodels', 'terminalmodels.id', '=', 'spareparts.model_id')
        ->join('conditions', 'conditions.id', '=', 'stocks.condition_id')
        ->join('warehouses', 'warehouses.id', '=', 'stocks.warehouse_id')
        ->select(
            DB::raw('MAX(stocks.id) as stock_id'),
            'stocks.sparepart_id',
            DB::raw('SUM(stocks.quantity) AS total_quantity'),
            DB::raw('SUM(stocks.used_qty) AS total_used_qty'),
            DB::raw('SUM(stocks.broken_qty) AS total_broken_qty'),
            DB::raw('SUM(stocks.using_qty) AS total_using_qty'),
            'spareparts.sparepart_name',
            'stocks.serial_part',
            'spareparts.part_number',
            'stocks.condition_id',
            'stocks.defect_id',
            'stocks.warehouse_id',
            'conditions.condition_name',
            'warehouses.warehouse_name',
            'terminalmodels.terminal_model',
            'stocks.remark'
        )
        ->groupBy(
            'stocks.sparepart_id',
            'warehouses.warehouse_name',
            'spareparts.sparepart_name',
            'stocks.serial_part',
            'spareparts.part_number',
            'conditions.condition_name',
            'stocks.defect_id',
            'stocks.warehouse_id',
            'stocks.condition_id',
            'terminalmodels.terminal_model',
            'stocks.remark'
        )
            ->orderBy('stock_id')
            ->get();

        $formattedData = [];

        foreach ($data as $item) {
            $key = $item->sparepart_id . '_' . $item->warehouse_name . '_' . $item->condition_name;
            if (!isset($formattedData[$key])) {
                $formattedData[$key] = [
                    'stock_id' => $item->stock_id,
                    'sparepart_id' => $item->sparepart_id,
                    'warehouse_name' => $item->warehouse_name,
                    'part_number' => $item->part_number,
                    'sparepart_name' => $item->sparepart_name,
                    'warehouse_id'=> $item->warehouse_id,
                    'condition_id' => $item->condition_id,
                    'condition_name' => $item->condition_name,
                    'defect_id' => $item->defect_id,
                    'terminal_model' => $item->terminal_model,
                    'remark' => $item->remark,
                    'serial_data' => [],
                    'quantity' => 0,
                    'used_qty' => 0,
                    'using_qty' =>0,
                    'broken_qty' =>0
                ];
            }
            // Add the quantity to the sum_stock
            $formattedData[$key]['quantity'] += $item->total_quantity;
            $formattedData[$key]['used_qty'] += $item->total_used_qty;
            $formattedData[$key]['using_qty'] += $item->total_using_qty;
            $formattedData[$key]['broken_qty'] += $item->total_broken_qty;
            // Add the serial data
            $formattedData[$key]['serial_data'][] = [
                'quantity' => $item->total_quantity,
                'serial_part' => $item->serial_part
            ];
        }
        $formattedData = array_values($formattedData);
        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $formattedData
        ];
        return response()->json($response, 200);
    }
    public function GetSpare(): JsonResponse
    {
        $data =  DB::table('stocks')
        ->join('spareparts','spareparts.id','=','stocks.sparepart_id')
        ->join('terminalmodels','terminalmodels.id','=','spareparts.model_id')
        ->join('warehouses', 'warehouses.id', '=', 'stocks.warehouse_id')
        ->select('stocks.*','spareparts.sparepart_name','spareparts.part_number','spareparts.serialized',
        'terminalmodels.terminal_model', 'warehouses.warehouse_name' )
        ->orderBy('stocks.id')->get();
        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $data
        ];
        return response()->json($response, 200);
    }

    public function addStock(Request $request) // with serail number and add one stock
    {
        $request->validate([
            'sparepart_id' => 'required|integer',
            'quantity' => 'required|integer',
            'warehouse_id' => 'required|integer',
            'serial_parts' => 'nullable|array|size:' . $request->input('quantity'),
            'serial_parts.*.serial_part' => 'required|string|max:255',
            'remark' => 'nullable|string',
            'defect_id' => 'nullable|integer',
            'condition_id' => 'nullable|integer'
        ]);

        // Get the inputs from the request
        $sparepartId = $request->input('sparepart_id');
        $quantity = $request->input('quantity');
        $warehouse = $request->input('warehouse_id');
        $remark = $request->input('remark');
        $defect = $request->input('defect_id');
        $condition = $request->input('condition_id');

        $createdStocks = [];

        // Check if serial_parts array exists
        if ($request->has('serial_parts')) {
            // Loop through the serial parts and create records
            foreach ($request->input('serial_parts') as $serialPart) {
                $stock = Stock::create([
                    'sparepart_id' => $sparepartId,
                    'quantity' => 1,
                    'used_qty' => 1,
                    'warehouse_id' => $warehouse,
                    'serial_part' => $serialPart['serial_part'],
                    'remark' => $remark,
                    'condition_id' => $condition,
                    'defect_id' => $defect
                ]);

                $createdStocks[] = $stock;
            }
            $response = [
                'success' => true,
                'message' => "Stock items added successfully.",
                'data' => $createdStocks
            ];
        } else {
            // Create a record without serial parts
            $stock = Stock::create([
                'sparepart_id' => $sparepartId,
                'quantity' => $quantity,
                'used_qty' => $quantity,
                'warehouse_id' => $warehouse,
                'serial_part' => null,
                'remark' => $remark,
                'condition_id' => $condition,
                'defect_id' => $defect
            ]);

            $response = [
                'success' => true,
                'message' => "Stock items added successfully.",
                'data' => $stock
            ];
        }
        return response()->json($response, 200);
    }

    public function getStockID($sparepart_id)
    {
            $data = DB::table('stocks')
            ->join('spareparts', 'spareparts.id', '=', 'stocks.sparepart_id')
            ->join('conditions', 'conditions.id', '=', 'stocks.condition_id')
            ->join('warehouses', 'warehouses.id', '=', 'stocks.warehouse_id')
            ->select(
                'stocks.id',
                'stocks.sparepart_id',
                DB::raw('SUM(stocks.used_qty) AS total_quantity'),
                'stocks.serial_part',
                'conditions.condition_name',
                'stocks.warehouse_id',
                'warehouses.warehouse_name',
            )
            ->where('stocks.sparepart_id', $sparepart_id)
            ->where('stocks.used_qty', '>', 0)
            ->groupBy(
                'stocks.id',
                'stocks.sparepart_id',
                'stocks.warehouse_id',
                'warehouses.warehouse_name',
                'stocks.serial_part',
                'conditions.condition_name',
            )
            ->get();

        $formattedData = [];

        foreach ($data as $item) {
            $key = $item->sparepart_id . '_' . $item->warehouse_name;
            if (!isset($formattedData[$key])) {
                $formattedData[$key] = [

                    'sparepart_id' => $item->sparepart_id,
                    'warehouse_name' => $item->warehouse_name,
                    'warehouse_id' => $item->warehouse_id,
                    'condition_name' => $item->condition_name,
                    'serial_data' => [],
                    'stock_qty' => 0
                ];
            }
            // Add the quantity to the sum_stock
            $formattedData[$key]['stock_qty'] += $item->total_quantity;

            // Add the serial data
            $formattedData[$key]['serial_data'][] = [
                'stock_id' => $item->id,
                'quantity' => $item->total_quantity,
                'serial_part' => $item->serial_part

            ];
        }
        $formattedData = array_values($formattedData);
        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $formattedData
        ];
        return response()->json($response, 200);
    }

    public function getSerial($sparepart_id)
    {
        try {
            $data =  DB::table('stocks')
            ->join('spareparts','spareparts.id','=','stocks.sparepart_id')
            ->join('conditions','conditions.id','=','stocks.condition_id')
            ->join('warehouses','warehouses.id','=','stocks.warehouse_id')
            ->select('stocks.id', 'stocks.sparepart_id', 'stocks.serial_part','conditions.condition_name','spareparts.sparepart_name', 'spareparts.part_number', 'stocks.used_qty')
            ->where('stocks.sparepart_id', $sparepart_id)
            ->where('stocks.used_qty', '>', 0)
            ->where('spareparts.serialized', true)
            ->get();

            $response = [
                'success' => true,
                'message' => "OK",
                'data' => $data
            ];
            return response()->json($response, 200);
        } catch (\Illuminate\Database\QueryException $e) {
                $response = [
                    'success' => false,
                    'message' => "An error occurred while fetching stock data",
                    'error' => $e->getMessage()
                ];
                return response()->json($response, 500);
        }
    }
}
