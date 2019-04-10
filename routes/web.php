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
use Illuminate\Support\Facades\Auth;



  // Route::get('/', 'DashboardController@index');
  // Route::post('/','DashboardController@index');
  Route::get('/','LoginController@getLogin')->name('login');
  Route::post('/','LoginController@postLogin');


Route::group(['middleware' => ['rule:user,admin,rs,karu,bkd']],function(){
  Route::get('/keteranganabsen','KeteranganAbsenController@index');
  Route::post('/keteranganabsen','KeteranganAbsenController@index')->name('postindexketeranganabsen');
  Route::get('/keteranganabsen/{pegawai_id}','KeteranganAbsenController@showpegawai');
  Route::get('/keteranganabsen/{pegawai_id}/{jadwalkerja_id}','KeteranganAbsenController@create');
  Route::post('/keteranganabsen/calendar/data','KeteranganAbsenController@calendardata')->name('datacalendarketeranganabsen');
  Route::post('/keteranganabsen/calendar/store','KeteranganAbsenController@storecalendardata')->name('storecalendarketeranganabsen');
  Route::post('/keteranganabsen/calendar/destroy','KeteranganAbsenController@destroycalendardata')->name('destroycalendarketeranganabsen');



  Route::get('/harilibur/pegawai','PegawaiHariLiburController@index');
  Route::get('/harilibur/pegawai/data','PegawaiHariLiburController@getdata')->name('getpegawaiharilibur');
  Route::post('/harilibur/pegawai/store','PegawaiHariLiburController@store')->name('storepegawaiharilibur');

  Route::get('/role/harilibur','RoleHariLiburController@index');
  Route::post('/role/harilibur/calendar','RoleHariLiburController@eventcalendar'); 

  Route::get('/queue/pegawai','QueuePegawaiController@index');
  Route::post('/queue/pegawai','QueuePegawaiController@index')->name('queuepegawaisearchpost');

  Route::get('/home','ChartController@index');
  Route::get('/home/data','ChartController@data');
  Route::get('/home/datacari','ChartController@datacari');
  Route::post('/home','ChartController@index');
  Route::post('/home/chat','ChartController@store')->name('chatpost');
  Route::post('/pegawai/delete','PegawaiController@destroy')->name('deletepegawai');
  Route::get('/instansi/cari','InstansiController@cari')->name('cariinstansi');
  
});

Route::group(['middleware' => ['rule:kadis,bkd,karu,rs,sekda,user,admin,pegawai,gubernur']],function(){
  Route::get('/changepassword','UserController@indexchange');
  Route::post('/changepassword','UserController@changepassword');
});

Route::group(['middleware'=>['rule:user,admin,kadis,rs,karu']],function(){
  Route::get('/detail/harian/absent','DetailAbsenController@absentharian');
  Route::get('/detail/harian/sakit','DetailAbsenController@sakitharian');
  Route::get('/detail/harian/ijin','DetailAbsenController@ijinharian');
  Route::get('/detail/harian/cuti','DetailAbsenController@cutiharian');
  Route::get('/detail/harian/tugasbelajar','DetailAbsenController@tugasbelajarharian');
  Route::get('/detail/harian/tugasluar','DetailAbsenController@tugasluarharian');
  Route::get('/detail/harian/terlambat','DetailAbsenController@terlambatharian');
  Route::get('/detail/harian/rapatundangan','DetailAbsenController@rapatundanganharian');
  
  // bulanan

  Route::get('/detail/bulan/absent','DetailAbsenController@absentbulan');
  Route::get('/detail/bulan/sakit','DetailAbsenController@sakitbulan');
  Route::get('/detail/bulan/ijin','DetailAbsenController@ijinbulan');
  Route::get('/detail/bulan/cuti','DetailAbsenController@cutibulan');
  Route::get('/detail/bulan/tugasbelajar','DetailAbsenController@tugasbelajarbulan');
  Route::get('/detail/bulan/tugasluar','DetailAbsenController@tugasluarbulan');
  Route::get('/detail/bulan/terlambat','DetailAbsenController@terlambatbulan');
  Route::get('/detail/bulan/rapatundangan','DetailAbsenController@rapatundanganbulan');

  // Tahun

  Route::get('/detail/tahun/absent','DetailAbsenController@absenttahun');
  Route::get('/detail/tahun/sakit','DetailAbsenController@sakittahun');
  Route::get('/detail/tahun/ijin','DetailAbsenController@ijintahun');
  Route::get('/detail/tahun/cuti','DetailAbsenController@cutitahun');
  Route::get('/detail/tahun/tugasbelajar','DetailAbsenController@tugasbelajartahun');
  Route::get('/detail/tahun/tugasluar','DetailAbsenController@tugasluartahun');
  Route::get('/detail/tahun/terlambat','DetailAbsenController@terlambattahun');
  Route::get('/detail/tahun/rapatundangan','DetailAbsenController@rapatundangantahun');

});



