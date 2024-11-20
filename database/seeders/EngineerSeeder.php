<?php 

namespace Database\Seeders;

use App\Models\Engineer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EngineerSeeder extends Seeder
{
    public function run(){
        DB::table('engineers')->delete();
        $customers = [
              
                [
                    "engineer_name"=>"Piseth",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "engineer_name'"=>"Parinha",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
               
            ];
        Engineer::insert($customers);
    }
}