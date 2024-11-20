<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'sparepart'], function () {
    Route::post('create', 'SparepartController@create');
    Route::put('update/{id}', 'SparepartController@update');
    Route::delete('delete/{id}', 'SparepartController@delete');
    Route::get('getall', 'SparepartController@GetSpare');

    Route::get('get-spare', 'SparepartController@all');
});
