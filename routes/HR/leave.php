<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'leave'], function () {
    Route::post('create', 'LeaveController@create');
    Route::put('update/{id}', 'LeaveController@update');
    Route::delete('delete/{id}', 'LeaveController@delete');
    Route::get('Deptall', 'LeaveController@all');
    Route::post('dataTable', 'LeaveController@dataTable');
    Route::get('listTable', 'LeaveController@listTable');

});
