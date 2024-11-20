<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Services\SparepartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SparepartController extends ParentController
{
    public function __construct(
        Sparepart $sparepart,
        SparepartService $sparepartService,
    )
    {
        $this->model= $sparepart;
        $this->service = $sparepartService;
    }
   public function test1()
   {
    return ("Testing");
   }

   public function create(Request  $request):JsonResponse
   {

    $validator = Validator::make($request->all(),[
            'model_id' => 'required',
            'sparepart_name' =>'required',
            'quantity' =>'required',
    ]);
    if($validator->fails()) {
        $response =[
            'success' =>false,
            'status' => 402,
            'message' =>"Please input all information",
        ];
        return response()->json($response, 402);
    }
        $data1 =DB::table('spareparts')->where('model_id','=',$request['model_id'])
                                       ->where('sparepart_name','=',$request['sparepart_name'])->count();
        if($data1 >0){
            $response =[
                'success' =>false,
                'status' => 403,
                'message' =>"This sparepart is exit",
            ];
            return response()->json($response, 403);
        }
    $request['quantity_remain'] = $request['quantity'];
    $request['quantity_used']=0;
    return parent::create($request);
   }
   public function updateData(Request $request, $id)
   {
    $data = Sparepart::find($id);

        if($request['quantity']<$data['quantity_used']){
            $response =[
                'success' =>false,
                'status' => 403,
                'message' =>"The total quantity is smaller than quantity used",
            ];
            return response()->json($response, 403);
        }
        $request['quantity_remain'] = $request['quantity']- $data['quantity_used'];
        return parent::update($request, $id);

   }
   public function delete($id):JsonResponse
   {
    return parent::delete($id);
   }
   public function getData()
   {
     $data = DB::table('spareparts')
                ->join('terminalmodels','terminalmodels.id','=','spareparts.model_id')
                ->select('spareparts.*','terminalmodels.terminal_model')
                ->orderBy('id')->get();
     return $data;
   }
}
