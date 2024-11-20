<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    
    public function AddSite(Request $request)
    {
        $create =[
            'site_name' =>$request->input('site_name'),
        ];
        if($request->site_name == null){
            $response = [
                'success' => false,
                'status' => 401,
                'message' => "Please input information",
            ];
            return response()->json($response, 401);
        }
        $data = DB::table('sites')->where('site_name','=',$request['site_name'])->count();
        if($data > 0){
            $response = [
                'success' => false,
                'status' => 402,
                'message' => "This site already exit",
            ];
            return response()->json($response, 402);
        }
            Site::create($create);
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Add site successfully",
            'data' =>$create,
        ];
        return response()->json($response, 200);
    }
    public function UpdateSite(Request $request, $id)
    {
        $update =[
            'site_name' =>$request->input('site_name'),
        ];
        if($request->site_name == null){
            $response = [
                'success' => false,
                'status' => 401,
                'message' => "Please input information",
            ];
            return response()->json($response, 401);
        }
        $data1 = DB::table('sites')->where('site_name','=',$request['site_name'])->count();
        if($data1 > 0){
            $response = [
                'success' => false,
                'status' => 402,
                'message' => "This site already exit",
            ];
            return response()->json($response, 402);
        }
        $data = Site::where('id','=',$id)->update($update);
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Updated  site successfully",
        ];
        return response()->json($response, 200);
    }
    public function DeleteSite($id)
    {
        $data = DB::table('banklocations')->where('site_name_id','=',$id)->count();
        if($data > 0){
            $response = [
                'success' => false,
                'status' => 402,
                'message' => "You cannot delete this site",
            ];
            return response()->json($response, 402);
        }
       
        Site::where('id',$id)->delete();
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Delete site successfully",
        ];
        return response()->json($response, 200);

    }
    public function GetallSite()
    {
        $data = DB::table('sites')->orderBy('id')->get();
        return $data;
    }
}
