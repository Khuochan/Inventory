<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'categorie'],function(){
    Route::post('add','CategoryController@AddCategory');
    Route::get('allcategory','CategoryController@getALLcategory');
    Route::put('update/{id}','CategoryController@UpdateCategoty');
    
});