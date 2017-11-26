<?php

use Illuminate\Http\Request;

Route::group(['middleware'=>['api']], function () {
    Route::post('/attendance', 'AttendanceController@store');
    Route::get('/attendanceall', 'AttendanceController@show');
    //Gasan trigger
    Route::get('/triger','TrigerController@index');
    //gasan pegawai
    Route::get('/cekpegawai','PegawaiController@cekpegawai');

    Route::get('/cekpegawaidata/{id}','PegawaiController@cekpegawaiparams');

    Route::get('/cekpegawai/{id}','PegawaiController@cekpegawaiinstansi');

    Route::get('/ambilfinger/{id}','PegawaiController@ambilfinger');
    //addfingerpegawai
    Route::post('/addfinger','PegawaiController@addfinger');

    Route::get('/admin/finger','PegawaiController@getadmin');

    Route::get('/admin/finger/{id}','PegawaiController@getadminparams');
    // Route::post('/otp','AttendanceController@encryptdata');

    Route::get('/macaddress','MacAdressControllers@macaddress');
});
