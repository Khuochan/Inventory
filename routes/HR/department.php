<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'department'], function () {
    Route::post('create', 'DepartmentController@create');
    Route::put('update/{id}', 'DepartmentController@update');
    Route::delete('delete/{id}', 'DepartmentController@delete');
    Route::get('Deptall', 'DepartmentController@all');
    Route::post('dataTable', 'DepartmentController@dataTable');
    Route::get('listTable', 'DepartmentController@listTable');

    //Dashbord
    Route::get('DeptCount', 'DepartmentController@countDept');
});