Route::get('/logout',function (){
    Auth::logout();
    return redirect('/')->with('error', 'Logout Berhasil');
});

Route::group(['middleware' => ['rule:pegawai']],function(){
  Route::get('/user/pegawai','UserController@indexpegawai');
}); 

Route::group(['middleware' => ['rule:kadis']],function(){
  Route::get('/home/pegawai','DashboardController@indexkadis');
  Route::get('/home/pegawai/detail/{id}','DashboardController@index');
  Route::get('/instansi/grafik','DashboardController@datatahun')->name('grafikinstansicari');
  Route::get('/instansi/grafik/{id}','DashboardController@datadetailrekappegawai')->name('grafikinstansicari');
  Route::get('/pegawai/grafik','DashboardController@datapegawai');
});

Route::group(['middleware' => ['rule:gubernur']],function(){
  Route::get('/dashboard/gub','DashboardController@dashboardgubernur');
  Route::get('/datapegawaigub','DashboardController@datapegawaigub')->name('datapegawaigub');
  Route::get('/pegawai/gub/{id}','DashboardController@datapegawaigubdetail');
});

Route::group(['middleware' => ['rule:sekda']],function(){
    Route::get('/dashboard','DashboardController@indexsekda');
    Route::get('/instansi/grafik/public','DashboardController@datakosong')->name('grafikinstansikosong');
    Route::get('/instansi/grafik/semua','DashboardController@datagrafiksekda')->name('grafikinstansi');
});

Route::group(['middleware' => ['rule:karu']],function(){
    Route::get('/manajemanpegawairuangan','DokterPerawatRuanganController@indexruangan');
    Route::get('/dataruangan/cariruanganspesifik','DokterPerawatRuanganController@caridokterspesifik')->name('cariruanganspesifik');
    Route::get('/manajemanpegawairuangan/data','DokterPerawatRuanganController@dataspesifikperawat')->name('dataspesifikruangan');
    Route::get('/home/ruangan','ChartKhususController@index');
    Route::post('/home/ruangan','ChartKhususController@index');

    #jadwalkerja pegawai harian
    Route::get('/jadwalkerjapegawaiharian','JadwalKerjaPegawaiHarianController@index')->name('indexjadwalkerjapegawaiharian');
    Route::post('/jadwalkerjapegawaiharian','JadwalKerjaPegawaiHarianController@index');
    

    Route::get('/jadwalkerjapegawaiharian/{id}','JadwalKerjaPegawaiHarianController@show');
    Route::post('/eventcalendar','JadwalKerjaPegawaiHarianController@eventcalendartoday');
    Route::post('/eventcalendar/delete','JadwalKerjaPegawaiHarianController@eventcalendardelete');
    Route::post('/eventcalendar/post','JadwalKerjaPegawaiHarianController@eventcalendarstore');
});

Route::group(['middleware' => ['rule:karu,rs,user']],function(){
    #halaman timeline
    Route::get('/timeline','TimelineController@index');
    #rekap absensi pegawai (menentukan jenis absen)
    Route::get('/rekapabsensipegawai','RekapAbsensiController@index');
    Route::post('/rekapabsensipegawai','RekapAbsensiController@index');
    // Route::post('/rekapabsensipegawai','RekapAbsensiController@index');
    // Route::get('/rekapabsensipegawai/{id}/{id2}','RekapAbsensiController@show');
    Route::post('/rekapabsensipegawai/edit','RekapAbsensiController@edit');
    Route::get('/rekapabsensipegawai/data','RekapAbsensiController@attsdata')->name('dataatts');
});

Route::group(['middleware' => ['rule:karu,rs']],function(){
  Route::get('/manajemenpegawairuangan','DokterPerawatRuanganController@indexruangan');
  Route::get('/dataruangan/cariruanganspesifik','DokterPerawatRuanganController@caridokterspesifik')->name('cariruanganspesifik');
  Route::get('/manajemanpegawairuangan/data','DokterPerawatRuanganController@dataspesifikperawat')->name('dataspesifikruangan');
  // Route::get('/home/ruangan','ChartKhususController@index');
  // Route::post('/home/ruangan','ChartKhususController@index');
  Route::get('/datapegawai/caridokter','DokterPerawatRuanganController@caridokter')->name('caridokter');
  Route::post('/dataruangan/storepegawairuangan','DokterPerawatRuanganController@storeperawat')->name('storeperawat');
  Route::post('/dataruangan/destroypegawairuangan','DokterPerawatRuanganController@destroyperawat')->name('destroyperawat');
});

