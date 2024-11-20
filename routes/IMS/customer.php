<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'customer'],function(){
    Route::post('addcustomer','CustomerController@AddCustomer');
    Route::put('updatecustomer/{id}','CustomerCOntroller@UpdateCustomer');
    Route::delete('deletecustomer/{id}','CustomerController@DeleteCustomer');
    Route::get('getallcustomer','CustomerController@GetallCustomer');
});