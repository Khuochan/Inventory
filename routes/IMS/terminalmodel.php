<?php 

use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'terminalmodel'], function(){
     Route::post('addmodel', 'TerminalmodelController@AddModel');
     Route::put('updatemodel/{id}', 'TerminalmodelController@UpdateModel');
     Route::get('getallmodel', 'TerminalmodelController@GetAllModel');
});