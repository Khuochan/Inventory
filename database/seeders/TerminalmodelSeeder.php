<?php 

namespace Database\Seeders;

use App\Models\Terminalmodel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TerminalmodelSeeder extends Seeder
{
    public function run(){
        DB::table('terminalmodels')->delete();
        $terminaltype = [
              
                [
                    "terminal_model"=>"DN100D",
                    "terminaltype_id"=>"1",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "terminal_model"=>"PC280",
                    "terminaltype_id"=>"1",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "terminal_model"=>"DN200V",
                    "terminaltype_id"=>"2",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "terminal_model"=>"DC365",
                    "terminaltype_id"=>"3",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "terminal_model"=>"DC365lx",
                    "terminaltype_id"=>"3",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
            ];
        Terminalmodel::insert($terminaltype);
    }
}