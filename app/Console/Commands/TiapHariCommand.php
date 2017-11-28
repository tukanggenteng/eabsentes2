<?php

namespace App\Console\Commands;
use App\att;
use App\harikerja;
use App\instansi;
use App\pegawai;
use Illuminate\Support\Facades\DB;
use App\finalrekapbulanan;
use App\rekapbulanan;
use App\masterbulanan;
use App\rulejadwalpegawai;
use Illuminate\Console\Command;

class TiapHariCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TiapHariCommand:tambahabsen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menambah Absen';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //

        $tanggal=date('d');
        if ($tanggal==8){
          $sekarang=date("Y-m-d");
          $bulan=date("Y-m",strtotime("-1 month",strtotime($sekarang)));
          // $bulan=date("m");
          $pecah=explode("-",$bulan);
          $bulan=$pecah[1];
          $tahun=$pecah[0];

          $idpegawais=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
              ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
              ->whereMonth('atts.tanggal_att','=',$bulan)
              ->whereYear('atts.tanggal_att','=',$tahun)
              ->select('atts.pegawai_id')
              ->distinct()
              ->get();
              // dd("asdasd");
          foreach ($idpegawais as $key => $idpegawai) {

                    $harikerja = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                        // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->where('atts.jenisabsen_id','!=','9')
                        ->where('atts.jenisabsen_id','!=','11')
                        ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                        ->select('atts.tanggal_att')
                        ->count();

                    $perbaikanakumulasi=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                        ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                        // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->where('atts.jenisabsen_id','=','1')
                        ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                        ->where('atts.jam_keluar','=',null)
                        ->select('atts.*','jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal')
                        ->get();

                    foreach ($perbaikanakumulasi as $key => $value) {

                        $start = date_create($value->jam_keluarjadwal);
                        $end = date_create($value->jam_masukjadwal);
                        $selisih=date_diff($start,$end);

                        $selisih=$selisih->format("%H:%i:%s");
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
                            // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
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
                        // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->where('jenisabsen_id', '=', '1')
                        ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                        ->select('atts.tanggal_att')
                        ->count();

                    $ijinterlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                        // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->where('jenisabsen_id', '=', '10')
                        ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                        ->count();

                    $absen = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                        // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->where('jenisabsen_id', '=', '2')
                        ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                        ->count();

                    $ijin = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                        // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->where('jenisabsen_id', '=', '3')
                        ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                        ->count();

                    $sakit = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                        // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->where('jenisabsen_id', '=', '5')
                        ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                        ->count();

                    $cuti = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                        // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->where('jenisabsen_id', '=', '4')
                        ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                        ->count();

                    $tugasluar = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                        // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->where('jenisabsen_id', '=', '7')
                        ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                        ->count();

                    $tugasbelajar = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->where('jenisabsen_id', '=', '6')
                        ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                        ->count();

                    $rapatundangan = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->where('jenisabsen_id', '=', '8')
                        ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                        ->count();

                    $terlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                        ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                        ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                        ->where('atts.jam_masuk', '>','jadwalkerjas.jam_masukjadwal')
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->whereNotNull('atts.jam_masuk')
                        ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                        ->count();

                    $tidakterlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                        ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                        ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                        ->where('atts.jam_masuk', '<=', 'jadwalkerjas.jam_masukjadwal')
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->whereNotNull('atts.jam_masuk')
                        ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                        ->count();

                    $pulangcepat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                        ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                        ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                        ->where('atts.jam_keluar', '<', 'jadwalkerjas.jam_keluarjadwal')
                        ->where('atts.jam_keluar', '=', null)
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
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
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                        ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(atts.akumulasi_sehari))) as total'))
                        ->first();

                    $totalterlambat=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                    ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                    ->whereMonth('atts.tanggal_att','=',$bulan)
                    ->whereYear('atts.tanggal_att','=',$tahun)
                    ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(atts.terlambat))) as total'))
                    ->first();

                    $table=new finalrekapbulanan();
                    $table->periode=$sekarang;
                    $table->pegawai_id=$idpegawai->pegawai_id;
                    $table->hari_kerja=$harikerja;
                    $table->hadir=$hadir;
                    $table->tanpa_kabar=$absen;
                    $table->ijin=$ijin;
                    $table->sakit=$sakit;
                    $table->cuti=$cuti;
                    $table->tugas_luar=$tugasluar;
                    $table->tugas_belajar=$tugasbelajar;
                    $table->terlambat=$terlambat;
                    $table->rapatundangan=$rapatundangan;
                    $table->pulang_cepat=$pulangcepat;
                    $table->persentase_apel=$presentaseapel;
                    $table->persentase_tidakhadir=$presentasetidakhadir;
                    $table->total_akumulasi=$totalakumulasi->total;
                    $table->total_terlambat=$totalterlambat->total;
                    $table->instansi_id=null;
                    $table->save();
          }
        }

        $date=date('N');
        if ($date==1){
            $hari='Senin';
        }
        elseif ($date==2){
            $hari='Selasa';
        }
        elseif ($date==3){
            $hari='Rabu';

                    $date=date('N');

                    $sekarang=date('Y-m-d');


                    $awal=date("Y-m-d",strtotime("-9 days",strtotime($sekarang)));
                    $akhir=date("Y-m-d",strtotime("-3 days",strtotime($sekarang)));

                    $instansis=pegawai::where('instansi_id','!=',null)->get();
                    // dd($instansis);
                    foreach ($instansis as $key => $instansi) {
                      $cekrekap=rekapbulanan::leftJoin('pegawais','pegawais.id','=','rekapbulanans.pegawai_id')
                          ->where('rekapbulanans.pegawai_id','=',$instansi->id)
                          ->where('rekapbulanans.periode','=',$awal)
                          ->count();
                      // dd($cekrekap);

                      $cekatt = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                          ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                          ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                          ->where('atts.tanggal_att','>=',$awal)
                          ->where('atts.tanggal_att','<=',$akhir)
                          ->where('atts.pegawai_id','=',$instansi->id)
                          ->count();
                      // dd($cekatt);
                      if (($cekrekap > 0) || ($cekatt==0))
                      {

                      }else {
                        // $idpegawais=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                        //     ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                        //     ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                        //     ->where('atts.tanggal_att','>=',$awal)
                        //     ->where('atts.tanggal_att','<=',$akhir)
                        //     ->select('atts.pegawai_id')
                        //     ->distinct()
                        //     ->get();

                            $idpegawais=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                                ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->where('atts.tanggal_att','>=',$awal)
                                ->where('atts.tanggal_att','<=',$akhir)
                                ->select('atts.pegawai_id')
                                ->distinct()
                                ->get();
                              // dd($idpegawais);
                            foreach ($idpegawais as $key => $idpegawai) {

                              $harikerja = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                  // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                  ->where('atts.tanggal_att','>=',$awal)
                                  ->where('atts.tanggal_att','<=',$akhir)
                                  ->where('atts.jenisabsen_id','!=','11')
                                  ->where('atts.jenisabsen_id','!=','9')
                                  ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                  ->select('atts.tanggal_att')
                                  ->count();
                                  // dd($harikerja);
                                  $perbaikanakumulasi=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                      ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                                      // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
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
                                          $start = date_create($value->jam_masuk);
                                          $end = date_create($value->jam_keluarjadwal);
                                          $end=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($end)));
                                          $selisih=date_diff($end,$start);

                                            // $jamban=$value->jam_masuk;
                                            // $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($value->jam_keluarjadwal)));
                                            // $selisih=$this->kurangwaktu($jamban,$jamban2);
                                        }
                                        else
                                        {
                                          $start = date_create($value->jam_masukjadwal);
                                          $end = date_create($value->jam_keluarjadwal);
                                          $end=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($end)));
                                          $selisih=date_diff($end,$start);
                                            // $jamban=($value->jam_masukjadwal);
                                            // $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($value->jam_keluarjadwal)));
                                            // $selisih=$this->kurangwaktu($jamban,$jamban2);
                                        }

                                    }
                                    else{
                                        if ($value->jam_masuk > $value->jam_masukjadwal)
                                        {
                                          $start = date_create($value->jam_masuk);
                                          $end = date_create($value->jam_keluarjadwal);
                                          $end=date("Y-m-d H:i:s", strtotime("+0 day", strtotime($end)));
                                          $selisih=date_diff($end,$start);
                                            // $jamban=$value->jam_masuk;
                                            // $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($value->jam_keluarjadwal)));
                                            // $selisih=$this->kurangwaktu($jamban2,$jamban);
                                        }
                                        else
                                        {
                                          $start = date_create($value->jam_masukjadwal);
                                          $end = date_create($value->jam_keluarjadwal);
                                          $end=date("Y-m-d H:i:s", strtotime("+0 day", strtotime($end)));
                                          $selisih=date_diff($end,$start);
                                            // $jamban=($value->jam_masukjadwal);
                                            // $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($value->jam_keluarjadwal)));
                                            // $selisih=$this->kurangwaktu($jamban2,$jamban);
                                        }

                                    }

                                      $selisih=$selisih->format("%H:%i:%s");
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
                                          // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
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
                                      // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                                      ->where('atts.tanggal_att','>=',$awal)
                                      ->where('atts.tanggal_att','<=',$akhir)
                                      ->where('jenisabsen_id', '=', '1')
                                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                      ->select('atts.tanggal_att')
                                      ->count();

                                  $ijinterlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                      // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                                      ->where('atts.tanggal_att','>=',$awal)
                                      ->where('atts.tanggal_att','<=',$akhir)
                                      ->where('jenisabsen_id', '=', '10')
                                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                      ->count();

                                  $absen = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                      // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                                      ->where('atts.tanggal_att','>=',$awal)
                                      ->where('atts.tanggal_att','<=',$akhir)
                                      ->where('jenisabsen_id', '=', '2')
                                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                      ->count();

                                  $ijin = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                      // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                                      ->where('atts.tanggal_att','>=',$awal)
                                      ->where('atts.tanggal_att','<=',$akhir)
                                      ->where('jenisabsen_id', '=', '3')
                                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                      ->count();

                                  $sakit = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                      // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                                      ->where('atts.tanggal_att','>=',$awal)
                                      ->where('atts.tanggal_att','<=',$akhir)
                                      ->where('jenisabsen_id', '=', '5')
                                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                      ->count();

                                  $cuti = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                      // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                                      ->where('atts.tanggal_att','>=',$awal)
                                      ->where('atts.tanggal_att','<=',$akhir)
                                      ->where('jenisabsen_id', '=', '4')
                                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                      ->count();

                                  $tugasluar = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                      // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                                      ->where('atts.tanggal_att','>=',$awal)
                                      ->where('atts.tanggal_att','<=',$akhir)
                                      ->where('jenisabsen_id', '=', '7')
                                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                      ->count();

                                  $tugasbelajar = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                      // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                                      ->where('atts.tanggal_att','>=',$awal)
                                      ->where('atts.tanggal_att','<=',$akhir)
                                      ->where('jenisabsen_id', '=', '6')
                                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                      ->count();

                                  $rapatundangan = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                      // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                                      ->where('atts.tanggal_att','>=',$awal)
                                      ->where('atts.tanggal_att','<=',$akhir)
                                      ->where('jenisabsen_id', '=', '8')
                                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                      ->count();

                                  $terlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                      ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                                      ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                                      // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                      ->where('atts.jam_masuk', '>','jadwalkerjas.jam_masukjadwal')
                                      ->where('atts.tanggal_att','>=',$awal)
                                      ->where('atts.tanggal_att','<=',$akhir)
                                      ->whereNotNull('atts.jam_masuk')
                                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                      ->count();

                                  $tidakterlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                      ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                                      ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                                      // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                      ->where('atts.jam_masuk', '<=', 'jadwalkerjas.jam_masukjadwal')

                                      ->where('atts.tanggal_att','>=',$awal)
                                      ->where('atts.tanggal_att','<=',$akhir)
                                      ->whereNotNull('atts.jam_masuk')
                                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                      ->count();

                                  $pulangcepat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                      ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                                      ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                                      // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
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
                                      // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                      ->where('atts.tanggal_att','>=',$awal)
                                      ->where('atts.tanggal_att','<=',$akhir)
                                      ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                      ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(atts.akumulasi_sehari))) as total'))
                                      ->first();

                                  // dd($totalakumulasi->total);

                                  $totalterlambat=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                  ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                                  ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                                  // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                  ->where('atts.tanggal_att','>=',$awal)
                                  ->where('atts.tanggal_att','<=',$akhir)
                                  ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                  ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(atts.terlambat))) as total'))
                                  ->first();

                                  // dd($totalterlambat->total);
                                  $totalakumulasi=$totalakumulasi->total;
                                  $totalterlambat=$totalterlambat->total;
                                  // dd($awal." = ".$akhir);

                                  $table=new rekapbulanan();
                                  $table->periode=$awal;
                                  $table->pegawai_id=$idpegawai->pegawai_id;
                                  $table->hari_kerja=$harikerja;
                                  $table->hadir=$hadir;
                                  $table->tanpa_kabar=$absen;
                                  $table->ijin=$ijin;
                                  $table->sakit=$sakit;
                                  $table->cuti=$cuti;
                                  $table->tugas_luar=$tugasluar;
                                  $table->tugas_belajar=$tugasbelajar;
                                  $table->terlambat=$terlambat;
                                  $table->rapatundangan=$rapatundangan;
                                  $table->pulang_cepat=$pulangcepat;
                                  $table->persentase_apel=$presentaseapel;
                                  $table->persentase_tidakhadir=$presentasetidakhadir;
                                  $table->total_akumulasi=$totalakumulasi;
                                  $table->total_terlambat=$totalterlambat;
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
                                  $table->tugas_luar=$tugasluar;
                                  $table->tugas_belajar=$tugasbelajar;
                                  $table->terlambat=$terlambat;
                                  $table->rapatundangan=$rapatundangan;
                                  $table->pulang_cepat=$pulangcepat;
                                  $table->persentase_apel=$presentaseapel;
                                  $table->persentase_tidakhadir=$presentasetidakhadir;
                                  $table->total_akumulasi=$totalakumulasi;
                                  $table->total_terlambat=$totalterlambat;
                                  $table->instansi_id=null;
                                  $table->save();
                            }
                      }
                    }
        }
        elseif ($date==4){
            $hari='Kamis';
        }
        elseif ($date==5){
            $hari='Jumat';
        }
        elseif ($date==6){
            $hari='Sabtu';
        }
        elseif ($date==7){
            $hari='Minggu';
        }


        $harikerjas=harikerja::where('hari','=',$hari)->get();
        $tanggalharini=date("Y-m-d");
        // dd($tanggalharini);
        foreach ($harikerjas as $key =>$jadwalkerja){

            		$hitung=rulejadwalpegawai::where('jadwalkerja_id','=',$jadwalkerja->jadwalkerja_id)
            		//->where('pegawai_id','=','9930')
                ->where('tanggal_awalrule','<=',$tanggalharini)
                ->where('tanggal_akhirrule','>=',$tanggalharini)
                ->count();
              // dd($hitung);
            if ($hitung>0)
            {
              // dd($hitung);
                $jadwalpegawais=rulejadwalpegawai::where('jadwalkerja_id','=',$jadwalkerja->jadwalkerja_id)
            		//->where('pegawai_id','=','9930')
                            ->where('tanggal_awalrule','<=',$tanggalharini)
                            ->where('tanggal_akhirrule','>=',$tanggalharini)
                            ->get();
                foreach ($jadwalpegawais as $jadwalpegawai)
                {
                    $user = new att();
                    $user->pegawai_id = $jadwalpegawai->pegawai_id;
                    $user->tanggal_att=$tanggalharini;
                    $user->terlambat='00:00:00';
                    $user->jadwalkerja_id=$jadwalkerja->jadwalkerja_id;
                    $user->akumulasi_sehari='00:00:00';
                    $user->jenisabsen_id = '2';
                    $user->save();
                    // dd("jalan");
                }
            }
            else
            {
                // dd("tidak jalan");
            }
        }

        $url="https://simpeg.kalselprov.go.id/api/identitas";
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);
        // Closing
        curl_close($ch);
        // dd($result);

        //Will dump a beauty json :3
        $jsons=(json_decode($result, true));

        $yg2=array();
          //
        // dd(($jsons));
       foreach ($jsons as $key=>$json)
       {

                   // set_time_limit(0);
                   // ini_set('memory_limit', '20000M');

        $pegawai=pegawai::where('nip','=',$json['nip'])
        ->count();

        if ($pegawai > 0){
            // dd($json['nip']);
            array_push($yg2,$json['nip']);
        }
        else{
           $user = new pegawai();
           $user->nip = $json['nip'];
           $user->nama = $json['nama'];
           $user->instansi_id = null;
           $user->save();
        }

      }
    }
}