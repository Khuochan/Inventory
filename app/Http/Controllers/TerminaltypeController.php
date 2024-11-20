<?php

namespace App\Http\Controllers;

use App\Models\Terminaltype;
use App\Services\TerminaltypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TerminaltypeController extends ParentController
{
    public function __construct(
        Terminaltype $terminaltype,
        TerminaltypeService $terminaltypeService,
    )
    {
        $this -> model = $terminaltype;
        $this->service= $terminaltypeService;
    }
    public function Addtype(Request $request):JsonResponse
    {
        return parent::create($request);
    }
    public function Updatetype(Request $request, $id): JsonResponse
    {
        return parent::update($request, $id);
    }
    public function Detetetype($id)
    {
        return parent::delete($id);
    }
    public function Getallterminaltype()
    {
         $data = DB::table('terminaltypes')->orderBy('id')->get();

         return $data;
    }
}
