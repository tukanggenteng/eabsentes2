<?php

namespace App\Http\Controllers;

use App\att;
use App\masterbulanan;
use App\pegawai;
use App\instansi;
use App\rekapbulanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class MasterAbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $date=date('N');

        $sekarang=date('Y-m-d');
        $status=false;

        if ($date==1){
            $hari='Senin';
            $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
            $akhir=date("Y-m-d",strtotime("-1 days",strtotime($sekarang)));
            $status=true;
        }
        elseif ($date==2){
            $hari='Selasa';
            $awal=date("Y-m-d",strtotime("-8 days",strtotime($sekarang)));
            $akhir=date("Y-m-d",strtotime("-2 days",strtotime($sekarang)));
            $status=true;
        }
        elseif ($date==3) {
          // dd("asda");
          $hari='Rabu';
          $awal=date("Y-m-d",strtotime("-9 days",strtotime($sekarang)));
          $akhir=date("Y-m-d",strtotime("-3 days",strtotime($sekarang)));
          $status=false;
        }

        // $bulan=date("m",strtotime("-1 month",strtotime($bulan)));
        // $tahun=date("Y");
        if ($status==true)
        {
        // dd($awal." + ".$akhir);
        // $pegawais=pegawai::join('rulejadwalpegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
        //     ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
        //     ->where('pegawais.status_aktif','=','1')
        //     ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        //     ->get();
        // dd($pegawais);


        $idpegawais=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
            ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
            ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->where('atts.tanggal_att','>=',$awal)
            ->where('atts.tanggal_att','<=',$akhir)
            ->select('atts.pegawai_id')
            ->distinct()
            ->get();
        foreach ($idpegawais as $key => $idpegawai) {

                  $harikerja = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                      ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                      ->where('atts.tanggal_att','>=',$awal)
                      ->where('atts.tanggal_att','<=',$akhir)
                      ->where('atts.jenisabsen_id','!=','11')
                      ->where('atts.jenisabsen_id','!=','9')
                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                      ->select('atts.tanggal_att')
                      ->count();
                      // dd($awal.' = '.$akhir);
                  $perbaikanakumulasi=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                      ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                      ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                      ->where('atts.tanggal_att','>=',$awal)
                      ->where('atts.tanggal_att','<=',$akhir)
                      ->where('atts.jenisabsen_id','=','1')
                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                      ->where('atts.jam_keluar','=',null)
                      ->select('atts.*','jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal')
                      ->get();

                  foreach ($perbaikanakumulasi as $key => $value) {
                    if ($value->jam_masukjadwal > $value->jam_keluarjadwal)
                    {
                        if ($value->jam_masuk > $value->jam_masukjadwal)
                        {
                            $jamban=$value->jam_masuk;
                            $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($value->jam_keluarjadwal)));
                            $selisih=$this->kurangwaktu($jamban,$jamban2);
                        }
                        else
                        {
                            $jamban=($value->jam_masukjadwal);
                            $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($value->jam_keluarjadwal)));
                            $selisih=$this->kurangwaktu($jamban,$jamban2);
                        }

                    }
                    else{
                        if ($value->jam_masuk > $value->jam_masukjadwal)
                        {
                            $jamban=$value->jam_masuk;
                            $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($value->jam_keluarjadwal)));
                            $selisih=$this->kurangwaktu($jamban2,$jamban);
                        }
                        else
                        {
                            $jamban=($value->jam_masuk);
                            $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($value->jam_keluarjadwal)));
                            $selisih=$this->kurangwaktu($jamban2,$jamban);
                        }

                    }


                      $time_array = explode(':', $selisih);
                      $hours = (int)$time_array[0];
                      $minutes = (int)$time_array[1];
                      $seconds = (int)$time_array[2];

                      $total_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;

                      $average = floor($total_seconds / 2);


                      $init = 685;
                      $hours2 = floor($average / 3600);
                      $minutes2 = floor(($average / 60) % 60);
                      $seconds2 = $average % 60;

                      $average=$hours2.":".$minutes2.":".$seconds2;


                      $perbaikanakumulasi2=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                          ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                          ->where('atts.tanggal_att','=',$value->tanggal_att)
                          ->where('atts.jenisabsen_id','=','1')
                          ->where('atts.id','=',$value->id)
                          ->where('atts.jam_keluar','=',null)
                          ->select('atts.*','jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal')
                          ->first();

                      $perbaikanakumulasi2->akumulasi_sehari=$selisih;
                      $perbaikanakumulasi2->save();
                  }


                  $hadir = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                      ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                      ->where('atts.tanggal_att','>=',$awal)
                      ->where('atts.tanggal_att','<=',$akhir)
                      ->where('jenisabsen_id', '=', '1')
                    //   ->where('jenisabsen_id','=','10')
                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                      ->select('atts.tanggal_att')
                      ->count();

                  $ijinterlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                      ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                      ->where('atts.tanggal_att','>=',$awal)
                      ->where('atts.tanggal_att','<=',$akhir)
                      ->where('jenisabsen_id', '=', '10')
                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                      ->count();

                  $absen = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                      ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                      ->where('atts.tanggal_att','>=',$awal)
                      ->where('atts.tanggal_att','<=',$akhir)
                      ->where('jenisabsen_id', '=', '2')
                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                      ->count();

                  $ijin = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                      ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                      ->where('atts.tanggal_att','>=',$awal)
                      ->where('atts.tanggal_att','<=',$akhir)
                      ->where('jenisabsen_id', '=', '3')
                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                      ->count();

                  $sakit = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                      ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                      ->where('atts.tanggal_att','>=',$awal)
                      ->where('atts.tanggal_att','<=',$akhir)
                      ->where('jenisabsen_id', '=', '5')
                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                      ->count();

                  $cuti = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                      ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                      ->where('atts.tanggal_att','>=',$awal)
                      ->where('atts.tanggal_att','<=',$akhir)
                      ->where('jenisabsen_id', '=', '4')
                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                      ->count();

                  $tugasluar = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                      ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                      ->where('atts.tanggal_att','>=',$awal)
                      ->where('atts.tanggal_att','<=',$akhir)
                      ->where('jenisabsen_id', '=', '7')
                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                      ->count();

                  $tugasbelajar = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                      ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                      ->where('atts.tanggal_att','>=',$awal)
                      ->where('atts.tanggal_att','<=',$akhir)
                      ->where('jenisabsen_id', '=', '6')
                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                      ->count();

                  $rapatundangan = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                      ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                      ->where('atts.tanggal_att','>=',$awal)
                      ->where('atts.tanggal_att','<=',$akhir)
                      ->where('jenisabsen_id', '=', '8')
                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                      ->count();

                  $terlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                      ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                      ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                      ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                      ->where('atts.jam_masuk', '>','jadwalkerjas.jam_masukjadwal')
                      ->where('atts.tanggal_att','>=',$awal)
                      ->where('atts.tanggal_att','<=',$akhir)
                      ->whereNotNull('atts.jam_masuk')
                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                      ->count();

                  $tidakterlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                      ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                      ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                      ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                      ->where('atts.jam_masuk', '<=', 'jadwalkerjas.jam_masukjadwal')

                      ->where('atts.tanggal_att','>=',$awal)
                      ->where('atts.tanggal_att','<=',$akhir)
                      ->whereNotNull('atts.jam_masuk')
                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                      ->count();

                  $pulangcepat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                      ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                      ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                      ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                      ->where('atts.jam_keluar', '<', 'jadwalkerjas.jam_keluarjadwal')
                      ->where('atts.jam_keluar', '=', null)

                      ->where('atts.tanggal_att','>=',$awal)
                      ->where('atts.tanggal_att','<=',$akhir)
                      ->where('atts.jenisabsen_id', '=', '1')
                      ->whereNotNull('atts.jam_masuk')
                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                      ->count();

                  if (($tidakterlambat==0) && ($harikerja==0) || ($tidakterlambat==0))
                  {
                      $presentaseapel=0;
                  }
                  else {
                      $presentaseapel=$tidakterlambat/$harikerja*100;
                  }

                  if (($absen==0) && ($harikerja==0) || ($absen==0))
                  {
                      $presentasetidakhadir=0;
                  }
                  else {
                              $presentasetidakhadir=$absen/$harikerja*100;
                  }


                $totalakumulasi = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                ->where('atts.tanggal_att','>=',$awal)
                ->where('atts.tanggal_att','<=',$akhir)
                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(atts.akumulasi_sehari))) as total'))
                ->first();

                  $totalterlambat=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                  ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                  ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                  ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                  ->where('atts.tanggal_att','>=',$awal)
                  ->where('atts.tanggal_att','<=',$akhir)
                  ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                  ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(atts.terlambat))) as total'))
                  ->first();

                  $table=new rekapbulanan();
                  $table->periode=$awal;
                  $table->pegawai_id=$idpegawai->pegawai_id;
                  $table->hari_kerja=$harikerja;
                  $table->hadir=$hadir;
                  $table->tanpa_kabar=$absen;
                  $table->ijin=$ijin;
                  $table->ijinterlambat=$ijinterlambat;
                  $table->sakit=$sakit;
                  $table->cuti=$cuti;
                  $table->tugas_luar=$tugasluar;
                  $table->tugas_belajar=$tugasbelajar;
                  $table->terlambat=$terlambat;
                  $table->rapatundangan=$rapatundangan;
                  $table->pulang_cepat=$pulangcepat;
                  $table->persentase_apel=$terlambat+$ijinterlambat;
                  $table->persentase_tidakhadir=$absen;
                  $table->total_akumulasi=$totalakumulasi->total;
                  $table->total_terlambat=$totalterlambat->total;
                  $table->instansi_id=null;
                  $table->save();

                  $table=new masterbulanan();
                  $table->periode=$awal;
                  $table->pegawai_id=$idpegawai->pegawai_id;
                  $table->hari_kerja=$harikerja;
                  $table->hadir=$hadir;
                  $table->tanpa_kabar=$absen;
                  $table->ijin=$ijin;
                  $table->sakit=$sakit;
                  $table->cuti=$cuti;
                  $table->ijinterlambat=$ijinterlambat;
                  $table->tugas_luar=$tugasluar;
                  $table->tugas_belajar=$tugasbelajar;
                  $table->terlambat=$terlambat;
                  $table->rapatundangan=$rapatundangan;
                  $table->pulang_cepat=$pulangcepat;
                  $table->persentase_apel=$terlambat+$ijinterlambat;
                  $table->persentase_tidakhadir=$absen;
                  $table->total_akumulasi=$totalakumulasi->total;
                  $table->total_terlambat=$totalterlambat->total;
                  $table->instansi_id=null;
                  $table->save();
        }

        return redirect('/home')->with('rekap','Rekap bulanan sukses.');
      }
      else {
        return redirect('/home')->with('rekap','Rekap bulanan gagal.');
      }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}
