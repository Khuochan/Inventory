<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'return'], function () {
    Route::post('add-job', 'ReturnSpareController@JobSheet');
    Route::post('return-spare', 'ReturnSpareController@ReturnSpare');

    // Route::post('return-stock', 'ReturnSpareController@ReturnStock');
    Route::get('list-job', 'ReturnSpareController@all');
    Route::post('close-job', 'ReturnSpareController@CloseJob');
    Route::get('list-repaire', 'ReturnSpareController@ListRepaire');
    Route::post('stock-transfer', 'ReturnSpareController@StockTransfer');
});
