<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'leave_annual'], function () {
    Route::post('create', 'LeaveAnnualController@create');
    Route::put('update/{id}', 'LeaveAnnualController@update');
    Route::delete('delete/{id}', 'LeaveAnnualController@delete');
    Route::post('dataTable', 'LeaveAnnualController@dataTable');
    Route::get('getLeaveStaff/{id}', 'LeaveAnnualController@getLeaveStaff');
    Route::get('filterLeave/{id}', 'LeaveAnnualController@filterLeave');

    Route::get('years', 'LeaveAnnualController@getYear');
});
