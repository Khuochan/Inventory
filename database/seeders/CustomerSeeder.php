<?php 

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run(){
        DB::table('customers')->delete();
        $customers = [
              
                [
                    "customer_name"=>"ABA Bank",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "customer_name"=>"AMK MFI",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "customer_name"=>"BTI Share",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "customer_name"=>"Philip Bank",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "customer_name"=>"WingBank",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
            ];
        Customer::insert($customers);
    }
}