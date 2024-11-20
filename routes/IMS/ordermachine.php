<?php 

use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'order'],function(){
    Route::post('addorder','OrdermachineController@addorder');
    Route::put('updateorder/{id}','OrdermachineController@updateorder');
    Route::get('getorder','OrdermachineController@getorder');
});