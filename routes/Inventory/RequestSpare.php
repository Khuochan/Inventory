<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'request'], function () {
    Route::post('request-spare', 'RequestSpareController@RequestSpare');
    Route::post('request-multiple', 'RequestSpareController@RequestMultiple');
    // Route::put('update/{id}', 'StockController@update');
    // Route::delete('delete/{id}', 'StockController@delete');
    Route::get('getall', 'RequestSpareController@all');
    Route::post('approve-request', 'RequestSpareController@ApproveRequest');
    Route::post('reject-request', 'RequestSpareController@RejectRequest');

    Route::get('get-request', 'RequestSpareController@allRequest');
    Route::get('one-request/{request_id}', 'RequestSpareController@OneRequest');
});