Route::group(['middleware' => ['rule:rs']],function(){
      Route::get('/manajemenpegawaikhusus','DokterPerawatRuanganController@index');
      Route::post('/manajemenpegawaikhusus/addruangan','DokterPerawatRuanganController@storeruangan')->name('storeruangan');
      Route::post('/manajemenpegawaikhusus/updateruangan','DokterPerawatRuanganController@updateruangan')->name('updateruangan');
      Route::post('/manajemenpegawaikhusus/destroyruangan','DokterPerawatRuanganController@destroyruangan')->name('destroyruangan');
      Route::get('/datapegawai/pegawai','DokterPerawatRuanganController@datapegawai')->name('datapegawaikhusus');
      Route::get('/datapegawai/dokter','DokterPerawatRuanganController@datadokter')->name('datadokter');
      Route::get('/datapegawai/allperawat','DokterPerawatRuanganController@dataallperawat')->name('dataallperawat');
      Route::get('/datapegawai/ruangan','DokterPerawatRuanganController@dataruangan')->name('dataruangan');
      Route::get('/dataruangan/cariruangan','DokterPerawatRuanganController@cariruangan')->name('cariruangan');
      Route::post('/datapegawai/storedokter','DokterPerawatRuanganController@storedokter')->name('storedokter');
      Route::post('/datapegawai/destroydokter','DokterPerawatRuanganController@destroydokter')->name('destroydokter');
      #karu dan tata usaha rs
      // Route::get('/jadwalkerjapegawai24','RuleJadwalKerja24JamController@index');
      // Route::get('/jadwalkerjapegawai24/data','RuleJadwalKerja24JamController@datapegawai')->name('datapegawaijadwalkerja24');
      // Route::get('/jadwalkerjapegawai24/data/jadwalkerja','RuleJadwalKerja24JamController@datarulejadwalpegawai')->name('datapegawairulejadwalkerja24');
      // Route::post('/jadwalkerjapegawai24','RuleJadwalKerja24JamController@index');
      // Route::post('/jadwalkerjapegawaiedit24','RuleJadwalKerja24JamController@store');
      // Route::get('/jadwalkerjapegawai24/{id}/edit','RuleJadwalKerja24JamController@show');
      // Route::post('/jadwalkerjapegawai24/edit','RuleJadwalKerja24JamController@update');
      // Route::get('/jadwalkerjapegawai24/{id}/hapus','RuleJadwalKerja24JamController@destroy');
      // Route::post('/hapusjadwalkerjapegawai24','RuleJadwalKerja24JamController@destroyall');

      #user ruangan
      Route::get('/userkhusus','UserRuanganController@index');
      Route::post('/userkhusus/add','UserRuanganController@registerstore')->name('adduserkhusus');
      Route::post('/userkhusus/edit','UserRuanganController@edit')->name('edituserkhusus');
      Route::post('/userkhusus/delete','UserRuanganController@delete')->name('deleteuserkhusus');
      Route::get('/userkhusus/data','UserRuanganController@data')->name('datauserkhusus');
});

