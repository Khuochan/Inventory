<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'status'],function(){
    Route::post('add','TerminalstatusController@addStatus');
    Route::put('update/{id}','TerminalstatusController@updateStatus');
    Route::get('all','TerminalstatusController@getAllStatus');
});
