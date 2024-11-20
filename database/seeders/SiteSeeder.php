<?php 
namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SiteSeeder extends Seeder
{
    public function run()
    {
          DB::table('sites')->delete();
        $sites =[
                [
                    "site_name"=>"2nd ATM",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "site_name"=>"AEON 2",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "site_name"=>"AEON 3",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "site_name"=>"Branch Site",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "site_name"=>"LOBBY",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                 [
                    "site_name"=>"MALL",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                 [
                    "site_name"=>"OffSite",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                [
                    "site_name"=>"OnSite",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
                 [
                    "site_name"=>"UAT",
                    "created_at"=>"03/13/2023",
                    "updated_at"=> "03/26/2023",
                ],
           
         ];
          Site::insert($sites);
    }
}