<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'role'], function () {
    Route::post('create', 'UserRoleController@create');
    Route::put('update/{id}', 'UserRoleController@update');
    Route::delete('delete/{id}', 'UserRoleController@delete');

    Route::get('listTable', 'UserRoleController@listTable');
    Route::get('roleUsers', 'UserRoleController@RoleUser');
    Route::get('Users', 'UserRoleController@User');
    Route::get('Directors', 'UserRoleController@Director');
    Route::put('updateUser/{id}', 'UserRoleController@UpdateUser');

    Route::post('assignRole', 'UserRoleController@assignRole');
});
