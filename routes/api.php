<?php

use Illuminate\Http\Request;

Route::group(['middleware'=>['api']], function () {
    Route::post('/attendance', 'AttendanceController@store');
    Route::get('/attendanceall', 'AttendanceController@show');
    //Gasan trigger
    Route::get('/triger','TrigerController@index');
    //gasan pegawai
    Route::get('/cekpegawai','PegawaiController@cekpegawai');
    Route::get('/ambilfinger/{id}','PegawaiController@ambilfinger');
    //addfingerpegawai
    Route::post('/addfinger','PegawaiController@addfinger');

    // Route::post('/otp','AttendanceController@encryptdata');
});
