<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Events\ChatEvent;




  Route::get('/', 'DashboardController@index');
  Route::post('/','DashboardController@index');
  Route::get('/login','LoginController@getLogin')->name('login');
  Route::post('/login','LoginController@postLogin');
  Route::get('/instansi/grafik/public','DashboardController@datakosong')->name('grafikinstansikosong');
  Route::get('/instansi/grafik','DashboardController@datatahun')->name('grafikinstansicari');
  Route::get('/pegawai/grafik','DashboardController@datapegawai');

Route::group(['middleware' => ['rule:user,admin']],function(){
  Route::get('/home','ChartController@index');
  Route::get('/home/data','ChartController@data');
  Route::get('/home/datacari','ChartController@datacari');
  Route::post('/home','ChartController@store')->name('chatpost');
});

Route::group(['middleware' => ['rule:user']],function(){

      Route::get('/jadwalkerja','JadwalKerjaController@index');
      Route::post('/jadwalkerja','JadwalKerjaController@store');
      Route::get('/jadwalkerja/{id}/edit','JadwalKerjaController@editshow');
      Route::put('/jadwalkerja/{id}','JadwalKerjaController@editstore');
      Route::get('/jadwalkerja/{id}/hapus','JadwalKerjaController@deletestore');

      Route::post('/rulejadwalkerja','RuleJadwalKerja@store');
      Route::get('/rulejadwalkerja/{id}/edit','RuleJadwalKerja@edit');
      Route::put('/rulejadwalkerja/{id}','RuleJadwalKerja@update');
      Route::get('/rulejadwalkerja/{id}/hapus','RuleJadwalKerja@destroy');

      Route::get('/jadwalkerjapegawai','JadwalKerjaPegawaiController@index');
      Route::post('/jadwalkerjapegawai','JadwalKerjaPegawaiController@index');
      Route::post('/jadwalkerjapegawaiedit','JadwalKerjaPegawaiController@store');
      Route::get('/jadwalkerjapegawai/{id}/edit','JadwalKerjaPegawaiController@show');
      Route::post('/jadwalkerjapegawai/edit','JadwalKerjaPegawaiController@update');
      Route::get('/jadwalkerjapegawai/{id}/hapus','JadwalKerjaPegawaiController@destroy');

      Route::get('/harikerja','HariKerjaController@index');
      Route::post('/harikerja','HariKerjaController@store');
      Route::get('/harikerja/{id}','HariKerjaController@show');
      Route::get('/harikerja/hapus/{id}','HariKerjaController@destroy');


      Route::get('/rekapabsensipegawai','RekapAbsensiController@index');
      Route::post('/rekapabsensipegawai','RekapAbsensiController@index');
      Route::get('/rekapabsensipegawai/{id}/{id2}','RekapAbsensiController@show');
      Route::post('/rekapabsensipegawai/{id}','RekapAbsensiController@edit');

      Route::post('/rekapbulanan','MasterAbsensiController@index');

      Route::get('/transrekap/datarekap','TransferRekapController@datagrid')->name('datatransrekap');
      Route::get('/transrekap','TransferRekapController@index');
      Route::post('/transrekap/postijin','TransferRekapController@postijin')->name('postijin');
      Route::post('/transrekap/postsakit','TransferRekapController@postsakit')->name('postsakit');
      Route::post('/transrekap/postcuti','TransferRekapController@postcuti')->name('postcuti');
      Route::post('/transrekap/posttb','TransferRekapController@posttb')->name('posttb');
      Route::post('/transrekap/posttl','TransferRekapController@posttl')->name('posttl');
      Route::post('/transrekap/postrp','TransferRekapController@postrp')->name('postrp');
      Route::post('/transrekap/postit','TransferRekapController@postit')->name('postit');
      Route::get('/transrekap/download/ijin','TransferRekapController@downloadsuratijin')->name('downloadsuratijin');
      Route::post('/transrekap/download/ijin','TransferRekapController@downloadsuratijin')->name('downloadsuratijinpost');
      Route::get('/transrekap/download/ijin/surat/{id}','TransferRekapController@downloadijin')->name('downloadingsuratijin');
      Route::get('/transrekap/download/sakit','TransferRekapController@downloadsuratsakit')->name('downloadsuratsakit');
      Route::post('/transrekap/download/sakit','TransferRekapController@downloadsuratsakit')->name('downloadsuratsakitpost');
      Route::get('/transrekap/download/sakit/surat/{id}','TransferRekapController@downloadsakit')->name('downloadingsuratsakit');
      Route::get('/transrekap/download/cuti','TransferRekapController@downloadsuratcuti')->name('downloadsuratcuti');
      Route::post('/transrekap/download/cuti','TransferRekapController@downloadsuratcuti')->name('downloadsuratcutipost');
      Route::get('/transrekap/download/cuti/surat/{id}','TransferRekapController@downloadcuti')->name('downloadingsuratcuti');
      Route::get('/transrekap/download/tl','TransferRekapController@downloadsurattl')->name('downloadsurattl');
      Route::post('/transrekap/download/tl','TransferRekapController@downloadsurattl')->name('downloadsurattlpost');
      Route::get('/transrekap/download/tl/surat/{id}','TransferRekapController@downloadtl')->name('downloadingsurattl');
      Route::get('/transrekap/download/tb','TransferRekapController@downloadsurattb')->name('downloadsurattb');
      Route::post('/transrekap/download/tb','TransferRekapController@downloadsurattb')->name('downloadsurattbpost');
      Route::get('/transrekap/download/tb/surat/{id}','TransferRekapController@downloadtb')->name('downloadingsurattb');
      Route::get('/transrekap/download/ru','TransferRekapController@downloadsuratru')->name('downloadsuratru');
      Route::post('/transrekap/download/ru','TransferRekapController@downloadsuratru')->name('downloadsuratrupost');
      Route::get('/transrekap/download/ru/surat/{id}','TransferRekapController@downloadru')->name('downloadingsuratru');
      Route::get('/transrekap/download/it','TransferRekapController@downloadsuratit')->name('downloadsuratit');
      Route::post('/transrekap/download/it','TransferRekapController@downloadsuratit')->name('downloadsuratitpost');
      Route::get('/transrekap/download/it/surat/{id}','TransferRekapController@downloadit')->name('downloadingsuratit');

      Route::get('/timeline','TimelineController@index');


});


      Route::post('/user/registerpost','UserController@registerstore');
      Route::get('/user/register','UserController@register');

