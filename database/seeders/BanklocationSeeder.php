<?php

namespace Database\Seeders;

use App\Models\Banklocation;
use Illuminate\Database\Seeder;


class BanklocationSeeder extends Seeder
{
    public function run(): void{
        Banklocation::truncate();
        $datas = fopen(base_path('database/csv/production_banklocation.csv'), 'r');
        $transRow = true;
  
        while(($data = fgetcsv($datas,'1000', ',')) !== false){
          if(!$transRow){
            Banklocation::create([
  
                'bank_name_id' => $data[0],
                'site_name_id' => $data[1],
                'siteID' => $data[2],
                'address' => $data[3],
            ]);
          }
          $transRow = false;
        }
        fclose($datas);
      }
}