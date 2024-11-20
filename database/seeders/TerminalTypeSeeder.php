<?php 

namespace Database\Seeders;

use App\Models\Terminaltype;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TerminalTypeSeeder extends Seeder
{
    public function run(){
        DB::table('terminaltypes')->delete();
        $terminaltype = [
              
                [
                    "terminal_type"=>"ATM",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "terminal_type"=>"CRM",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "terminal_type"=>"DC365",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
            ];
        Terminaltype::insert($terminaltype);
    }
}