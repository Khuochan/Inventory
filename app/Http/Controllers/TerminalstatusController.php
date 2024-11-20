<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Terminalstatus;
use Illuminate\Support\Facades\Redis;

class TerminalstatusController extends Controller
{
    public function addStatus(Request $request)
    {
        $create = [
            'status' =>$request->input('status'),
        ];
        if($request->status == null){
            $response = [
                'success' => false,
                'status' => 401,
                'message' => "Please input information",
            ];
            return response()->json($response, 401);
        }
        $data = DB::table('terminalstatuses')->where('status','=',$request['status'])->count();
        if( $data> 0){
            $response = [
                'success' => false,
                'status' => 402,
                'message' => "This status already exit",
            ];
            return response()->json($response, 402);
        }
        Terminalstatus::create($create);
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Add new status successfully",
            'data'=>$create,
        ];
        return response()->json($response, 200);

    }
    public function updateStatus(Request $request, $id)
    {
        $update = [
            'status' =>$request->input('status'),
        ];
        if($request->status == null){
            $response = [
                'success' => false,
                'status' => 401,
                'message' => "Please input information",
            ];
            return response()->json($response, 401);
        }
        $data = DB::table('terminalstatuses')->where('status','=',$request['status'])->count();
        if( $data> 0){
            $response = [
                'success' => false,
                'status' => 402,
                'message' => "This status already exit",
            ];
            return response()->json($response, 402);
        }
        Terminalstatus::where('id','=',$id)->update($update);
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Updated status successfully",
            'data'=>$update,
        ];
        return response()->json($response, 200);
    }
    public function getAllStatus()
    {
        $data = Terminalstatus::orderBy('id')->get();

        return response()->json($data);
    }
}
