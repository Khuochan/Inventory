<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'sub-item'], function () {
    Route::get('get-condition', 'SubItemController@getCondtion');
    Route::get('get-defect', 'SubItemController@getDefect');
    Route::get('get-status', 'SubItemController@getStatus');
    Route::get('get-usage', 'SubItemController@getUsage');
    Route::get('get-status-job', 'SubItemController@getStatusJob');
    Route::get('get-status-repaire', 'SubItemController@getStatusRepaire');
    Route::get('get-inventory', 'SubItemController@CountInventory');
});