Route::group(['middleware' => ['rule:bkd']],function(){
      //monitoring BKD
      Route::get('/monitoring/pegawai','MonitoringController@monitoringpegawaibulanan');
      Route::post('/monitoring/pegawai','MonitoringController@monitoringpegawaibulanan')->name('monitoringpegawaibulan');
      Route::get('/monitoring/pegawai/{id}/{tanggal}','MonitoringController@monitoringpegawaiminggu');
      Route::post('/monitoring/pegawai/{id}/{tanggal}','MonitoringController@monitoringpegawaiminggu')->name('monitoringpegawaiminggu');
      Route::get('/monitoring/pegawai/{id}/{tanggal}/att','MonitoringController@monitoringpegawaihari');
      Route::post('/monitoring/pegawai/{id}/{tanggal}/att','MonitoringController@monitoringpegawaihari')->name('monitoringpegawaihari');    
      Route::get('/monitoring/pegawai/export','MonitoringController@monitoringpegawaibulanexport');


      Route::get('/monitoring/instansi','MonitoringController@monitoringinstansiminggu');
      Route::post('/monitoring/instansi','MonitoringController@monitoringinstansiminggu')->name('monitoringinstansiminggu');
      Route::get('/monitoring/instansi/export','MonitoringController@monitoringinstansimingguexport');
      Route::get('/monitoring/instansi/{id}/{tanggal}','MonitoringController@monitoringinstansiminggupersonal');
      Route::get('/monitoring/instansi/export/{id}/{tanggal}','MonitoringController@monitoringinstansiminggupersonalexport');
      Route::post('/monitoring/instansi/{id}/{tanggal}','MonitoringController@monitoringinstansiminggupersonal')->name('monitoringinstansiminggupersonal');
      Route::get('/monitoring/instansi/detail/{id}/{tanggal}/{instansi_id}','MonitoringController@monitoringinstansiminggupersonaldetail');  
      Route::post('/monitoring/instansi/detail/{id}/{tanggal}/{instansi_id}','MonitoringController@monitoringinstansiminggupersonaldetail')->name('monitoringinstansiminggupersonaldetail'); 
      Route::get('/monitoring/instansi/detail/att/{id}/{tanggal}/{instansi_id}','MonitoringController@monitoringinstansiminggupersonaldetailatt'); 
  
      Route::get('/monitoring/grafik/harian','MonitoringController@grafikmonitoringharian');
      Route::get('/monitoring/grafik/harian/data','MonitoringController@grafikmonitoringhariandata')->name('monitoringgrafikhariandata');
      Route::get('/monitoring/grafik/bulanan','MonitoringController@grafikmonitoringbulan');
      Route::get('/monitoring/grafik/bulanan/data','MonitoringController@grafikmonitoringbulandata')->name('monitoringgrafikbulanandata');

 
});

