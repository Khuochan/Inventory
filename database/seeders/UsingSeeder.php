<?php
namespace Database\Seeders;

use App\Models\Using;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsingSeeder extends Seeder
{
  public function run(): void{
      Using::truncate();
      $datas = fopen(base_path('database/csv/v5.csv'), 'r');
      $transRow = true;

      while(($data = fgetcsv($datas,'1000', ',')) !== false){
        if(!$transRow){
          Using::create([

            'atm_id' => $data[0],
            'alias_id' => $data[1],
            'site_id' => $data[2],
            'site_name' => $data[3],
            'address'=>$data[4],
            'city' => $data[5],
            'state_name' => $data[6],
            'region_name' => $data[7],
            'location' => $data[8],
            'accessibility'=>$data[9],
            'install_date'=>$data[10],
            'delivery_date' => $data[11],
            'take_over_date' => $data[12],
            'category_name' => $data[13],
            'model_name' => $data[14],
            'serial_number'=>$data[15],
            'type_name' => $data[16],
            'warranty_days' => $data[17],
            'bank_name' => $data[18],
            // 'user_id' => $data[19],
            'status'=>$data[20],

          ]);
        }
        $transRow = false;
      }
      fclose($datas);
    }
}
