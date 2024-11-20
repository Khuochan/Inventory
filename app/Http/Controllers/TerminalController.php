<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Terminal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TerminalController extends Controller
{

    public function add(Request $request)
    {
        $validator = Validator::make($request -> all(),[
            'atm_id' =>'required',
            'serial_number'=>'required',
            'takeover_date' => 'required',
            'android_version'=>'required',
            'model_id'=>'required',
            'type_id'=>'required',
            'banklocation_id'=>'required',
            'category_id' =>'required',
            'status_id'=>'required',
            'warrenty'=>'required',
        ]);
        if($validator ->fails()){
            $response =[
                'success' =>false,
                'status' => 402,
                'message' =>"Please input all required information",
            ];
            return response()->json($response,402);
        }
        $input = $request->all();
        $data = DB::table('terminals')->where('atm_id','=',$request['atm_id'])->count();
        if($data >0){
            $response =[
                'success' =>false,
                'status' => 403,
                'message' =>"This atm ID alredy exit",
            ];
            return response()->json($response,403);
        }
        $data1 = DB::table('terminals')->where('serial_number','=',$request['serail_number'])->count();
        if($data1 >0){
            $response =[
                'success' =>false,
                'status' => 405,
                'message' =>"This serial number alredy exit",
            ];
            return response()->json($response);
        }
        Terminal::create($input);
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Add new terminal successfully",
            'data' => $input
        ];
        return response()->json($response);
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request -> all(),[
            'atm_id' =>'required',
            'serial_number'=>'required',
            'takeover_date' => 'required',
            'android_version'=>'required',
            'model_id'=>'required',
            'type_id'=>'required',
            'banklocation_id'=>'required',
            'category_id' =>'required',
            'status_id'=>'required',
            'warrenty'=>'required',
        ]);
        if($validator ->fails()){
            $response =[
                'success' =>false,
                'status' => 402,
                'message' =>"Please input all required information",
            ];
            return response()->json($response,402);
        }
        $input = $request->all();
        $data = DB::table('terminals')->where('atm_id','=',$request['atm_id'])
                                        ->where('id','!=',$id)->count();

        if($data >0){
            $response =[
                'success' =>false,
                'status' => 403,
                'message' =>"This atm ID alredy exit",
            ];
            return response()->json($response,403);
        }
        Terminal::where('id','=',$id)->update($input);
        $response = [
            'success' => true,
            'status' => 200,
            'message' => "Updated terminal successfully",
            'data' => $input
        ];
        return response()->json($response);
    }
    public function get()
    {
        $data =  DB::table('terminals')
                ->join('banklocations','banklocations.id','=','terminals.banklocation_id')
                ->join('sites','sites.id','=','banklocations.site_name_id')
                ->join('customers','customers.id','=','banklocations.bank_name_id')
                ->join('terminalmodels','terminalmodels.id','=','terminals.model_id')
                ->join('terminaltypes','terminaltypes.id','=','terminals.type_id')
                ->join('categories','categories.id','=','terminals.category_id')
                ->join('terminalstatuses','terminalstatuses.id','=','terminals.status_id')
                ->select('customers.customer_name',
                            'banklocations.siteID',
                            'banklocations.address',
                            'sites.site_name',
                            'terminalmodels.terminal_model',
                            'terminaltypes.terminal_type',
                            'categories.category_name',
                            'terminalstatuses.status',
                            'terminals.*',)
                ->orderBy('terminals.id')->get();
                return response()->json($data);
    }
    public function getID($id)
    {

        $data =  DB::table('terminals')->where('terminals.id','=',$id)->get();
        return response()->json($data);
    }
    public function getViewdetail($id)
    {
        $data =  DB::table('terminals')
        ->join('banklocations','banklocations.id','=','terminals.banklocation_id')
        ->join('customers','customers.id','=','banklocations.bank_name_id')
        ->join('sites','sites.id','=','banklocations.site_name_id')
        ->join('terminalmodels','terminalmodels.id','=','terminals.model_id')
        ->join('terminaltypes','terminaltypes.id','=','terminalmodels.terminaltype_id')
        ->join('categories','categories.id','=','terminals.category_id')
        ->join('terminalstatuses','terminalstatuses.id','=','terminals.status_id')
        ->select('customers.customer_name',
                    'banklocations.siteID',
                    'banklocations.address',
                    'sites.site_name',
                    'terminalmodels.terminal_model',
                    'terminaltypes.terminal_type',
                    'categories.category_name',
                    'terminalstatuses.status',
                    'terminals.*',)
        ->where('terminals.id','=',$id)->get();
        return response()->json($data);
    }

    public function CountTerminal() {
        $users = Terminal::all();
        $count = $users->count();
        $atm = DB::table('terminals')->where('type_id', '=', 1)->count();
        $crm = DB::table('terminals')->where('type_id', '=', 2)->count();
        $dc = DB::table('terminals')->where('type_id', '=', 3)->count();

        return response()->json([
            'terminal' => $count,
            'atm' => $atm,
            'crm' => $crm,
            'dc' => $dc,
            'data' => $users
        ]);
    }

    public function PieChart(){
        $amk = DB::table('terminals')
            ->join('banklocations','banklocations.id','=','terminals.banklocation_id')
            ->where('bank_name_id', '=', 2)->count();
        $wing = DB::table('terminals')
            ->join('banklocations','banklocations.id','=','terminals.banklocation_id')
            ->where('bank_name_id', '=', 5)->count();
        $aba = DB::table('terminals')
            ->join('banklocations','banklocations.id','=','terminals.banklocation_id')
            ->where('bank_name_id', '=', 1)->count();
        $phillip = DB::table('terminals')
            ->join('banklocations','banklocations.id','=','terminals.banklocation_id')
            ->where('bank_name_id', '=', 4)->count();
        $bti = DB::table('terminals')
            ->join('banklocations','banklocations.id','=','terminals.banklocation_id')
            ->where('bank_name_id', '=', 3)->count();
        return response()->json([
            'amk' => $amk,
            'wing' => $wing,
            'aba' => $aba,
            'phillip' => $phillip,
            'bti' => $bti
        ]);
    }

    public function getFilter(Request $request)
    {
       if($request->model_id && $request->type_id && $request->bank_id){
        $data =  DB::table('terminals')
        ->join('banklocations','banklocations.id','=','terminals.banklocation_id')
        ->join('customers','customers.id','=','banklocations.bank_name_id')
        ->join('sites','sites.id','=','banklocations.site_name_id')
        ->join('terminalmodels','terminalmodels.id','=','terminals.model_id')
        ->join('terminaltypes','terminaltypes.id','=','terminalmodels.terminaltype_id')
        ->join('categories','categories.id','=','terminals.category_id')
        ->join('terminalstatuses','terminalstatuses.id','=','terminals.status_id')
        ->select('customers.customer_name',
                    'banklocations.siteID',
                    'banklocations.address',
                    'banklocations.bank_name_id',
                    'sites.site_name',
                    'terminalmodels.terminal_model',
                    'terminaltypes.terminal_type',
                    'categories.category_name',
                    'terminalstatuses.status',
                    'terminals.*',)
        ->where('terminals.model_id','=',$request->model_id)
        ->where('banklocations.bank_name_id','=',$request->bank_id)
        ->where('terminals.type_id','=',$request->type_id)
        ->orderBy('terminals.id')->get();
        return response()->json($data);
       }
       else if($request->model_id && $request->type_id ){
        $data =  DB::table('terminals')
        ->join('banklocations','banklocations.id','=','terminals.banklocation_id')
        ->join('customers','customers.id','=','banklocations.bank_name_id')
        ->join('sites','sites.id','=','banklocations.site_name_id')
        ->join('terminalmodels','terminalmodels.id','=','terminals.model_id')
        ->join('terminaltypes','terminaltypes.id','=','terminalmodels.terminaltype_id')
        ->join('categories','categories.id','=','terminals.category_id')
        ->join('terminalstatuses','terminalstatuses.id','=','terminals.status_id')
        ->select('customers.customer_name',
                    'banklocations.siteID',
                    'banklocations.address',
                    'banklocations.bank_name_id',
                    'sites.site_name',
                    'terminalmodels.terminal_model',
                    'terminaltypes.terminal_type',
                    'categories.category_name',
                    'terminalstatuses.status',
                    'terminals.*',)
        ->where('terminals.model_id','=',$request->model_id)
        ->where('terminals.type_id','=',$request->type_id)
        ->orderBy('terminals.id')->get();
        return response()->json($data);
       }
       else if($request->model_id && $request->bank_id ){
        $data =  DB::table('terminals')
        ->join('banklocations','banklocations.id','=','terminals.banklocation_id')
        ->join('customers','customers.id','=','banklocations.bank_name_id')
        ->join('sites','sites.id','=','banklocations.site_name_id')
        ->join('terminalmodels','terminalmodels.id','=','terminals.model_id')
        ->join('terminaltypes','terminaltypes.id','=','terminalmodels.terminaltype_id')
        ->join('categories','categories.id','=','terminals.category_id')
        ->join('terminalstatuses','terminalstatuses.id','=','terminals.status_id')
        ->select('customers.customer_name',
                    'banklocations.siteID',
                    'banklocations.address',
                    'banklocations.bank_name_id',
                    'sites.site_name',
                    'terminalmodels.terminal_model',
                    'terminaltypes.terminal_type',
                    'categories.category_name',
                    'terminalstatuses.status',
                    'terminals.*',)
        ->where('terminals.model_id','=',$request->model_id)
        ->where('banklocations.bank_name_id','=',$request->bank_id)
        ->orderBy('terminals.id')->get();
        return response()->json($data);
       }
       else if($request->type_id && $request->bank_id)
       {
        $data =  DB::table('terminals')
        ->join('banklocations','banklocations.id','=','terminals.banklocation_id')
        ->join('customers','customers.id','=','banklocations.bank_name_id')
        ->join('sites','sites.id','=','banklocations.site_name_id')
        ->join('terminalmodels','terminalmodels.id','=','terminals.model_id')
        ->join('terminaltypes','terminaltypes.id','=','terminalmodels.terminaltype_id')
        ->join('categories','categories.id','=','terminals.category_id')
        ->join('terminalstatuses','terminalstatuses.id','=','terminals.status_id')
        ->select('customers.customer_name',
                    'banklocations.siteID',
                    'banklocations.address',
                    'banklocations.bank_name_id',
                    'sites.site_name',
                    'terminalmodels.terminal_model',
                    'terminaltypes.terminal_type',
                    'categories.category_name',
                    'terminalstatuses.status',
                    'terminals.*',)
        ->where('banklocations.bank_name_id','=',$request->bank_id)
        ->where('terminals.type_id','=',$request->type_id)
        ->orderBy('terminals.id')->get();
        return response()->json($data);
       }
       else if($request->type_id)
       {
        $data =  DB::table('terminals')
        ->join('banklocations','banklocations.id','=','terminals.banklocation_id')
        ->join('customers','customers.id','=','banklocations.bank_name_id')
        ->join('sites','sites.id','=','banklocations.site_name_id')
        ->join('terminalmodels','terminalmodels.id','=','terminals.model_id')
        ->join('terminaltypes','terminaltypes.id','=','terminalmodels.terminaltype_id')
        ->join('categories','categories.id','=','terminals.category_id')
        ->join('terminalstatuses','terminalstatuses.id','=','terminals.status_id')
        ->select('customers.customer_name',
                    'banklocations.siteID',
                    'banklocations.address',
                    'banklocations.bank_name_id',
                    'sites.site_name',
                    'terminalmodels.terminal_model',
                    'terminaltypes.terminal_type',
                    'categories.category_name',
                    'terminalstatuses.status',
                    'terminals.*',)
        ->where('terminals.type_id','=',$request->type_id)
        ->orderBy('terminals.id')->get();
        return response()->json($data);
       }
       else if($request->model_id)
       {
        $data =  DB::table('terminals')
        ->join('banklocations','banklocations.id','=','terminals.banklocation_id')
        ->join('customers','customers.id','=','banklocations.bank_name_id')
        ->join('sites','sites.id','=','banklocations.site_name_id')
        ->join('terminalmodels','terminalmodels.id','=','terminals.model_id')
        ->join('terminaltypes','terminaltypes.id','=','terminalmodels.terminaltype_id')
        ->join('categories','categories.id','=','terminals.category_id')
        ->join('terminalstatuses','terminalstatuses.id','=','terminals.status_id')
        ->select('customers.customer_name',
                    'banklocations.siteID',
                    'banklocations.address',
                    'banklocations.bank_name_id',
                    'sites.site_name',
                    'terminalmodels.terminal_model',
                    'terminaltypes.terminal_type',
                    'categories.category_name',
                    'terminalstatuses.status',
                    'terminals.*',)
        ->where('terminals.model_id','=',$request->model_id)
        ->orderBy('terminals.id')->get();
        return response()->json($data);
       }
       else if($request->bank_id)
       {
        $data =  DB::table('terminals')
        ->join('banklocations','banklocations.id','=','terminals.banklocation_id')
        ->join('customers','customers.id','=','banklocations.bank_name_id')
        ->join('sites','sites.id','=','banklocations.site_name_id')
        ->join('terminalmodels','terminalmodels.id','=','terminals.model_id')
        ->join('terminaltypes','terminaltypes.id','=','terminalmodels.terminaltype_id')
        ->join('categories','categories.id','=','terminals.category_id')
        ->join('terminalstatuses','terminalstatuses.id','=','terminals.status_id')
        ->select('customers.customer_name',
                    'banklocations.siteID',
                    'banklocations.address',
                    'banklocations.bank_name_id',
                    'sites.site_name',
                    'terminalmodels.terminal_model',
                    'terminaltypes.terminal_type',
                    'categories.category_name',
                    'terminalstatuses.status',
                    'terminals.*',)
        ->where('banklocations.bank_name_id','=',$request->bank_id)
        ->orderBy('terminals.id')->get();
        return response()->json($data);
       }
       $data =  DB::table('terminals')
       ->join('banklocations','banklocations.id','=','terminals.banklocation_id')
       ->join('sites','sites.id','=','banklocations.site_name_id')
       ->join('customers','customers.id','=','banklocations.bank_name_id')
       ->join('terminalmodels','terminalmodels.id','=','terminals.model_id')
       ->join('terminaltypes','terminaltypes.id','=','terminalmodels.terminaltype_id')
       ->join('categories','categories.id','=','terminals.category_id')
       ->join('terminalstatuses','terminalstatuses.id','=','terminals.status_id')
       ->select('customers.customer_name',
                   'banklocations.siteID',
                   'banklocations.address',
                   'sites.site_name',
                   'terminalmodels.terminal_model',
                   'terminaltypes.terminal_type',
                   'categories.category_name',
                   'terminalstatuses.status',
                   'terminals.*',)
       ->orderBy('terminals.id')->get();
       return response()->json($data);
    }

}