Route::group(['middleware' => ['rule:rs,user']],function(){

      
      Route::get('/jadwalkerjapegawai','JadwalKerjaPegawaiController@index');
      Route::get('/jadwalkerjapegawai/data','JadwalKerjaPegawaiController@datapegawai')->name('datapegawaijadwalkerja');
      Route::get('/jadwalkerjapegawai/data/jadwalkerja','JadwalKerjaPegawaiController@datarulejadwalpegawai')->name('datapegawairulejadwalkerja');
      Route::post('/jadwalkerjapegawai','JadwalKerjaPegawaiController@index');
      Route::post('/jadwalkerjapegawaiedit','JadwalKerjaPegawaiController@store');
      // Route::get('/jadwalkerjapegawai/{id}/edit','JadwalKerjaPegawaiController@show');
      // Route::post('/jadwalkerjapegawai/edit','JadwalKerjaPegawaiController@update');
      Route::get('/jadwalkerjapegawai/{id}/hapus','JadwalKerjaPegawaiController@destroy');
      Route::post('/hapusjadwalkerjapegawai','JadwalKerjaPegawaiController@destroyall');

      #rekap mingguan
      // Route::get('/rekapbulanan','RekapAbsensiController@indexrekap');
      // Route::get('/rekapbulanan/rekapbulanan/data','RekapAbsensiController@datarekapuser')->name('datarekapusermingguan');

      #laporanharian
      Route::get('/laporanharian','PDFController@index');
      Route::post('/laporanharian','PDFController@index');
      Route::get('/laporanharian/pdf/tanggal/{id}/nip/{id2}','PDFController@pdfharianfull');
      Route::get('/laporanharian/pdf/tanggal/{id}','PDFController@pdfhariantanggal');
      Route::get('/laporanharian/pdf/nip/{id2}','PDFController@pdfhariannip');
      Route::get('/laporanharian/pdf','PDFController@pdfharian');

      #laporanminggu
      Route::get('/laporanmingguan','PDFController@pdfmingguanindex');
      Route::post('/laporanmingguan','PDFController@pdfmingguanindex');
      Route::get('/laporanmingguan/pdf/tanggal/{id}/nip/{id2}','PDFController@pdfminggufull');
      Route::get('/laporanmingguan/pdf/tanggal/{id}','PDFController@pdfminggutanggal');
      Route::get('/laporanmingguan/pdf/nip/{id2}','PDFController@pdfminggunip');
      Route::get('/laporanmingguan/pdf','PDFController@pdfminggu');

      #laporanbulan

      Route::get('/laporanbulan','PDFController@pdfbulanindex');
      Route::post('/laporanbulan','PDFController@pdfbulanindex');
      Route::get('/laporanbulan/pdf/tanggal/{id}/nip/{id2}','PDFController@pdfbulanfull');
      Route::get('/laporanbulan/pdf/tanggal/{id}','PDFController@pdfbulantanggal');
      Route::get('/laporanbulan/pdf/nip/{id2}','PDFController@pdfbulannip');
      Route::get('/laporanbulan/pdf','PDFController@pdfbulan');

      // #transrekap absensi
      // Route::get('/transrekap/datarekap','TransferRekapController@datagrid')->name('datatransrekap');
      // Route::get('/transrekap','TransferRekapController@index');
      // Route::post('/transrekap/postijin','TransferRekapController@postijin')->name('postijin');
      // Route::post('/transrekap/postsakit','TransferRekapController@postsakit')->name('postsakit');
      // Route::post('/transrekap/postcuti','TransferRekapController@postcuti')->name('postcuti');
      // Route::post('/transrekap/posttb','TransferRekapController@posttb')->name('posttb');
      // Route::post('/transrekap/posttl','TransferRekapController@posttl')->name('posttl');
      // Route::post('/transrekap/postrp','TransferRekapController@postrp')->name('postrp');
      // Route::post('/transrekap/postit','TransferRekapController@postit')->name('postit');
      // Route::post('/transrekap/postipc','TransferRekapController@postipc')->name('postipc');

      #halaman download surat
      // Route::get('/transrekap/download/ijin','TransferRekapController@downloadsuratijin')->name('downloadsuratijin');
      // Route::post('/transrekap/download/ijin','TransferRekapController@downloadsuratijin')->name('downloadsuratijinpost');
      // // Route::get('/transrekap/download/ijin/surat/{id}','TransferRekapController@downloadijin')->name('downloadingsuratijin');
      // Route::get('/transrekap/download/sakit','TransferRekapController@downloadsuratsakit')->name('downloadsuratsakit');
      // Route::post('/transrekap/download/sakit','TransferRekapController@downloadsuratsakit')->name('downloadsuratsakitpost');
      // // Route::get('/transrekap/download/sakit/surat/{id}','TransferRekapController@downloadsakit')->name('downloadingsuratsakit');
      // Route::get('/transrekap/download/cuti','TransferRekapController@downloadsuratcuti')->name('downloadsuratcuti');
      // Route::post('/transrekap/download/cuti','TransferRekapController@downloadsuratcuti')->name('downloadsuratcutipost');
      // // Route::get('/transrekap/download/cuti/surat/{id}','TransferRekapController@downloadcuti')->name('downloadingsuratcuti');
      // Route::get('/transrekap/download/tl','TransferRekapController@downloadsurattl')->name('downloadsurattl');
      // Route::post('/transrekap/download/tl','TransferRekapController@downloadsurattl')->name('downloadsurattlpost');
      // // Route::get('/transrekap/download/tl/surat/{id}','TransferRekapController@downloadtl')->name('downloadingsurattl');
      // Route::get('/transrekap/download/tb','TransferRekapController@downloadsurattb')->name('downloadsurattb');
      // Route::post('/transrekap/download/tb','TransferRekapController@downloadsurattb')->name('downloadsurattbpost');
      // // Route::get('/transrekap/download/tb/surat/{id}','TransferRekapController@downloadtb')->name('downloadingsurattb');
      // Route::get('/transrekap/download/ru','TransferRekapController@downloadsuratru')->name('downloadsuratru');
      // Route::post('/transrekap/download/ru','TransferRekapController@downloadsuratru')->name('downloadsuratrupost');
      // // Route::get('/transrekap/download/ru/surat/{id}','TransferRekapController@downloadru')->name('downloadingsuratru');
      // Route::get('/transrekap/download/it','TransferRekapController@downloadsuratit')->name('downloadsuratit');
      // Route::post('/transrekap/download/it','TransferRekapController@downloadsuratit')->name('downloadsuratitpost');
      // // Route::get('/transrekap/download/it/surat/{id}','TransferRekapController@downloadit')->name('downloadingsuratit');
      // Route::get('/transrekap/download/ipc','TransferRekapController@downloadsuratipc')->name('downloadsuratipc');
      // Route::post('/transrekap/download/ipc','TransferRekapController@downloadsuratipc')->name('downloadsuratipcpost');
      // // Route::get('/transrekap/download/it/surat/{id}','TransferRekapController@downloadit')->name('downloadingsuratit');

      #raspberry
      Route::get('/alat/instansi','LogRaspberryController@indexinstansi');
      Route::get('/alat/instansi/data','LogRaspberryController@datainstansi')->name('dataraspberryinstansi');
      Route::get('/alat/instansi/sidikjari','LogRaspberryController@indexfinger');
      Route::get('/alat/instansi/sidikjari/data','LogRaspberryController@datasidikjariinstansi')->name('dataraspberrysidikjariinstansi');
      
      

      #atur hari kerja
      Route::get('/harikerja','HariKerjaController@index');
      Route::post('/harikerja','HariKerjaController@store');
      Route::get('/harikerja/{id}','HariKerjaController@show');
      Route::get('/harikerja/hapus/{id}','HariKerjaController@destroy');
      Route::post('/minggukerja','JadwalKerjaController@minggukerja');
      Route::get('/minggukerja/{id}','JadwalKerjaController@hapusjadwalminggu');
});

