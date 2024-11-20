<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Services\StockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends ParentController
{
    public function __construct( Stock $stock, StockService $stockService,)
    {
        $this->model= $stock;
        $this->service = $stockService;
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
    public function getdata($id){

        $data = DB::table('stocks')
                ->join('orders', 'orders.id', '=', 'stocks.order_id')
                ->select('stocks.*')
                ->where('orders.id','=',$id)
                ->orderBy('stocks.id')
                ->get();

        return $data;
    }
}