Route::group(['middleware' => ['rule:admin']],function(){

      Route::get('/ijin/admin','IjinAdminController@index');
      Route::get('/ijin/admin/show/{id}','IjinAdminController@show');
      Route::get('/ijin/admin/download/{id}','TransferRekapController@downloadijin');
      Route::post('/ijin/admin/update/{id}','IjinAdminController@update');
      Route::get('ijin/admin/data','IjinAdminController@dataijin')->name('dataijinadmin');

      Route::get('/sakit/admin','SakitAdminController@index');
      Route::get('/sakit/admin/show/{id}','SakitAdminController@show');
      Route::get('/sakit/admin/download/{id}','TransferRekapController@downloadsakit');
      Route::post('/sakit/admin/update/{id}','SakitAdminController@update');
      Route::get('/sakit/admin/data','SakitAdminController@datasakit')->name('datasakitadmin');

      Route::get('/cuti/admin','CutiAdminController@index');
      Route::get('/cuti/admin/show/{id}','CutiAdminController@show');
      Route::get('/cuti/admin/download/{id}','TransferRekapController@downloadcuti');
      Route::post('/cuti/admin/update/{id}','CutiAdminController@update');
      Route::get('/cuti/admin/data','CutiAdminController@datasakit')->name('datacutiadmin');

      Route::get('/tugasbelajar/admin','TbAdminController@index');
      Route::get('/tugasbelajar/admin/show/{id}','TbAdminController@show');
      Route::get('/tugasbelajar/admin/download/{id}','TransferRekapController@downloadtb');
      Route::post('/tugasbelajar/admin/update/{id}','TbAdminController@update');
      Route::get('/tugasbelajar/admin/data','TbAdminController@datatb')->name('datatugasbelajaradmin');

      Route::get('/tugasluar/admin','TlAdminController@index');
      Route::get('/tugasluar/admin/show/{id}','TlAdminController@show');
      Route::get('/tugasluar/admin/download/{id}','TransferRekapController@downloadtl');
      Route::post('/tugasluar/admin/update/{id}','TlAdminController@update');
      Route::get('/tugasluar/admin/data','TlAdminController@datatl')->name('datatugasluaradmin');


      Route::get('/rapatundangan/admin','RpAdminController@index');
      Route::get('/rapatundangan/admin/show/{id}','RpAdminController@show');
      Route::get('/rapatundangan/admin/download/{id}','TransferRekapController@downloadrp');
      Route::post('/rapatundangan/admin/update/{id}','RpAdminController@update');
      Route::get('/rapatundangan/admin/data','RpAdminController@datarp')->name('datarapatundanganadmin');


      Route::get('/ijinterlambat/admin','ItAdminController@index');
      Route::get('/ijinterlambat/admin/show/{id}','ItAdminController@show');
      Route::get('/ijinterlambat/admin/download/{id}','TransferRekapController@downloadit');
      Route::post('/ijinterlambat/admin/update/{id}','ItAdminController@update');
      Route::get('/ijinterlambat/admin/data','ItAdminController@datarp')->name('dataijinterlambatadmin');


      Route::get('/pegawai','PegawaiController@index');
      Route::get('/pegawai/datapegawai','PegawaiController@data')->name('datapegawai');
      Route::post('/pegawai/sinkron','PegawaiController@store')->name('sinkronpegawai');
      // Route::post('/editpegawai','PegawaiController@update')->name('editpegawai');
      // Route::post('/deletepegawai','PegawaiController@destroy')->name('deletepegawai');

      Route::get('/instansi','InstansiController@index');
      Route::post('/instansi/sinkron','InstansiController@store')->name('sinkroninstansi');
      Route::get('/instansi/data','InstansiController@data')->name('datainstansi');


      Route::get('/user','UserController@index');
      Route::get('/user/datauser','UserController@data')->name('datauser');
      Route::post('/user/postuser','UserController@store')->name('adduser');
      Route::post('/user/edituser','UserController@edit')->name('edituser');
      Route::post('/user/deleteuser','UserController@destroy')->name('deleteuser');

});


Route::get('/logout',function (){
    Auth::logout();
    return redirect('/')->with('error', 'Logout Berhasil');
});