// Route::group(['middleware' => ['rule:admin,bkd']],function(){
//       #rekap bulanan
//       Route::get('/rekapbulanan/rekapbulanan/admin','RekapAbsensiController@indexrekapadmin');
//       Route::get('/rekapbulanan/rekapbulanan/admin/data','RekapAbsensiController@datarekapadmin')->name('datarekapadminmingguan');

// });

Route::group(['middleware' => ['rule:user,rs']],function(){
      

      

      #atur jadwal kerja pegawai
      

      

      // Route::get('/manajemenpegawaikhusus','DokterPerawatRuanganController@index');
      // Route::post('/manajemenpegawaikhusus/addruangan','DokterPerawatRuanganController@storeruangan')->name('storeruangan');
      // Route::post('/manajemenpegawaikhusus/updateruangan','DokterPerawatRuanganController@updateruangan')->name('updateruangan');
      // Route::post('/manajemenpegawaikhusus/destroyruangan','DokterPerawatRuanganController@destroyruangan')->name('destroyruangan');
      // Route::get('/datapegawai/pegawai','DokterPerawatRuanganController@datapegawai')->name('datapegawaikhusus');
      // Route::get('/datapegawai/dokter','DokterPerawatRuanganController@datadokter')->name('datadokter');
      // Route::get('/datapegawai/allperawat','DokterPerawatRuanganController@dataallperawat')->name('dataallperawat');
      // Route::get('/datapegawai/ruangan','DokterPerawatRuanganController@dataruangan')->name('dataruangan');
      // Route::get('/dataruangan/cariruangan','DokterPerawatRuanganController@cariruangan')->name('cariruangan');
      // Route::post('/datapegawai/storedokter','DokterPerawatRuanganController@storedokter')->name('storedokter');
      // Route::post('/datapegawai/destroydokter','DokterPerawatRuanganController@destroydokter')->name('destroydokter');
      // #karu dan tata usaha rs
      // Route::get('/datapegawai/caridokter','DokterPerawatRuanganController@caridokter')->name('caridokter');
      // Route::post('/dataruangan/storepegawairuangan','DokterPerawatRuanganController@storeperawat')->name('storeperawat');
      // Route::post('/dataruangan/destroypegawairuangan','DokterPerawatRuanganController@destroyperawat')->name('destroyperawat');
      #end

      // Route::get('/manajemanpegawairuangan','DokterPerawatRuanganController@indexruangan');
      // Route::get('/dataruangan/cariruanganspesifik','DokterPerawatRuanganController@caridokterspesifik')->name('cariruanganspesifik');
      // Route::get('/manajemanpegawairuangan/data','DokterPerawatRuanganController@dataspesifikperawat')->name('dataspesifikruangan');
    

      
      

      

      #rekap absensi pegawai (menentukan jenis absen)
      // Route::get('/rekapabsensipegawai','RekapAbsensiController@index');
      // Route::post('/rekapabsensipegawai','RekapAbsensiController@index');
      // // Route::post('/rekapabsensipegawai','RekapAbsensiController@index');
      // // Route::get('/rekapabsensipegawai/{id}/{id2}','RekapAbsensiController@show');
      // Route::post('/rekapabsensipegawai/edit','RekapAbsensiController@edit');
      // Route::get('/rekapabsensipegawai/data','RekapAbsensiController@attsdata')->name('dataatts');

      #table rekap mingguan
      

      #backend proses
      #Route::post('/rekapbulanans','MasterAbsensiController@index');

      #transfer surat rekap
      
      

      

      #manajemen pegawai
      Route::get('/pegawai/show','PegawaiController@show');
      Route::get('/pegawai/show/data','PegawaiController@datauser')->name('datapegawaiuser');
      Route::post('/pegawai/add','PegawaiController@update')->name('editpegawai');
      Route::get('/pegawai/cek/{id}','PegawaiController@validasipegawai')->name('validasipegawai');


      
});


      // Route::post('/user/registerpost','UserController@registerstore');
      // Route::get('/user/register','UserController@register');


