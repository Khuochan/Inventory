<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' =>'terminal'], function(){
    Route::post('add', 'TerminalController@add');
    Route::put('update/{id}','TerminalController@update');
    Route::get('get','TerminalController@get');
    Route::get('getid/{id}','TerminalController@getID');
    Route::get('getviewdetail/{id}','TerminalController@getViewdetail');
    Route::post('filter','TerminalController@getFilter');

    // Dashboard
    Route::get('countTerminal','TerminalController@CountTerminal');
    Route::get('pieChart','TerminalController@PieChart');
});
