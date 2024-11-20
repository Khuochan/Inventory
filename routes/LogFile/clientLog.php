<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'clientLog'], function () {
    // Route::post('upload-file', 'LogFile@uploadfile'); // update in version 2 , file big size
    // Route::get('getData', 'ClientLogFileController@getAll'); // update in version 2
    Route::post('list-qr', 'ClientLogFileController@QRScanLog_date');
    Route::post('qrServer', 'ClientLogFileController@QRScanLog_time'); // work 2.1
    Route::post('getData', 'ClientLogFileController@DataTable'); // work 2.1

    Route::post('search-device', 'ClientLogFileController@SearchDevice_time'); // work 2.1
    Route::post('list-device', 'ClientLogFileController@SearchDevice'); // time
    Route::post('device-time', 'ClientLogFileController@DeviceTime');

    // version 2
    Route::post('multiple-file', 'ClientLogFileController@upload_data');

    // test
    Route::post('test-file', 'UploadFileController@test_data');
    Route::post('get-device', 'UploadFileController@DeviceTime');


    Route::delete('delete-data', 'ClientLogFileController@DeleteData');
});