Route::group(['middleware' => ['rule:admin']],function(){
      Route::get('/harilibur','HariLiburController@index'); 
      Route::post('/harilibur','HariLiburController@index')->name('carinamaharilibur');  
      Route::get('/harilibur/create','HariLiburController@create'); 
      Route::post('/harilibur/store','HariLiburController@store')->name('postharilibur');  
      Route::get('/harilibur/show/{id}','HariLiburController@edit');
      Route::put('/harilibur/update/{id}','HariLiburController@update');
      Route::get('/harilibur/delete/{id}', 'HariLiburController@destroy');


      Route::post('/role/harilibur/calendar/store','RoleHariLiburController@store'); 
      Route::post('/role/harilibur/calendar/delete','RoleHariLiburController@destroy');

      #raspberry
      Route::get('/raspberry','LogRaspberryController@index');
      Route::get('/raspberry/data','LogRaspberryController@data')->name('dataraspberry');

      #hapussidikjariinstansi
      #Route::get('/hapussidikjari/{id}','FingerPegawaiController@hapussidikjariinstansi');

      #jadwalkerja
      Route::get('/jadwalkerja','JadwalKerjaController@index');
      Route::post('/jadwalkerja','JadwalKerjaController@index');
      Route::post('/jadwalkerja/add','JadwalKerjaController@store');
      Route::get('/jadwalkerja/{id}/edit','JadwalKerjaController@editshow');
      Route::put('/jadwalkerja/{id}','JadwalKerjaController@editstore');
      Route::get('/jadwalkerja/{id}/hapus','JadwalKerjaController@deletestore');
      Route::get('/jadwalkerja/{id}/detail','JadwalKerjaController@detailjadwalkerja');
      Route::get('/jadwalkerja/cari','JadwalKerjaController@cari')->name('carijadwal');
      Route::get('/jadwalkerja/datatable/jadwalkerja','JadwalKerjaController@jadwalkerjadatatable')->name('jadwalkerjadatatable');
      Route::get('/jadwalkerja/datatable/rulejadwalkerja','JadwalKerjaController@rulejadwalkerjadatatable')->name('rulejadwalkerjadatatable');
      // Route::get('/carbon','JadwalKerjaController@minggukerja');

      #rulejadwalkerja
      Route::post('/rulejadwalkerja','RuleJadwalKerja@store');
      Route::get('/rulejadwalkerja/{id}/edit','RuleJadwalKerja@edit');
      Route::put('/rulejadwalkerja/{id}','RuleJadwalKerja@update');
      Route::get('/rulejadwalkerja/{id}/hapus','RuleJadwalKerja@destroy');
      Route::post('/rulejadwalkerjadetail','RuleJadwalKerja@storedetail');


      #manajemen fingerpegawai
      Route::get('/finger','FingerPegawaiController@index');
      Route::post('/finger','FingerPegawaiController@index');
      Route::get('/finger/{id}','FingerPegawaiController@show');
      Route::get('/finger/delete/{id}','FingerPegawaiController@destroy');

      // #halaman data untuk download surat
      // Route::get('/ijin/admin','IjinAdminController@index');
      // // Route::get('/ijin/admin/show/{id}','IjinAdminController@show');
      // // Route::get('/ijin/admin/download/{id}','TransferRekapController@downloadijin');
      // // Route::post('/ijin/admin/update/{id}','IjinAdminController@update');
      // Route::get('ijin/admin/data','IjinAdminController@dataijin')->name('dataijinadmin');

      // Route::get('/sakit/admin','SakitAdminController@index');
      // // Route::get('/sakit/admin/show/{id}','SakitAdminController@show');
      // // Route::get('/sakit/admin/download/{id}','TransferRekapController@downloadsakit');
      // // Route::post('/sakit/admin/update/{id}','SakitAdminController@update');
      // Route::get('/sakit/admin/data','SakitAdminController@datasakit')->name('datasakitadmin');

      // Route::get('/cuti/admin','CutiAdminController@index');
      // // Route::get('/cuti/admin/show/{id}','CutiAdminController@show');
      // // Route::get('/cuti/admin/download/{id}','TransferRekapController@downloadcuti');
      // // Route::post('/cuti/admin/update/{id}','CutiAdminController@update');
      // Route::get('/cuti/admin/data','CutiAdminController@datasakit')->name('datacutiadmin');

      // Route::get('/tugasbelajar/admin','TbAdminController@index');
      // // Route::get('/tugasbelajar/admin/show/{id}','TbAdminController@show');
      // // Route::get('/tugasbelajar/admin/download/{id}','TransferRekapController@downloadtb');
      // // Route::post('/tugasbelajar/admin/update/{id}','TbAdminController@update');
      // Route::get('/tugasbelajar/admin/data','TbAdminController@datatb')->name('datatugasbelajaradmin');

      Route::get('/macaddress','MacAdressControllers@index');
      Route::get('/macaddress/{id}','MacAdressControllers@show');
      Route::post('/macaddress','MacAdressControllers@store');
      Route::post('/macaddress/edit','MacAdressControllers@edit');
      Route::get('/macaddress/delete/{id}','MacAdressControllers@destroy');

      // Route::get('/tugasluar/admin','TlAdminController@index');
      // // Route::get('/tugasluar/admin/show/{id}','TlAdminController@show');
      // // Route::get('/tugasluar/admin/download/{id}','TransferRekapController@downloadtl');
      // // Route::post('/tugasluar/admin/update/{id}','TlAdminController@update');
      // Route::get('/tugasluar/admin/data','TlAdminController@datatl')->name('datatugasluaradmin');


      // Route::get('/rapatundangan/admin','RpAdminController@index');
      // // Route::get('/rapatundangan/admin/show/{id}','RpAdminController@show');
      // // Route::get('/rapatundangan/admin/download/{id}','TransferRekapController@downloadrp');
      // // Route::post('/rapatundangan/admin/update/{id}','RpAdminController@update');
      // Route::get('/rapatundangan/admin/data','RpAdminController@datarp')->name('datarapatundanganadmin');


      // Route::get('/ijinterlambat/admin','ItAdminController@index');
      // // Route::get('/ijinterlambat/admin/show/{id}','ItAdminController@show');
      // // Route::get('/ijinterlambat/admin/download/{id}','TransferRekapController@downloadit');
      // // Route::post('/ijinterlambat/admin/update/{id}','ItAdminController@update');
      // Route::get('/ijinterlambat/admin/data','ItAdminController@datait')->name('dataijinterlambatadmin');

      #manajemen pegawai()
      Route::get('/pegawai','PegawaiController@index');
      Route::post('/pegawai','PegawaiController@index');
      Route::get('/pegawai/datapegawai','PegawaiController@data')->name('datapegawai');
      Route::post('/pegawai/sinkron','PegawaiController@store')->name('sinkronpegawai');
      Route::get('/pegawai/cari','PegawaiController@cari');
      Route::get('/pegawai/import','PegawaiController@indexuploadexcel');
      Route::post('/pegawai/import','PegawaiController@uploadpegawaiExcel');
      // Route::get('/pegawai/manajemen','PegawaiController@pagepegawaiadmin');
      // Route::get('/pegawai/show/data/admin','PegawaiController@datauser')->name('datapegawaiadmin');
      // Route::post('/editpegawai','PegawaiController@update')->name('editpegawai');
      // Route::post('/deletepegawai','PegawaiController@destroy')->name('deletepegawai');

      #manajemen instansi
      Route::get('/instansi','InstansiController@index');
      Route::post('/instansi/sinkron','InstansiController@store')->name('sinkroninstansi');
      Route::get('/instansi/data','InstansiController@data')->name('datainstansi');
      // Route::get('/instansi/cari','InstansiController@cari')->name('cariinstansi');

      #rekap bulanan
      // Route::get('/rekapbulanan/rekapbulanan/admin','RekapAbsensiController@indexrekapadmin');
      // Route::get('/rekapbulanan/rekapbulanan/admin/data','RekapAbsensiController@datarekapadmin')->name('datarekapadminmingguan');

      #manajemen user
      Route::get('/user','UserController@index');
      Route::get('/user/datauser','UserController@data')->name('datauser');
      Route::post('/user/postuser','UserController@store')->name('adduser');
      Route::post('/user/edituser','UserController@edit')->name('edituser');
      Route::post('/user/deleteuser','UserController@delete')->name('deleteuser');

      #trigger
      Route::get('/trigger','TrigerController@form');
      Route::post('/trigger','TrigerController@edit');
      Route::post('/trigger/hapus','TrigerController@hapus');
      Route::post('/trigger/hapusdata','TrigerController@posthapus');
      Route::post('/trigger/tambahadmin','TrigerController@postadmindata');
      Route::post('/trigger/hapusadmin','TrigerController@hapusadmindata');

      Route::get('/historyfingerpegawai','HistoryFingerPegawaiController@index');
      Route::get('/historufingerpegawai/data','HistoryFingerPegawaiController@data')->name('datahistory');


      Route::get('/historycrashraspberry','HistoryCrashRaspberryController@index');
      Route::get('/historycrashraspberry/data','HistoryCrashRaspberryController@data')->name('datahistorycrash');

});
