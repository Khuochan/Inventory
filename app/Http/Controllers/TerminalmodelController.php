<?php

namespace App\Http\Controllers;

use App\Models\Terminalmodel;
use App\Services\TerminalmodelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 use Illuminate\Support\Facades\Validator;

class TerminalmodelController extends ParentController
{
    public function __construct(
        Terminalmodel $terminalmodel,
        TerminalmodelService $terminalmodelService,
    )
    {
        $this ->model = $terminalmodel;
        $this ->service = $terminalmodelService; 
    }
    public function AddModel(Request $request)
    {   
        
        $valitateData = $request->validate([
            'terminaltype_id' =>'required',
            'terminal_model' =>'required',
        ]);
        $data = DB::table('terminalmodels')
                    ->where('terminaltype_id', '=',$request['terminaltype_id'])
                    ->Where('terminal_model','=',$request['terminal_model'])
                    ->count();
        if($data > 0){
            return "This model is exits"; 
        }
        return parent::create($request);
    }
    public function UpdateModel(Request $request, $id)
    {
        $data = DB::table('terminalmodels')
                    ->where('terminaltype_id', '=',$request['terminaltype_id'])
                    ->Where('terminal_model','=',$request['terminal_model'])
                    ->count();
        if($data > 0)
        {
             return "This model is exits"; 
        }
        return parent::update($request, $id);
    }
    public function DeleteModel($id)
    {
        return parent::delete($id);
    }
    public function GetAllModel()
    {
    //    $data = DB ::table('terminalmodels')
    //             ->join('terminaltypes','terminaltypes.id', '=','terminalmodels.terminaltype_id')
    //             ->select('terminaltypes.terminal_type', 'terminalmodels.*')
    //             ->where('terminaltypes.id', '=', $id)
    //             ->orderBy('terminalmodels.id')
    //             ->get();
    $data = DB::table('terminalmodels')->orderBy('id')->get();
        return $data;
    }
}
