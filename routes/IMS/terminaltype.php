<?php 

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'terminaltype'], function(){
    Route::post('addtype','TerminaltypeController@Addtype');
    Route::put('updatetype/{id}', 'TerminaltypeCOntroller@Updatetype');
    Route::get('allterminaltype', 'TerminaltypeController@Getallterminaltype');
    Route::delete('deletetype/{id}','TerminaltypeController@Detetetype');
});