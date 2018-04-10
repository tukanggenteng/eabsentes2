<?php

use Illuminate\Http\Request;

Route::group(['middleware'=>['api']], function () {
    Route::post('/attendance', 'AttendanceController@store');
    Route::get('/attendanceall', 'AttendanceController@show');
    //Gasan trigger
    Route::get('/triger','TrigerController@index');
    //gasan pegawai

    Route::get('/cekpegawai','PegawaiController@cekpegawai2');

    Route::get('/cekpegawai/{id}','PegawaiController@cekpegawai');

    Route::get('/cekpegawaidata/{id}','PegawaiController@cekpegawaiparams');

    Route::get('/cekpegawai/data/{id}','PegawaiController@cekpegawaiinstansi');

    Route::get('/ambilfinger/{id}','PegawaiController@ambilfinger');
    //addfingerpegawai
    Route::post('/addfinger','PegawaiController@addfinger');

    Route::get('/admin/finger','PegawaiController@getadmin');

    Route::get('/admin/finger/{id}','PegawaiController@getadminparams');
    // Route::post('/otp','AttendanceController@encryptdata');

    Route::get('/macaddress','MacAdressControllers@macaddress');

    Route::get('/version','VersionController@index');

    Route::post('/logerror','LogFingerErrorController@create');

    Route::post('/raspberry','RaspberryStatusController@update');

    Route::get('/hapusfingerpegawai','TrigerController@pegawaihapus');

    // Route::get('/atts/hapus/{id}','AttendanceController@hapusatt');

    Route::post('/lograspberry','LogRaspberryController@postlog');

    Route::get('/historyfinger/{ip}/{instansi_id}','HistoryFingerPegawaiController@getdata');
    
    Route::post('/historyfinger','HistoryFingerPegawaiController@edit');

    Route::post('/historycrash','HistoryCrashRaspberryController@post');
});
