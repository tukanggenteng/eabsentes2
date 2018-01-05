<?php

namespace App\Http\Controllers;
use App\att;
use App\atts_tran;
use App\instansi;
use App\jadwalkerja;
use App\pegawai;
use App\rulejadwalpegawai;
use App\rulejammasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Request as Request2;
use App\Events\Timeline;

class AttendanceController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('throttle:500000,1');
    }

    public function store(Request $request){

       $jam=$request->json('jam');
       $tanggal=$request->json('tanggal');
       $user_id=$request->json('user_id');
       $instansi=$request->json('instansi');
       $status=$request->json('status');
       $auth=$request->json('token');
       $hasilbasic=$jam.$tanggal.$user_id.$instansi.$status;
       $hasil=$this->encryptOTP($hasilbasic);
       $statusauth=false;


       if ($hasil==$auth){

           $statusauth=true;

              if ($request->json('status')=='0' ){

                  $hitungabsen=att::where('pegawai_id','=',$request->json('user_id'))
                      ->where('tanggal_att','=',$request->json('tanggal'))
                      ->count();
                    // dd($hitungabsen);
                  if ($hitungabsen>0) {

                      $absens = att::where('pegawai_id', '=', $request->json('user_id'))
                          ->where('tanggal_att','=',$request->json('tanggal'))
                          ->get();

                      foreach ($absens as $key=>$absen) {

                          //cek kecocokan jam masuk berdasarkan jadwalkerja
                          $cek = jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                              ->select('jadwalkerjas.id', 'jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal', 'rulejammasuks.jamsebelum_masukkerja')
                              ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                              ->get();
                          $jamawal = date("H:i", strtotime($cek[0]['jamsebelum_masukkerja']));
                          $jamakhir = $cek[0]['jam_masukjadwal'];
                          //menentukan jam toleransi masuk pegawai
                          // $jamakhir2 = date("H:i:s", strtotime("+30 minutes", strtotime($jamakhir)));
                          $jamakhir2=$jamakhir;
                          $jamfingerprint = date("H:i", strtotime($request->json('jam')));
                          if (($jamfingerprint >= $cek[0]['jamsebelum_masukkerja']) && ($jamfingerprint<=$cek[0]['jam_keluarjadwal'])) {
                              //menghitung data absen trans pegawai
                              $cari = atts_tran::where('pegawai_id', '=', $request->json('user_id'))
                                  ->where('tanggal', '=', $request->json('tanggal'))
                                  ->where('status_kedatangan', '=', $request->json('status'))
                                  ->count();
                              //jika hasil nya lebih dari 0 maka
                              if ($cari > 0) {
                                  
                              } else {
                                  //menambah data absen trans yang baru
                                  $save = new atts_tran();
                                  $save->pegawai_id = $request->json('user_id');
                                  $save->tanggal = $request->json('tanggal');
                                  $save->jam = $request->json('jam');
                                  $save->lokasi_alat = $request->json('instansi');
                                  $save->status_kedatangan = $request->json('status');
                                  $save->save();

                                  //meubah data masuk
                                  $table = att::where('tanggal_att', '=', $request->json('tanggal'))
                                      ->where('pegawai_id', '=', $request->json('user_id'))
                                      ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                                      ->first();
                                  //                    dd($table);
                                  $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                                      ->where('pegawais.id', '=', $request->json('user_id'))->get();
                                  if ($pegawai[0]['instansi_id']==$request->json('instansi'))
                                  {
                                    $table->jenisabsen_id = "1";
                                  }else {
                                $table->jenisabsen_id = "8";
                                  }
                                  if ($request->json('jam')>$jamakhir)
                                  {
                                    $terlambatnya=$this->kurangwaktu($request->json('jam'),$jamakhir);
                                  }
                                  else {
                                    $terlambatnya="00:00:00";
                                  }
                                  $table->terlambat=$terlambatnya;
                                  $table->jam_masuk = $request->json('jam');
                                  $table->tanggal_att = $request->json('tanggal');
                                  $table->masukinstansi_id = $request->json('instansi');

                                  $table->save();



                                  $instansi = instansi::where('id', '=', $request->json('instansi'))->get();

                                  if ($request->json('jam')>$jamakhir2){

                                    $status = "hadir terlambat";
                                    // dd($status);
                                  }
                                  else {

                                    $status = "hadir";
                                  }
                                  if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                                      $class = "bg-green";
                                  } else {
                                      $class = "bg-yellow";
                                  }
                                  $tanggalbaru = date("d-M-Y");

                                  event(new Timeline($request->json('user_id'), $tanggalbaru, $request->json('jam'), $request->json('instansi'), $request->json('status'), $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                                  return "Success";
                                  //bisadatang
                              }

                          } else {
                              return ("Failed");
                              //tidakbisadatang
                          }
                      }
                  }
                  else {
                    return "Failed";
                    //tidaksukses
                  }
              }
              // ########### absen pulang
              else
              {
                  //cek absen jam masuk yang kosong hari ini
                  $hitungabsen=att::where('pegawai_id','=',$request->json('user_id'))
                      ->where('tanggal_att','=',$request->json('tanggal'))
                     // ->whereNull('jam_masuk')
                      ->count();

                  if ($hitungabsen>0) {
                      $tanggalkemarin=date("Y-m-d",strtotime("-1 day",strtotime($request->json('tanggal'))));

                      // cek absen pulang yg tidak kosong kemarin
                      $cekjamkeluar=att::where('pegawai_id','=',$request->json('user_id'))
                          ->where('tanggal_att','=',$tanggalkemarin)
                          ->whereNull('jam_keluar')
                          ->whereNotNull('jam_masuk')
                          ->count();

                      if ($cekjamkeluar>0) {

                          $absens = att::where('pegawai_id', '=', $request->json('user_id'))
                              ->where('tanggal_att', '=', $tanggalkemarin)
                              ->get();
                         // dd($absens);
                          foreach ($absens as $key => $absen) {
                              //cek kecocokan jam masuk berdasarkan jadwalkerja


                              $cek = jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                                  ->select('jadwalkerjas.id', 'jadwalkerjas.jam_keluarjadwal', 'rulejammasuks.jamsebelum_pulangkerja')
                                  ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                                  ->get();

                              $jamawal = date("H:i", strtotime($cek[0]['jamsebelum_pulangkerja']));
                              $jamakhir = $cek[0]['jam_keluarjadwal'];
                              //menentukan jam toleransi masuk pegawai
                              // $jamakhir2 = date("H:i:s", strtotime("-30 minutes", strtotime($jamakhir)));
                              $jamfingerprint = date("H:i", strtotime($request->json('jam')));
                              $jamakhir2=$jamakhir;
                              // dd($jamawal."    ".$jamfingerprint."    ".$jamakhir2);
                              //if (($jamfingerprint >= $jamakhir2) && ($jamfingerprint <= ( $jamawal))) {
                              if (($jamfingerprint >= $jamakhir2) && ($jamfingerprint <= ( $jamawal))) {
                                  //menghitung data absen trans pegawai
                                  $cari = atts_tran::where('pegawai_id', '=', $request->json('user_id'))
                                      ->where('tanggal', '=', $request->json('tanggal'))
                                      ->where('status_kedatangan', '=', $request->json('status'))
                                      ->count();
                                  //jika hasil nya lebih dari 0 maka
                                  if ($cari > 0) {
                                      //melakukan perubahan data absen trans yang ada
                                      $table = atts_tran::where('tanggal', '=', $request->json('tanggal'))
                                          ->where('pegawai_id', '=', $request->json('user_id'))
                                          ->first();
                                      $table->jam = $request->json('jam');
                                      $table->tanggal = $request->json('tanggal');
                                      $table->save();
                                  } else {
                                      //menambah data absen trans yang baru
                                      $save = new atts_tran();
                                      $save->pegawai_id = $request->json('user_id');
                                      $save->tanggal = $request->json('tanggal');
                                      $save->jam = $request->json('jam');
                                      $save->lokasi_alat = $request->json('instansi');
                                      $save->status_kedatangan = $request->json('status');
                                      $save->save();
                                  }
                                  //meubah data masuk
                                 // dd($absen->jadwalkerja_id);
                                  $table2 = jadwalkerja::find($absen->jadwalkerja_id)
                                      ->get();

                                  if ($table2[0]['jam_masukjadwal']>$table2[0]['jam_keluarjadwal'])
                                  {
                                      if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                                      {
                                          $jamban=$absen->jam_masuk;
                                          $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($table2[0]['jam_keluarjadwal'])));
                                          $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                      }
                                      else
                                      {
                                          $jamban=($table2[0]['jam_masukjadwal']);
                                          $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($table2[0]['jam_keluarjadwal'])));
                                          $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                      }

                                  }
                                  else{
                                      if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                                      {
                                          $jamban=$absen->jam_masuk;
                                          $jamban2=date("Y-m-d H:i:s", strtotime("+0 day", strtotime($table2[0]['jam_keluarjadwal'])));
                                          $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                                      }
                                      else
                                      {
                                          $jamban=($table2[0]['jam_masukjadwal']);
                                          $jamban2=date("Y-m-d H:i:s", strtotime("+0 day", strtotime($table2[0]['jam_keluarjadwal'])));
                                          $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                                      }
                                  }

                                  $table = att::where('tanggal_att', '=', $tanggalkemarin)
                                      ->where('pegawai_id', '=', $request->json('user_id'))
                                      ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                                      ->first();

                                  $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                                      ->where('pegawais.id', '=', $request->json('user_id'))->get();
                                  if (($pegawai[0]['instansi_id']==$request->json('instansi')) && ($table->masukinstansi_id==$request->json('instansi')))
                                  {
                                    $table->jenisabsen_id = "1";
                                  }elseif (($pegawai[0]['instansi_id']!=$request->json('instansi')) && ($table->masukinstansi_id!=$request->json('instansi')))
                                  {
                                    $table->jenisabsen_id = "8";
                                  }
                                  $table->jam_keluar = $request->json('jam');
                                  $table->keluarinstansi_id = $request->json('instansi');
                                  $table->akumulasi_sehari = $akumulasi;
                                  $table->save();

                                  $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                                      ->where('pegawais.id', '=', $request->json('user_id'))->get();

                                  $instansi = instansi::where('id', '=', $request->json('instansi'))->get();
                                  $status = "pulang";
                                  if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                                      $class = "bg-green";
                                  } else {
                                      $class = "bg-yellow";
                                  }
                                  $tanggalbaru = date("d-M-Y");

                                  event(new Timeline($request->json('user_id'), $tanggalbaru, $request->json('jam'), $request->json('instansi'), $request->json('status'), $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                                  return "Success";
                                  //Bisa Pulang
                              }

                              elseif (($jamfingerprint < ( $jamakhir)))

                              {
                                  $cari = atts_tran::where('pegawai_id', '=', $request->json('user_id'))
                                      ->where('tanggal', '=', $request->json('tanggal'))
                                      ->where('status_kedatangan', '=', $request->json('status'))
                                      ->count();
                                  //jika hasil nya lebih dari 0 maka
                                  if ($cari > 0) {
                                      //melakukan perubahan data absen trans yang ada
                                      $table = atts_tran::where('tanggal', '=', $request->json('tanggal'))
                                          ->where('pegawai_id', '=', $request->json('user_id'))
                                          ->first();
                                      $table->jam = $request->json('jam');
                                      $table->tanggal = $request->json('tanggal');
                                      $table->save();
                                  } else {
                                      //menambah data absen trans yang baru
                                      $save = new atts_tran();
                                      $save->pegawai_id = $request->json('user_id');
                                      $save->tanggal = $request->json('tanggal');
                                      $save->jam = $request->json('jam');
                                      $save->lokasi_alat = $request->json('instansi');
                                      $save->status_kedatangan = $request->json('status');
                                      $save->save();
                                  }
                                  //meubah data masuk
                                 // dd($absen->jadwalkerja_id);
                                  $table2 = jadwalkerja::where('id','=',$absen->jadwalkerja_id)
                                      ->get();
                                 // $table2 = att::where('pegawai_id', '=', $request->json('user_id'))
                                 //     ->where('tanggal_att', '=', $tanggalkemarin)
                                 //     ->where('jadwalkerja_id','=',$absen->jadwalkerja_id)
                                 //     ->get();

                                  if ($table2[0]['jam_masukjadwal']>$table2[0]['jam_keluarjadwal'])
                                  {
                                      if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                                      {
                                          $jamban=$absen->jam_masuk;
                                          $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($table2[0]['jam_keluarjadwal'])));
                                          $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                      }
                                      else
                                      {
                                          $jamban=($table2[0]['jam_masukjadwal']);
                                          $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($table2[0]['jam_keluarjadwal'])));
                                          $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                      }

                                  }
                                  else{
                                      if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                                      {
                                          $jamban=$absen->jam_masuk;
                                          $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($table2[0]['jam_keluarjadwal'])));
                                          $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                                      }
                                      else
                                      {
                                          $jamban=($table2[0]['jam_masukjadwal']);
                                          $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($table2[0]['jam_keluarjadwal'])));
                                          $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                                      }

                                     // $jamban=$table2[0]['jam_masukjadwal'];
                                     // $jamban2=$table2[0]['jam_keluarjadwal'];
                                     // $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                                  }

                                  $table = att::where('tanggal_att', '=', $tanggalkemarin)
                                      ->where('pegawai_id', '=', $request->json('user_id'))
                                      ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                                      ->first();

                                  $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                                      ->where('pegawais.id', '=', $request->json('user_id'))->get();
                                  if (($pegawai[0]['instansi_id']==$table->masukinstansi_id) && ($pegawai[0]['instansi_id']==$request->json('instansi')))
                                  {
                                    $table->jenisabsen_id = "1";
                                  }
                                  // elseif (($pegawai[0]['instansi_id']!=$request->json('instansi')) && ($table->masukinstansi_id!=$request->json('instansi')))
                                  else
                                  {
                                  $table->jenisabsen_id = "8";
                                  }

                                  $table->jam_keluar = $request->json('jam');
                                  $table->keluarinstansi_id = $request->json('instansi');
                                  $table->akumulasi_sehari = $akumulasi;
                                  $table->save();

                                  $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                                      ->where('pegawais.id', '=', $request->json('user_id'))->get();

                                  $instansi = instansi::where('id', '=', $request->json('instansi'))->get();
                                  $status = "pulang lebih cepat";
                                  if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                                      $class = "bg-green";
                                  } else {
                                      $class = "bg-yellow";
                                  }
                                  $tanggalbaru = date("d-M-Y");

                                  event(new Timeline($request->json('user_id'), $tanggalbaru, $request->json('jam'), $request->json('instansi'), $request->json('status'), $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                                  return "Failed";
                                  //Pulang Cepat
                              }
                          }
                      }
                      //jika hari kemarin jam pulang nya sudah terisi
                      else
                      {

                         //pencarian absen hari ini yang jam masuk nya terisi
                          $absens = att::where('pegawai_id', '=', $request->json('user_id'))
                              ->where('tanggal_att', '=', $request->json('tanggal'))
                              ->whereNotNull('jam_masuk')
                              ->get();

                          foreach ($absens as $key => $absen) {
                              //cek kecocokan jam masuk berdasarkan jadwalkerja

                              $cek = jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                                  ->select('jadwalkerjas.id', 'jadwalkerjas.jam_keluarjadwal', 'rulejammasuks.jamsebelum_pulangkerja')
                                  ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                                  ->get();
                             // dd("as");
                              $jamawal = date("H:i", strtotime($cek[0]['jamsebelum_pulangkerja']));
                              $jamakhir = $cek[0]['jam_keluarjadwal'];
                              //menentukan jam toleransi masuk pegawai
                              // $jamakhir2 = date("H:i:s", strtotime("-30 minutes", strtotime($jamakhir)));
                              $jamakhir2=$jamakhir;
                              $jamfingerprint = date("H:i", strtotime($request->json('jam')));
                              // dd($jamawal."    ".$jamfingerprint."    ".$jamakhir2);
                              if (($jamfingerprint >= $jamakhir2) && ($jamfingerprint <= ( $jamawal))) {

                                  //menghitung data absen trans pegawai
                                  $cari = atts_tran::where('pegawai_id', '=', $request->json('user_id'))
                                      ->where('tanggal', '=', $request->json('tanggal'))
                                      ->where('status_kedatangan', '=', $request->json('status'))
                                      ->count();
                                  //jika hasil nya lebih dari 0 maka
                                  if ($cari > 0) {
                                      //melakukan perubahan data absen trans yang ada
                                      $table = atts_tran::where('tanggal', '=', $request->json('tanggal'))
                                          ->where('pegawai_id', '=', $request->json('user_id'))
                                          ->first();
                                      $table->jam = $request->json('jam');
                                      $table->tanggal = $request->json('tanggal');
                                      $table->save();
                                  } else {
                                      //menambah data absen trans yang baru
                                      $save = new atts_tran();
                                      $save->pegawai_id = $request->json('user_id');
                                      $save->tanggal = $request->json('tanggal');
                                      $save->jam = $request->json('jam');
                                      $save->lokasi_alat = $request->json('instansi');
                                      $save->status_kedatangan = $request->json('status');
                                      $save->save();
                                  }
                                  //meubah data masuk
                                  //meubah data masuk
                                  $table2 = jadwalkerja::where('id','=',$absen->jadwalkerja_id)
                                      ->get();
                                 // dd($table2);
                                 // dd($table2[0]['jam_masukjadwal']>$table2[0]['jam_keluarjadwal']);
                                  if ($table2[0]['jam_masukjadwal']>$table2[0]['jam_keluarjadwal'])
                                  {
                                      if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                                      {
                                          $jamban=$absen->jam_masuk;
                                          $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($table2[0]['jam_keluarjadwal'])));
                                          $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                      }
                                      else
                                      {
                                          $jamban=($table2[0]['jam_masukjadwal']);
                                          $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($table2[0]['jam_keluarjadwal'])));
                                          $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                      }

                                  }
                                  else
                                  {
                                      if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                                      {
                                         // dd("terlambat");
                                           $jamban=date("Y-m-d H:i:s",strtotime("+1 day",strtotime($absen->jam_masuk)));
                                          $jamban2=date("Y-m-d H:i:s",strtotime("+1 day", strtotime($table2[0]['jam_keluarjadwal'])));
                                          $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                                         // dd("jam masuk=".$jamban. "jam keluar=".$jamban2." akumulasi=".$akumulasi);
                                      }
                                      else
                                      {
                                          $jamban=($table2[0]['jam_masukjadwal']);
                                          $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($table2[0]['jam_keluarjadwal'])));
                                          $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                                      }
                                     // dd("jam masuk=".$jamban. "jam keluar=".$jamban2." akumulasi=".$akumulasi);
                                     // $jamban=$table2[0]['jam_masukjadwal'];
                                     // $jamban2=$table2[0]['jam_keluarjadwal'];
                                     // $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                                  }

                                  $table = att::where('tanggal_att', '=', $request->json('tanggal'))
                                      ->where('pegawai_id', '=', $request->json('user_id'))
                                      ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                                      ->first();

                                  $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                                      ->where('pegawais.id', '=', $request->json('user_id'))->get();
                                      if (($pegawai[0]['instansi_id']==$table->masukinstansi_id) && ($pegawai[0]['instansi_id']==$request->json('instansi')))
                                      {
                                        $table->jenisabsen_id = "1";
                                      }
                                      // elseif (($pegawai[0]['instansi_id']!=$request->json('instansi')) && ($table->masukinstansi_id!=$request->json('instansi')))
                                      else
                                      {
                                      $table->jenisabsen_id = "8";
                                      }

                                  $table->jam_keluar = $request->json('jam');
                                  $table->keluarinstansi_id = $request->json('instansi');
                                  $table->akumulasi_sehari = $akumulasi;
                                  $table->save();

                                  $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                                      ->where('pegawais.id', '=', $request->json('user_id'))->get();

                                  $instansi = instansi::where('id', '=', $request->json('instansi'))->get();

                                  if (($jamfingerprint >= $jamakhir2) && ($jamfingerprint <= ( $jamawal))) {
                                    // dd("jam awal".$jamakhir2." Jam finger ".$jamfingerprint." jam akhir".$jamawal);

                                    $status="pulang";
                                  }
                                  if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                                      $class = "bg-green";
                                  } else {
                                      $class = "bg-yellow";
                                  }
                                  $tanggalbaru = date("d-M-Y");

                                  event(new Timeline($request->json('user_id'), $tanggalbaru, $request->json('jam'), $request->json('instansi'), $request->json('status'), $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                                  return "Success";
                                  //Bisa Pulan 3
                              }
                              elseif(($jamfingerprint < ( $jamakhir)))

                              {
                                  $cari = atts_tran::where('pegawai_id', '=', $request->json('user_id'))
                                      ->where('tanggal', '=', $request->json('tanggal'))
                                      ->where('status_kedatangan', '=', $request->json('status'))
                                      ->count();
                                  //jika hasil nya lebih dari 0 maka
                                  if ($cari > 0) {
                                      //melakukan perubahan data absen trans yang ada
                                      $table = atts_tran::where('tanggal', '=', $request->json('tanggal'))
                                          ->where('pegawai_id', '=', $request->json('user_id'))
                                          ->first();
                                      $table->jam = $request->json('jam');
                                      $table->tanggal = $request->json('tanggal');
                                      $table->save();
                                  } else {
                                      //menambah data absen trans yang baru
                                      $save = new atts_tran();
                                      $save->pegawai_id = $request->json('user_id');
                                      $save->tanggal = $request->json('tanggal');
                                      $save->jam = $request->json('jam');
                                      $save->lokasi_alat = $request->json('instansi');
                                      $save->status_kedatangan = $request->json('status');
                                      $save->save();
                                  }
                                  //meubah data masuk
                                 // dd($absen->jadwalkerja_id);
                                 // $table2 = jadwalkerja::find($absen->jadwalkerja_id)
                                 //     ->get();
                                  $table2 = jadwalkerja::where('id','=',$absen->jadwalkerja_id)
                                      ->get();
                                 // dd($table2);
                                 // dd($table2[0]['jam_masukjadwal']>$table2[0]['jam_keluarjadwal']);
                                  if ($table2[0]['jam_masukjadwal']>$table2[0]['jam_keluarjadwal'])
                                  {
                                      if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                                      {
                                          $jamban=$absen->jam_masuk;
                                          $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($table2[0]['jam_keluarjadwal'])));
                                          $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                      }
                                      else
                                      {
                                          $jamban=($table2[0]['jam_masukjadwal']);
                                          $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($table2[0]['jam_keluarjadwal'])));
                                          $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                      }

                                  }
                                  else
                                  {
                                      if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                                      {
                                         // dd("terlambat");
                                          $jamban=date("Y-m-d H:i:s",strtotime("+1 day",strtotime($absen->jam_masuk)));
                                          $jamban2=date("Y-m-d H:i:s",strtotime("+1 day", strtotime($table2[0]['jam_keluarjadwal'])));
                                          $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                                         // dd("jam masuk=".$jamban. "jam keluar=".$jamban2." akumulasi=".$akumulasi);
                                      }
                                      else
                                      {
                                          $jamban=($table2[0]['jam_masukjadwal']);
                                          $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($table2[0]['jam_keluarjadwal'])));
                                          $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                                      }
                                     // dd("jam masuk=".$jamban. "jam keluar=".$jamban2." akumulasi=".$akumulasi);
                                     // $jamban=$table2[0]['jam_masukjadwal'];
                                     // $jamban2=$table2[0]['jam_keluarjadwal'];
                                     // $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                                  }

                                  $table = att::where('tanggal_att', '=', $request->json('tanggal'))
                                      ->where('pegawai_id', '=', $request->json('user_id'))
                                      ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                                      ->first();
                                 // dd($table);
                                  $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                                      ->where('pegawais.id', '=', $request->json('user_id'))->get();
                                      if (($pegawai[0]['instansi_id']==$table->masukinstansi_id) && ($pegawai[0]['instansi_id']==$request->json('instansi')))
                                      {
                                        $table->jenisabsen_id = "1";
                                      }
                                      // elseif (($pegawai[0]['instansi_id']!=$request->json('instansi')) && ($table->masukinstansi_id!=$request->json('instansi')))
                                      else
                                      {
                                      $table->jenisabsen_id = "8";
                                      }
                                  $table->jam_keluar = $request->json('jam');
                                  $table->keluarinstansi_id = $request->json('instansi');
                                  $table->akumulasi_sehari = $akumulasi;
                                  $table->save();

                                  $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                                      ->where('pegawais.id', '=', $request->json('user_id'))->get();

                                  $instansi = instansi::where('id', '=', $request->json('instansi'))->get();
                                  $status = "pulang lebih cepat";
                                  if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                                      $class = "bg-green";
                                  } else {
                                      $class = "bg-yellow";
                                  }
                                  $tanggalbaru = date("d-M-Y", strtotime($tanggalkemarin));

                                  event(new Timeline($request->json('user_id'), $tanggalbaru, $request->json('jam'), $request->json('instansi'), $request->json('status'), $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                                  return "Success";
                                  //Pulang Cepat
                              }
                          }
                      }
                  }
              }
        }
        else{
            $statusauth=false;
            return "Failed Token";
            //Token Salah 
        }

    }

    public function show(){
        $tanggal=date('H:i');
        $tabel=atts_tran::join('pegawais','atts_trans.pegawai_id','=','pegawais.id')->join('instansis','atts_trans.lokasi_alat','=','instansis.id')->where('tanggal','=',$tanggal)->get();
//        dd($tanggal);
        return $tabel;
    }

    public function go(Request $request){
        $name=$request->name;
        $url="http://eabsen.dev/timeline?name=".$name;
        return new RedirectResponse($url);
    }

    public function encryptdata(Request $request){
        $jam=$request->json('jam2');
        $basic=substr($jam,5);
        $hasilbasic=str_replace(":","",$basic);
        $hasil=$this->encryptOTP($hasilbasic);
        $statusauth="0";
        $auth=$request->json('auth');

        if ($hasil==$auth){
            $statusauth="1";
        }else{
            $statusauth="0";
        }

        return $hasil;
    }

    public function hapusatt($id){
        $table=att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
                ->where('pegawais.instansi_id','=',$id)
                ->select('atts.*','pegawais.nip','pegawais.nama','pegawais.instansi_id')
                ->get();

        foreach ($table as $key =>$data){
            $hapus=att::find($data->id);
            // dd($data);
            $hapus->delete();
        }
        // dd($table);
        return "selesai";
    }
}
