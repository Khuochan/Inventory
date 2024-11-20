<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends ParentController
{
    public function __construct(
        Customer $customer,
        CustomerService $customerService,
    ) {
        $this->model = $customer;
        $this->service = $customerService;
    }
    public function AddCustomer(Request $request)
    {
        $create=[
            'customer_name' => $request->input('customer_name'),
        ];
        if($request->customer_name == null){
            $response = [
                'success' => false,
                'status' => 403,
                'message' => "Please input information",
            ];
            return response()->json($response, 400);
        }
        $data = DB::table('customers')
                ->where('customer_name', '=', $request['customer_name'])->count();
        if ($data > 0) {
            $response = [
                'success' => false,
                'status' => 400,
                'message' => "This customer is exits",
            ];
            return response()->json($response, 400);
        }
        $data1 = Customer::create($create);
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Create Successfully",
            'data' => $data1
        ];
        return response()->json($response, 200);
    }
    public function UpdateCustomer(Request $request, $id)
    {   
        $update =[
            'customer_name' => $request->input('customer_name'),
        ];
        if($request->customer_name == null){
            $response = [
                'success' => false,
                'status' => 403,
                'message' => "Please input information",
            ];
            return response()->json($response, 400);
        }
        $data = DB::table('customers')
            ->where('customer_name', '=', $request['customer_name'])->count();
        if ($data > 0) {
            $response = [
                'success' => false,
                'status' => 401,
                'message' => "This customer is exits",
            ];
            return response()->json($response, 401);
        }
        $data1 = Customer::where('id','=',$id)->update($update);
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Updated Successfully",
            'data' => $data1
        ];
        return response()->json($response, 200);
    }
    public function DeleteCustomer($id)
    {
        return parent::delete($id);
    }
    public function GetallCustomer()
    {
        $data = DB::table('customers')->orderBy('id')->get();
        return $data;
    }
}
