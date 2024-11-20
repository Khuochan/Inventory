<?php

use App\Http\Controllers\EngineerController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' =>'engineer'],function(){
    Route::post('addengineer','EngineerController@addEngineer');
    Route::put('updateengineer/{id}','EngineerController@updateEngineer');
    Route::get('all','EngineerController@getEngineer');
    Route::delete('deleteengineer/{id}', 'EngineerController@deleteEngineer');
});