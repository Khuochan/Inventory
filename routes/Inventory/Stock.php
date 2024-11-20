<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'stock'], function () {
    Route::post('add-stock', 'StockController@addStock');
    // Route::post('add-stock', 'StockController@store'); // add multiple need to study
    Route::put('update/{id}', 'StockController@update');
    Route::delete('delete/{id}', 'StockController@delete');
    Route::get('getall', 'StockController@all');
    Route::get('get-spare', 'StockController@GetSpare');

    Route::get('one-stock/{sparepart_id}', 'StockController@getStockID');
    Route::get('one-serial/{sparepart_id}', 'StockController@getSerial');
});
