<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
{
    public function AddCategory(Request $request)
    {
        $create = [
            'category_name' => $request->input('category_name'),
        ];
        Category::create($create);
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Create Category Successfully",
            'data' => $create
        ];
        return response()->json($response, 200);

    }
    public function UpdateCategoty(Request $request,$id)
    {
        $update = [
            'category_name' => $request->input('category_name'),
        ];
        Category::where('id',$id)->update($update);
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Updated Category Successfully",
            'data' => $update
        ];
        return response()->json($response, 200);
    }
    public function Delete($id)
    {
        Category::where('id',$id)->delete();
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Deleted Category Successfully",
           
        ];
        return response()->json($response, 200);
    }
    public function getALLcategory()
    {
        $data = DB::table('categories')->orderBy('id')->get();
        
        return response()->json(['status'=>200,'data'=>$data]); 
    }

    
}
