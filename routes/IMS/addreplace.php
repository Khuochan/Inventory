<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' =>'replace'],function(){
    Route::post('add','AddreplaceController@add');
    Route::put('update/{id}','AddreplaceController@updateReplace');
    Route::get('getdata','AddreplaceController@getData');

});