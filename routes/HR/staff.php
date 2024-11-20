<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'staff'], function () {
    Route::post('create', 'StaffController@create');
    Route::put('update/{id}', 'StaffController@update');
    Route::delete('delete/{id}', 'StaffController@delete');
    Route::post('dataTable', 'StaffController@dataTable');
    Route::get('all', 'StaffController@all');
    Route::get('getAll', 'StaffController@getALL');
    Route::get('listTable', 'StaffController@ListTable');
    Route::get('getStaff/{id}', 'StaffController@getStaff');

    Route::post('reset/{id}', 'StaffController@reset');

    // Dashbord
    Route::get('userCount', 'StaffController@countUsers');
    Route::get('notification/{id}', 'StaffController@notification');
    //upload image of user
    Route::post('uploadImg', 'StaffController@uploadImg');

    // HR_V2
    Route::post('active/{id}', 'StaffController@active');
    Route::post('import', 'StaffController@test');
});
