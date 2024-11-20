<?php 

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(){
        DB::table('categories')->delete();
        $customers = [
              
                [
                    "category_name"=>"Broze",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "category_name"=>"Gold",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "category_name"=>"Platinum",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "category_name"=>"Silver",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
            
            ];
        Category::insert($customers);
    }
}