<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Addreplace;
use App\Models\Sparepart;
use App\Services\AddreplaceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddreplaceController extends ParentController
{
    public function __construct(
        Addreplace $addreplace,
        AddreplaceService $addreplaceService,
    ) {
        $this->model = $addreplace;
        $this->service = $addreplaceService;
    }
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'terminal_id' => 'required',
            'sparepart_id' => 'required',
            'replace_date' => 'required',
            'engineer_id' => 'required',
            'quantity' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'status' => 402,
                'message' => "Please input all information",
            ];
            return response()->json($response, 402);
        }
        $data = DB::table('spareparts')->where('id', '=', $request['sparepart_id'])->sum('quantity_remain');
        $data1 = DB::table('spareparts')->where('id', '=', $request['sparepart_id'])->sum('quantity_used');
        if ($request['quantity'] > $data) {
            $response = [
                'success' => false,
                'status' => 403,
                'message' => "Spare part in stock is not enough",
            ];
            return response()->json($response, 403);
        }
        $update = [
            'quantity_remain' => $request->input('quantity_remain'),
            'quantity_used' => $request->input('quantity_used'),
        ];
        $update['quantity_remain'] = $data - $request['quantity'];
        $update['quantity_used'] = $data1 + $request['quantity'];

        Sparepart::where('id', '=', $request['sparepart_id'])->update($update);

        return parent::create($request);
    }
    public function updateReplace(Request $request, $id): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'terminal_id' => 'required',
            'sparepart_id' => 'required',
            'replace_date' => 'required',
            'engineer_id' => 'required',
            'quantity' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'status' => 402,
                'message' => "Please input all information",
            ];
            return response()->json($response, 402);
        }
        $replace = Addreplace::find($id);
        if($replace['sparepart_id'] != $request['sparepart_id'])
        {
            $data = DB::table('spareparts')->where('id', '=', $replace['sparepart_id'])->sum('quantity_remain');
            $data1 = DB::table('spareparts')->where('id', '=', $replace['sparepart_id'])->sum('quantity_used');
            $update1 = [
                'quantity_remain' => $request->input('quantity_remain'),
                'quantity_used' => $request->input('quantity_used'),
            ];
            $update1['quantity_remain'] = $data + $replace['quantity'];
            $update1['quantity_used'] = $data1 - $replace['quantity'];
            Sparepart::where('id', '=', $replace['sparepart_id'])->update($update1);

            $data2 = DB::table('spareparts')->where('id', '=', $request['sparepart_id'])->sum('quantity_remain');
            $data3 = DB::table('spareparts')->where('id', '=', $request['sparepart_id'])->sum('quantity_used');
            if ($request['quantity'] > $data) {
                $response = [
                    'success' => false,
                    'status' => 403,
                    'message' => "Spare part in stock is not enough",
                ];
                return response()->json($response, 403);
            }
            $update = [
                'quantity_remain' => $request->input('quantity_remain'),
                'quantity_used' => $request->input('quantity_used'),
            ];
            $update['quantity_remain'] = $data2 - $request['quantity'];
            $update['quantity_used'] = $data3 + $request['quantity'];
            Sparepart::where('id', '=', $request['sparepart_id'])->update($update);
            return parent::update($request,$id);

        }
            $data = DB::table('spareparts')->where('id', '=', $request['sparepart_id'])->sum('quantity_remain');
            $data1 = DB::table('spareparts')->where('id', '=', $request['sparepart_id'])->sum('quantity_used');
            if ($request['quantity'] > $data) {
                $response = [
                    'success' => false,
                    'status' => 403,
                    'message' => "Spare part in stock is not enough",
                ];
                return response()->json($response, 403);
            }

            $update = [
                'quantity_remain' => $request->input('quantity_remain'),
                'quantity_used' => $request->input('quantity_used'),
            ];
            $update['quantity_remain'] =$data + $replace['quantity'] -$request['quantity'];
            $update['quantity_used'] =  $request['quantity'] +$data1 -$replace['quantity'];
            Sparepart::where('id', '=', $request['sparepart_id'])->update($update);
            return parent::update($request,$id);

    }
    public function delete($id): JsonResponse
    {
        return parent::delete($id);
    }
    public function getData()
    {
        $data = DB::table('addreplaces')
                ->join('spareparts','spareparts.id','=','addreplaces.sparepart_id')
                ->join('engineers','engineers.id','=','addreplaces.engineer_id')
                ->join('terminals','terminals.id','=','addreplaces.terminal_id')
                ->select('addreplaces.*','spareparts.sparepart_name','engineers.engineer_name','terminals.atm_id')
                ->orderBy('addreplaces.id')->get();
     return $data;
    }
}
