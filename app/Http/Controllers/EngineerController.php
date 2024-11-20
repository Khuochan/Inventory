<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Engineer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EngineerController extends Controller
{
    public function addEngineer(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'engineer_name' => 'required',
        ]);
        if($validator -> failed()){
            $response =[
                'success' =>false,
                'status' =>402,
                'message' => "Please input information",
            ];
            return response()->json($response,402);
        }
        $create =['engineer_name'=>$request->input('engineer_name'),];
        $data = DB::table('engineers')->where('engineer_name','=',$request['engineer_name'])->count();
        if($data >0){
            $response =[
                'success' =>false,
                'status' =>403,
                'message' => "This engineer is exit",
            ];
            return response()->json($response,403);
        }
        Engineer::create($create);
        $response =[
            'success' =>true,
            'status' =>200,
            'message' => "Engineer is created successfully",
            'data' =>$create
        ];
        return response()->json($response,200);
    }
    public function updateEngineer(Request $request, $id){

        $update = [
            'engineer_name'=>$request->input('engineer_name'),
        ];
        if($request->engineer_name == null){
            $response = [
                'success' => false,
                'status' => 403,
                'message' => "Please input information",
            ];
            return response()->json($response, 403);
        }
    
        $data = DB::table('engineers')->where('engineer_name','=',$request['engineer_name'])->count();
        if($data >0){
            $response = [
                'success' => false,
                'status' => 405,
                'message' => "This engineer is already exit",
            ];
            return response()->json($response, 405);
        }
        $data1 = Engineer::where('id',$id)->update($update);
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Updated successfully",
            'data'=> $update,
        ];
        return response()->json($response, 200);
    }
    public function getEngineer()
    {
        $data = DB::table('engineers')->orderBy('id')->get();

        return $data;
    }
    public function deleteEngineer($id)
    {
        Engineer::where('id',$id)->delete();
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Delete engineer successfully",
        ];
        return response()->json($response, 200);
    }
        
    
}
