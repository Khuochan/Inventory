<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'attendance'], function () {
    //Apply leave
    Route::post('create', 'AttendanceController@create');
    Route::post('applyLeave', 'AttendanceController@applyLeave');
    Route::post('upload', 'AttendanceController@upload');
    Route::post('remaining/{id}', 'AttendanceController@Remaining');
    // Manage Leave
    Route::get('approveLeave/{id}', 'AttendanceController@approveLeave');
    Route::get('my_leave/{id}', 'AttendanceController@myleave');
    Route::put('update/{id}', 'AttendanceController@update');
    Route::delete('delete/{id}', 'AttendanceController@delete');
    Route::get('all', 'AttendanceController@all');
    Route::post('dataTable', 'AttendanceController@dataTable');
    Route::get('listTable', 'AttendanceController@listTable');
    // Route::post('upload', 'AttendanceController@store');
    Route::post('reject', 'AttendanceController@reject');
    Route::post('approve', 'AttendanceController@approve');
    Route::post('pending', 'AttendanceController@pending');

    Route::get('user', 'AttendanceController@getAccept');

    // leave view
    Route::get('leave_view', 'AttendanceController@leaveView');

    // view detail
    Route::get('leave_blance/{id}', 'AttendanceController@LeaveBalance');

    // export data
    Route::get('month_export/{data}', 'AttendanceController@MonthExport');
});
