<?php

namespace App\Http\Controllers;
use App\att;
use App\pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DetailAbsenController extends Controller
{
    //

    public function absentharian()
    {
      $tanggal=date("Y-m-d");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','2')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->where('atts.tanggal_att','=',$tanggal)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
        return view('absent.kadis.detailabsenharian',['tables'=>$tables]);
      }
      else {
        return view('absent.detailabsenharian',['tables'=>$tables]);
      }
    }

    public function sakitharian(){
      $tanggal=date("Y-m-d");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','5')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->where('atts.tanggal_att','=',$tanggal)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
      return view('absent.kadis.detailsakitharian',['tables'=>$tables]);
      }
      else {
      return view('absent.detailsakitharian',['tables'=>$tables]);
      }
    }

    public function ijinharian(){
      $tanggal=date("Y-m-d");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','3')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
      'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->where('atts.tanggal_att','=',$tanggal)
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {

        return view('absent.kadis.detailijinharian',['tables'=>$tables]);
      }
      else {
      return view('absent.detailijinharian',['tables'=>$tables]);
      }
    }

    public function cutiharian(){
      $tanggal=date("Y-m-d");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','4')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->where('atts.tanggal_att','=',$tanggal)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
      return view('absent.kadis.detailcutiharian',['tables'=>$tables]);
      }
      else {
      return view('absent.detailcutiharian',['tables'=>$tables]);
      }
    }

    public function tugasbelajarharian(){
      $tanggal=date("Y-m-d");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','6')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->where('atts.tanggal_att','=',$tanggal)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
      return view('absent.kadis.detailtugasbelajarharian',['tables'=>$tables]);
      }
      else {
      return view('absent.detailtugasbelajarharian',['tables'=>$tables]);
      }
    }

    public function tugasluarharian(){
      $tanggal=date("Y-m-d");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','7')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->where('atts.tanggal_att','=',$tanggal)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
      return view('absent.kadis.detailtugasluarharian',['tables'=>$tables]);
      }
      else {
      return view('absent.detailtugasluarharian',['tables'=>$tables]);
      }
    }

    public function terlambatharian(){
      $tanggal=date("Y-m-d");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.terlambat','!=','00:00:00')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->where('atts.tanggal_att','=',$tanggal)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
      return view('absent.kadis.detailterlambatharian',['tables'=>$tables]);
      }
      else {
      return view('absent.detailterlambatharian',['tables'=>$tables]);
      }
    }

    public function rapatundanganharian(){
      $tanggal=date("Y-m-d");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','8')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->where('atts.tanggal_att','=',$tanggal)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
        return view('absent.kadis.detailrapatundanganharian',['tables'=>$tables]);
      }
      else {
      return view('absent.detailrapatundanganharian',['tables'=>$tables]);
      }
    }


    // bulanan

    public function absentbulan(){
      $bulan=date("m");
      $tahun=date("Y");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','2')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->whereMonth('atts.tanggal_att','=',$bulan)
      ->whereYear('atts.tanggal_att','=',$tahun)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
      return view('absent.kadis.detailabsenbulan',['tables'=>$tables]);
      }
      else {
      return view('absent.detailabsenbulan',['tables'=>$tables]);
      }
    }

    public function sakitbulan(){
      $bulan=date("m");
      $tahun=date("Y");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','5')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->whereMonth('atts.tanggal_att','=',$bulan)
      ->whereYear('atts.tanggal_att','=',$tahun)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
      return view('absent.kadis.detailsakitbulan',['tables'=>$tables]);
      }
      else {
      return view('absent.detailsakitbulan',['tables'=>$tables]);
      }
    }

    public function ijinbulan(){
      $bulan=date("m");
      $tahun=date("Y");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','3')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->whereMonth('atts.tanggal_att','=',$bulan)
      ->whereYear('atts.tanggal_att','=',$tahun)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
      return view('absent.kadis.detailijinbulan',['tables'=>$tables]);
      }
      else {
      return view('absent.detailijinbulan',['tables'=>$tables]);
      }
    }

    public function cutibulan(){
      $bulan=date("m");
      $tahun=date("Y");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','4')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->whereMonth('atts.tanggal_att','=',$bulan)
      ->whereYear('atts.tanggal_att','=',$tahun)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
        return view('absent.kadis.detailcutibulan',['tables'=>$tables]);
      }
      else {
      return view('absent.detailcutibulan',['tables'=>$tables]);
      }
    }

    public function tugasbelajarbulan(){
      $bulan=date("m");
      $tahun=date("Y");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','6')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->whereMonth('atts.tanggal_att','=',$bulan)
      ->whereYear('atts.tanggal_att','=',$tahun)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
      return view('absent.kadis.detailtugasbelajarbulan',['tables'=>$tables]);
      }
      else {
      return view('absent.detailtugasbelajarbulan',['tables'=>$tables]);

      }
    }

    public function tugasluarbulan(){
      $bulan=date("m");
      $tahun=date("Y");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','7')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->whereMonth('atts.tanggal_att','=',$bulan)
      ->whereYear('atts.tanggal_att','=',$tahun)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
      return view('absent.kadis.detailtugasluarbulan',['tables'=>$tables]);
      }
      else {
      return view('absent.detailtugasluarbulan',['tables'=>$tables]);
      }
    }

    public function terlambatbulan(){
      $bulan=date("m");
      $tahun=date("Y");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.terlambat','!=','00:00:00')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->whereMonth('atts.tanggal_att','=',$bulan)
      ->whereYear('atts.tanggal_att','=',$tahun)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
      return view('absent.kadis.detailterlambatbulan',['tables'=>$tables]);
      }
      else {
      return view('absent.detailterlambatbulan',['tables'=>$tables]);
      }
    }

    public function rapatundanganbulan(){
      $bulan=date("m");
      $tahun=date("Y");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','8')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->whereMonth('atts.tanggal_att','=',$bulan)
      ->whereYear('atts.tanggal_att','=',$tahun)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
      return view('absent.kadis.detailrapatundanganbulan',['tables'=>$tables]);
      }
      else {
      return view('absent.detailrapatundanganbulan',['tables'=>$tables]);
      }
    }

    // tahun

    public function absenttahun(){
      $tahun=date("Y");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','2')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->whereYear('atts.tanggal_att','=',$tahun)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {

        return view('absent.kadis.detailabsentahun',['tables'=>$tables]);
      }
      else {

        return view('absent.detailabsentahun',['tables'=>$tables]);
      }
    }

    public function sakittahun(){
      $tahun=date("Y");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','5')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->whereYear('atts.tanggal_att','=',$tahun)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
      return view('absent.kadis.detailsakittahun',['tables'=>$tables]);
      }
      else {
        return view('absent.detailsakittahun',['tables'=>$tables]);
      }
    }

    public function ijintahun(){
      $tahun=date("Y");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','3')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->whereYear('atts.tanggal_att','=',$tahun)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {

        return view('absent.kadis.detailijintahun',['tables'=>$tables]);
      }
      else {
      return view('absent.detailijintahun',['tables'=>$tables]);

      }
    }

    public function cutitahun(){
      $bulan=date("m");
      $tahun=date("Y");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','4')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->whereYear('atts.tanggal_att','=',$tahun)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
      return view('absent.kadis.detailcutitahun',['tables'=>$tables]);
      }
      else {
      return view('absent.detailcutitahun',['tables'=>$tables]);
      }
    }

    public function tugasbelajartahun(){
      $bulan=date("m");
      $tahun=date("Y");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','6')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->whereYear('atts.tanggal_att','=',$tahun)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
      return view('absent.kadis.detailtugasbelajartahun',['tables'=>$tables]);
      }
      else {
      return view('absent.detailtugasbelajartahun',['tables'=>$tables]);

      }
    }

    public function tugasluartahun(){
      $bulan=date("m");
      $tahun=date("Y");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','7')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->whereYear('atts.tanggal_att','=',$tahun)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
      return view('absent.kadis.detailtugasluartahun',['tables'=>$tables]);
      }
      else {
      return view('absent.detailtugasluartahun',['tables'=>$tables]);
      }
    }

    public function terlambattahun(){
      $bulan=date("m");
      $tahun=date("Y");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.terlambat','!=','00:00:00')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->whereYear('atts.tanggal_att','=',$tahun)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
        return view('absent.kadis.detailterlambattahun',['tables'=>$tables]);
      }
      else{
        return view('absent.detailterlambattahun',['tables'=>$tables]);
      }

    }

    public function rapatundangantahun(){
      $bulan=date("m");
      $tahun=date("Y");
      $tables=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
      ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
      ->leftJoin('jenisabsens','jenisabsens.id','=','atts.jenisabsen_id')
      ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
      ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
      ->where('atts.jenisabsen_id','=','8')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->whereYear('atts.tanggal_att','=',$tahun)
      ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
      ->orderBy('atts.tanggal_att','desc')
      ->paginate(30);

      if (Auth::user()->role->namaRole=="kadis")
      {
        return view('absent.kadis.detailrapatundangantahun',['tables'=>$tables]);
      }
      else {
      return view('absent.detailrapatundangantahun',['tables'=>$tables]);
      }
    }

}
