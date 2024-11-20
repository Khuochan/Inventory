<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'site'],function(){
    Route::post('addsite','SiteController@AddSite');
    Route::put('updatesite/{id}','SiteController@UpdateSite');
    Route::get('getallsite','SiteController@GetallSite');
    Route::delete('deletesite/{id}','SiteController@DeleteSite');

});