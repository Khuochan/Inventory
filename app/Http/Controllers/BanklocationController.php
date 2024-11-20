<?php

namespace App\Http\Controllers;

use App\Models\Banklocation;
use App\Services\BanklocationService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BanklocationController extends ParentController
{
    public function __construct(
        Banklocation $banklocation,
        BanklocationService $banklocationService,
    )
    { 
        $this->model = $banklocation;
        $this->service = $banklocationService;
    }
   
    public function AddBankLocation(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'bank_name_id' =>'required',
            'site_name_id' =>'required',
            'siteID' =>'required',
            'address' =>'required',
        ]);
        $create = [
            'bank_name_id'=>$request->input('bank_name_id'),
            'site_name_id' => $request->input('site_name_id'),
            'siteID' => $request->input('siteID'),
            'address' => $request->input('address'),
        ];
        if($validator->fails()) {
            $response =[
                'success' =>false,
                'status' => 402,
                'message' =>"Please input all information",
            ];
            return response()->json($response, 402);
        }
        $data = DB::table('banklocations')->where('siteID','=', $request['siteID'])->count();
        if($data >0){
            $response = [
                'success' => false,
                'status' => 405,
                'message' => "This site ID already exits",
                'data' => $validator
            ]; 
            return response()->json($response, 405);
        }
        Banklocation::create($create);
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Create Bank location Successfully",
            'data' => $create
        ];
        return response()->json($response, 200);
    }
    public function UpdateBankLocation(Request $request,$id)
    {
        $update = [
            'bank_name_id'=>$request->input('bank_name_id'),
            'site_name_id' => $request->input('site_name_id'),
            'siteID' => $request->input('siteID'),
            'address' => $request->input('address'),
        ];
        $validator = Validator::make($request->all(),[
            'bank_name_id' =>'required',
            'site_name_id' =>'required',
            'siteID' =>'required',
            'address' =>'required',
        ]);
        if($validator->fails()) {
            $response =[
                'success' =>false,
                'status' => 402,
                'message' =>"Please input all information",
            ];
            return response()->json($response, 402);
        }
        $data = DB::table('banklocations')->where('siteID','=', $request['siteID'])
                                        ->where('id','!=',$id)
                                        ->count();
        if($data >0){
            $response = [
                'success' => false,
                'status' => 405,
                'message' => "This site ID already exits",
            ]; 
            return response()->json($response, 405);
        }

        $data1 = Banklocation::where('id','=',$id)->update($update);
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Updated Successfully",
            'data' => $data1
        ];
        return response()->json($response, 200);
    }
    public function getAll()
    {
        $data = DB::table('banklocations')
                    ->join('customers','banklocations.bank_name_id','=','customers.id')
                    ->join('sites','banklocations.site_name_id','=','sites.id')
                    ->select('customers.customer_name','sites.site_name', 'banklocations.*')
                    ->orderBy('id')->get();
        return $data;
    }
    public function getID($id)
    {
        $data = DB::table('banklocations')
                    ->join('customers','banklocations.bank_name_id','=','customers.id')
                    ->join('sites','banklocations.site_name_id','=','sites.id')
                    ->select('customers.customer_name','sites.site_name', 'banklocations.*')
                    ->where('banklocations.id','=',$id)->get();
                   
        return response()->json($data);
    }
}
