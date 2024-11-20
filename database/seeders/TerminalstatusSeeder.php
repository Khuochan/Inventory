<?php 

namespace Database\Seeders;

use App\Models\Terminalstatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TerminalstatusSeeder extends Seeder
{
    public function run(){
        DB::table('terminalstatuses')->delete();
        $customers = [
              
                [
                    "status"=>"Active",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "status"=>"Non-active",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
              
            
            ];
      Terminalstatus::insert($customers);
    }
}