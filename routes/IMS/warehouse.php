 <?php

 use Illuminate\Support\Facades\Route;

 Route::group(['prefix'=>'warehouse'],function(){

    Route::post('addwarehouse','WarehouseCOntroller@AddWarehouse');
    Route::put('updatewarehouse/{id}','WarehouseController@UpdateWarehouse');
    Route::get('getallwarehouse','WarehouseController@GetallWarehouse');
 });