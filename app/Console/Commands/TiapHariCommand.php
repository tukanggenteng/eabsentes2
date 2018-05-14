<?php

namespace App\Console\Commands;
use App\att;
use App\attendancecheck;
use App\rekapbulancheck;
use App\rekapminggucheck;
use App\harikerja;
use App\instansi;
use App\jadwalminggu;
use App\pegawai;
use Illuminate\Support\Facades\DB;
use App\finalrekapbulanan;
use App\rekapbulanan;
use App\masterbulanan;
use App\rulejadwalpegawai;
use App\jenisabsen;
use Illuminate\Console\Command;
use Carbon\Carbon;

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
        // dd("jalan");
        $tanggal=date('d');

        if ($tanggal==1){
          $sekarang=date("Y-m-d");
          $bulan=date("Y-m",strtotime("-1 month",strtotime($sekarang)));
            // dd($bulan);
          $pecah=explode("-",$bulan);
          $bulan=$pecah[1];
          $tahun=$pecah[0];

            $cekrekapbulanan=rekapbulancheck::whereMonth('tanggalcheckrekapbulan','=',$bulan)
                                            ->whereYear('tanggalcheckrekapbulan','=',$tahun)
                                            ->count();
            if ($cekrekapbulanan > 0)
            {

            }
            else
            {
                $idpegawais=att::leftJoin('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //   ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                    ->whereMonth('atts.tanggal_att','=',$bulan)
                    ->whereYear('atts.tanggal_att','=',$tahun)
                    // ->where('pegawais.id','=','830')
                    ->select('atts.pegawai_id','atts.jadwalkerja_id')
                    //   ->distinct('atts.pegawai_id','atts.jadwalkerja_id')
                    ->distinct()
                    ->get();
                foreach ($idpegawais as $key => $idpegawai) {

                            $harikerja = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->whereMonth('atts.tanggal_att','=',$bulan)
                                ->whereYear('atts.tanggal_att','=',$tahun)
                                ->where('atts.jenisabsen_id','!=','9')
                                ->where('atts.jenisabsen_id','!=','11')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->select('atts.tanggal_att')
                                ->count();


                            $perbaikanakumulasi=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->whereMonth('atts.tanggal_att','=',$bulan)
                                ->whereYear('atts.tanggal_att','=',$tahun)
                                ->where('atts.jenisabsen_id','=','1')
                                // ->where('atts.jenisabsen_id','=','10')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->where('atts.jam_keluar','=',null)
                                ->select('atts.*','jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal')
                                ->get();

                            foreach ($perbaikanakumulasi as $key => $value) {

                                // $start = date_create($value->jam_keluarjadwal);
                                // $end = date_create($value->jam_masukjadwal);
                                // $selisih=date_diff($start,$end);

                                // $selisih=$selisih->format("%H:%i:%s");
                                // $time_array = explode(':', $selisih);
                                // $hours = (int)$time_array[0];
                                // $minutes = (int)$time_array[1];
                                // $seconds = (int)$time_array[2];

                                // $total_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;

                                // $average = floor($total_seconds / 2);


                                // $init = 685;
                                // $hours2 = floor($average / 3600);
                                // $minutes2 = floor(($average / 60) % 60);
                                // $seconds2 = $average % 60;

                                // $average=$hours2.":".$minutes2.":".$seconds2;


                                $perbaikanakumulasi2=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                    ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                                    // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                    ->where('atts.tanggal_att','=',$value->tanggal_att)
                                    ->where('atts.jenisabsen_id','=','1')
                                    ->where('atts.id','=',$value->id)
                                    ->where('atts.jam_keluar','=',null)
                                    ->select('atts.*','jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal')
                                    ->first();

                                $perbaikanakumulasi2->jenisabsen_id='2';
                                $perbaikanakumulasi2->save();
                            }


                            $hadir = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->whereMonth('atts.tanggal_att','=',$bulan)
                                ->whereYear('atts.tanggal_att','=',$tahun)
                                ->where('jenisabsen_id', '=', '1')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->select('atts.tanggal_att')
                                ->count();

                            $ijinterlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->whereMonth('atts.tanggal_att','=',$bulan)
                                ->whereYear('atts.tanggal_att','=',$tahun)
                                ->where('jenisabsen_id', '=', '10')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $absen = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->whereMonth('atts.tanggal_att','=',$bulan)
                                ->whereYear('atts.tanggal_att','=',$tahun)
                                ->where('jenisabsen_id', '=', '2')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $ijin = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->whereMonth('atts.tanggal_att','=',$bulan)
                                ->whereYear('atts.tanggal_att','=',$tahun)
                                ->where('jenisabsen_id', '=', '3')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $sakit = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->whereMonth('atts.tanggal_att','=',$bulan)
                                ->whereYear('atts.tanggal_att','=',$tahun)
                                ->where('jenisabsen_id', '=', '5')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $cuti = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->whereMonth('atts.tanggal_att','=',$bulan)
                                ->whereYear('atts.tanggal_att','=',$tahun)
                                ->where('jenisabsen_id', '=', '4')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $tugasluar = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->whereMonth('atts.tanggal_att','=',$bulan)
                                ->whereYear('atts.tanggal_att','=',$tahun)
                                ->where('jenisabsen_id', '=', '7')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $tugasbelajar = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                ->whereMonth('atts.tanggal_att','=',$bulan)
                                ->whereYear('atts.tanggal_att','=',$tahun)
                                ->where('jenisabsen_id', '=', '6')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $rapatundangan = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                ->whereMonth('atts.tanggal_att','=',$bulan)
                                ->whereYear('atts.tanggal_att','=',$tahun)
                                ->where('jenisabsen_id', '=', '8')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $terlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                                ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                                ->where('atts.jam_masuk', '>','jadwalkerjas.jam_masukjadwal')
                                ->whereMonth('atts.tanggal_att','=',$bulan)
                                ->whereYear('atts.tanggal_att','=',$tahun)
                                ->whereNotNull('atts.jam_masuk')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            // $tanpaabsen=jenisabsen::where('id','!=',2)
                            //                 ->where('id','!=',9)
                            //                 ->where('id','!=',11)
                            //                 ->pluck('id')
                            //                 ->get();

                            $tidakterlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                                ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                                ->whereMonth('atts.tanggal_att','=',$bulan)
                                ->whereYear('atts.tanggal_att','=',$tahun)
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                // ->where('atts.jenisabsen_id','=',1)
                                // ->where('atts.jenisabsen_id','!=',2)
                                // ->where('atts.jenisabsen_id','!=',9)
                                // ->where('atts.jenisabsen_id','!=',11)
                                ->where('atts.apel','=','1')
                                // ->where('atts.jenisabsen_id',$tanpaabsen)
                                ->count();


                            // $tidakterlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                            // ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                            // ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                            // ->where('atts.jam_masuk', '<=', 'jadwalkerjas.jam_masukjadwal')
                            // ->whereMonth('atts.tanggal_att','=',$bulan)
                            // ->whereYear('atts.tanggal_att','=',$tahun)
                            // ->whereNotNull('atts.jam_masuk')
                            // ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                            // // ->where('atts.jenisabsen_id','=',1)
                            // ->where('atts.jenisabsen_id','!=',2)
                            // ->where('atts.jenisabsen_id','!=',9)
                            // ->where('atts.jenisabsen_id','!=',11)
                            // // ->where('atts.jenisabsen_id',$tanpaabsen)
                            // ->count();
                            
                            
                            // dd($tidakterlambat);

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
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $ijinpulangcepat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->whereMonth('atts.tanggal_att','=',$bulan)
                                ->whereYear('atts.tanggal_att','=',$tahun)
                                ->where('jenisabsen_id', '=', '12')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->select('atts.tanggal_att')
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
                            ->whereMonth('atts.tanggal_att','=',$bulan)
                            ->whereYear('atts.tanggal_att','=',$tahun)
                            ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                            ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                            ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(atts.akumulasi_sehari))) as total'))
                            ->first();

                            $totalterlambat=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                            ->whereMonth('atts.tanggal_att','=',$bulan)
                            ->whereYear('atts.tanggal_att','=',$tahun)
                            ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                            ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                            ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(atts.terlambat))) as total'))
                            ->first();

                            $pegawai=pegawai::where('id','=',$idpegawai->pegawai_id)
                                        ->first();
                            
                            if ($pegawai->instansi_id == null){
                                $instansi_id=null;
                            }
                            else
                            {
                                $instansi_id=$pegawai->instansi_id;
                            }

                            $table=new finalrekapbulanan();
                            $table->periode=date("Y-m-d",strtotime("-1 month",strtotime($sekarang)));
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
                            $table->persentase_apel=$presentaseapel;
                            $table->persentase_tidakhadir=$presentasetidakhadir;
                            $table->total_akumulasi=$totalakumulasi->total;
                            $table->total_terlambat=$totalterlambat->total;
                            $table->instansi_id=$instansi_id;
                            $table->ijinpulangcepat=$ijinpulangcepat;
                            $table->jadwalkerja_id=$idpegawai->jadwalkerja_id;
                            $table->apelbulanan=$tidakterlambat;
                            $table->save();

                            $table=new rekapbulanan();
                            $table->periode=date("Y-m-d",strtotime("-1 month",strtotime($sekarang)));
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
                            $table->persentase_apel=$presentaseapel;
                            $table->persentase_tidakhadir=$presentasetidakhadir;
                            $table->total_akumulasi=$totalakumulasi->total;
                            $table->total_terlambat=$totalterlambat->total;
                            $table->instansi_id=$instansi_id;
                            $table->ijinpulangcepat=$ijinpulangcepat;
                            $table->jadwalkerja_id=$idpegawai->jadwalkerja_id;
                            $table->apelbulanan=$tidakterlambat;
                            $table->save();
                }

                $rekapbulanchek=new rekapbulancheck;
                $rekapbulanchek->tanggalcheckrekapbulan=$sekarang;
                $rekapbulanchek->statuscheckrekapbulan="1";
                $rekapbulanchek->save();
            }    

        }

        $date=date('N');
        if ($date==1){
            $hari='Senin';
            
            $sekarang=date("Y-m-d");
            $bulan=date("Y-m",strtotime("-1 month",strtotime($sekarang)));
                // dd($bulan);
            $pecah=explode("-",$bulan);
            $bulan=$pecah[1];
            $tahun=$pecah[0];

            $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
            $akhir=date("Y-m-d",strtotime("-1 days",strtotime($sekarang)));

            $cekrekapbulanan=rekapminggucheck::where('tanggalcheckrekapminggu','=',$sekarang)
                                            ->count();
            if ($cekrekapbulanan > 0)
            {

            }
            else
            {
                $idpegawais=att::leftJoin('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //   ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                    // ->whereMonth('atts.tanggal_att','=',$bulan)
                    // ->whereYear('atts.tanggal_att','=',$tahun)
                    ->where('atts.tanggal_att','>=',$awal)
                    ->where('atts.tanggal_att','<=',$akhir)
                    // ->where('pegawais.id','=','830')
                    ->select('atts.pegawai_id','atts.jadwalkerja_id')
                    //   ->distinct('atts.pegawai_id','atts.jadwalkerja_id')
                    ->distinct()
                    ->get();
                foreach ($idpegawais as $key => $idpegawai) {

                            $harikerja = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->where('atts.tanggal_att','>=',$awal)
                                ->where('atts.tanggal_att','<=',$akhir)
                                ->where('atts.jenisabsen_id','!=','9')
                                ->where('atts.jenisabsen_id','!=','11')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->select('atts.tanggal_att')
                                ->count();


                            $perbaikanakumulasi=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->where('atts.tanggal_att','>=',$awal)
                                ->where('atts.tanggal_att','<=',$akhir)
                                ->where('atts.jenisabsen_id','=','1')
                                // ->where('atts.jenisabsen_id','=','10')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->where('atts.jam_keluar','=',null)
                                ->select('atts.*','jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal')
                                ->get();

                            foreach ($perbaikanakumulasi as $key => $value) {

                                // $start = date_create($value->jam_keluarjadwal);
                                // $end = date_create($value->jam_masukjadwal);
                                // $selisih=date_diff($start,$end);

                                // $selisih=$selisih->format("%H:%i:%s");
                                // $time_array = explode(':', $selisih);
                                // $hours = (int)$time_array[0];
                                // $minutes = (int)$time_array[1];
                                // $seconds = (int)$time_array[2];

                                // $total_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;

                                // $average = floor($total_seconds / 2);


                                // $init = 685;
                                // $hours2 = floor($average / 3600);
                                // $minutes2 = floor(($average / 60) % 60);
                                // $seconds2 = $average % 60;

                                // $average=$hours2.":".$minutes2.":".$seconds2;


                                $perbaikanakumulasi2=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                    ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                                    // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                    ->where('atts.tanggal_att','=',$value->tanggal_att)
                                    ->where('atts.jenisabsen_id','=','1')
                                    ->where('atts.id','=',$value->id)
                                    ->where('atts.jam_keluar','=',null)
                                    ->select('atts.*','jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal')
                                    ->first();

                                $perbaikanakumulasi2->jenisabsen_id='2';
                                $perbaikanakumulasi2->save();
                            }


                            $hadir = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->where('atts.tanggal_att','>=',$awal)
                                ->where('atts.tanggal_att','<=',$akhir)
                                ->where('jenisabsen_id', '=', '1')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->select('atts.tanggal_att')
                                ->count();

                            $ijinterlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->where('atts.tanggal_att','>=',$awal)
                                ->where('atts.tanggal_att','<=',$akhir)
                                ->where('jenisabsen_id', '=', '10')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $absen = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->where('atts.tanggal_att','>=',$awal)
                                ->where('atts.tanggal_att','<=',$akhir)
                                ->where('jenisabsen_id', '=', '2')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $ijin = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->where('atts.tanggal_att','>=',$awal)
                                ->where('atts.tanggal_att','<=',$akhir)
                                ->where('jenisabsen_id', '=', '3')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $sakit = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->where('atts.tanggal_att','>=',$awal)
                                ->where('atts.tanggal_att','<=',$akhir)
                                ->where('jenisabsen_id', '=', '5')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $cuti = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->where('atts.tanggal_att','>=',$awal)
                                ->where('atts.tanggal_att','<=',$akhir)
                                ->where('jenisabsen_id', '=', '4')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $tugasluar = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->where('atts.tanggal_att','>=',$awal)
                                ->where('atts.tanggal_att','<=',$akhir)
                                ->where('jenisabsen_id', '=', '7')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $tugasbelajar = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                ->where('atts.tanggal_att','>=',$awal)
                                ->where('atts.tanggal_att','<=',$akhir)
                                ->where('jenisabsen_id', '=', '6')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $rapatundangan = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                ->where('atts.tanggal_att','>=',$awal)
                                ->where('atts.tanggal_att','<=',$akhir)
                                ->where('jenisabsen_id', '=', '8')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $terlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                                ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                                ->where('atts.jam_masuk', '>','jadwalkerjas.jam_masukjadwal')
                                ->where('atts.tanggal_att','>=',$awal)
                                ->where('atts.tanggal_att','<=',$akhir)
                                ->whereNotNull('atts.jam_masuk')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            // $tanpaabsen=jenisabsen::where('id','!=',2)
                            //                 ->where('id','!=',9)
                            //                 ->where('id','!=',11)
                            //                 ->pluck('id')
                            //                 ->get();

                            $tidakterlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                                ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                                ->where('atts.tanggal_att','>=',$awal)
                                ->where('atts.tanggal_att','<=',$akhir)
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                // ->where('atts.jenisabsen_id','=',1)
                                // ->where('atts.jenisabsen_id','!=',2)
                                // ->where('atts.jenisabsen_id','!=',9)
                                // ->where('atts.jenisabsen_id','!=',11)
                                ->where('atts.apel','=','1')
                                // ->where('atts.jenisabsen_id',$tanpaabsen)
                                ->count();


                            // $tidakterlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                            // ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                            // ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                            // ->where('atts.jam_masuk', '<=', 'jadwalkerjas.jam_masukjadwal')
                            // ->whereMonth('atts.tanggal_att','=',$bulan)
                            // ->whereYear('atts.tanggal_att','=',$tahun)
                            // ->whereNotNull('atts.jam_masuk')
                            // ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                            // // ->where('atts.jenisabsen_id','=',1)
                            // ->where('atts.jenisabsen_id','!=',2)
                            // ->where('atts.jenisabsen_id','!=',9)
                            // ->where('atts.jenisabsen_id','!=',11)
                            // // ->where('atts.jenisabsen_id',$tanpaabsen)
                            // ->count();
                            
                            
                            // dd($tidakterlambat);

                            $pulangcepat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                                ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                                ->where('atts.jam_keluar', '<', 'jadwalkerjas.jam_keluarjadwal')
                                ->where('atts.jam_keluar', '=', null)
                                ->where('atts.tanggal_att','>=',$awal)
                                ->where('atts.tanggal_att','<=',$akhir)
                                ->where('atts.jenisabsen_id', '=', '1')
                                ->whereNotNull('atts.jam_masuk')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->count();

                            $ijinpulangcepat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                                ->where('atts.tanggal_att','>=',$awal)
                                ->where('atts.tanggal_att','<=',$akhir)
                                ->where('jenisabsen_id', '=', '12')
                                ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                                ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                                ->select('atts.tanggal_att')
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
                            ->where('atts.tanggal_att','>=',$awal)
                            ->where('atts.tanggal_att','<=',$akhir)
                            ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                            ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                            ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(atts.akumulasi_sehari))) as total'))
                            ->first();

                            $totalterlambat=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                            ->where('atts.tanggal_att','>=',$awal)
                            ->where('atts.tanggal_att','<=',$akhir)
                            ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                            ->where('atts.jadwalkerja_id','=',$idpegawai->jadwalkerja_id)
                            ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(atts.terlambat))) as total'))
                            ->first();

                            $pegawai=pegawai::where('id','=',$idpegawai->pegawai_id)
                                            ->first();

                            if ($pegawai->instansi_id == null){
                                $instansi_id=null;
                            }
                            else
                            {
                                $instansi_id=$pegawai->instansi_id;
                            }

                            $table=new masterbulanan();
                            $table->periode=$sekarang;
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
                            $table->persentase_apel=$presentaseapel;
                            $table->persentase_tidakhadir=$presentasetidakhadir;
                            $table->total_akumulasi=$totalakumulasi->total;
                            $table->total_terlambat=$totalterlambat->total;
                            $table->instansi_id=$instansi_id;
                            $table->ijinpulangcepat=$ijinpulangcepat;
                            $table->jadwalkerja_id=$idpegawai->jadwalkerja_id;
                            $table->apelbulanan=$tidakterlambat;
                            $table->save();

                            // $table=new rekapbulanan();
                            // $table->periode=date("Y-m-d",strtotime("-1 month",strtotime($sekarang)));
                            // $table->pegawai_id=$idpegawai->pegawai_id;
                            // $table->hari_kerja=$harikerja;
                            // $table->hadir=$hadir;
                            // $table->tanpa_kabar=$absen;
                            // $table->ijin=$ijin;
                            // $table->sakit=$sakit;
                            // $table->cuti=$cuti;
                            // $table->ijinterlambat=$ijinterlambat;
                            // $table->tugas_luar=$tugasluar;
                            // $table->tugas_belajar=$tugasbelajar;
                            // $table->terlambat=$terlambat;
                            // $table->rapatundangan=$rapatundangan;
                            // $table->pulang_cepat=$pulangcepat;
                            // $table->persentase_apel=$presentaseapel;
                            // $table->persentase_tidakhadir=$presentasetidakhadir;
                            // $table->total_akumulasi=$totalakumulasi->total;
                            // $table->total_terlambat=$totalterlambat->total;
                            // $table->instansi_id=$pegawai->instansi_id;
                            // $table->ijinpulangcepat=$ijinpulangcepat;
                            // $table->jadwalkerja_id=$idpegawai->jadwalkerja_id;
                            // $table->apelbulanan=$tidakterlambat;
                            // $table->save();
                }

                $rekapbulanchek=new rekapminggucheck;
                $rekapbulanchek->tanggalcheckrekapminggu=$sekarang;
                $rekapbulanchek->statuscheckrekapminggu="1";
                $rekapbulanchek->save();
            }    


        }
        elseif ($date==2){
            $hari='Selasa';
        }
        elseif ($date==3){
            $hari='Rabu';

                    // $date=date('N');

                    // $sekarang=date('Y-m-d');


                    // $awal=date("Y-m-d",strtotime("-9 days",strtotime($sekarang)));
                    // $akhir=date("Y-m-d",strtotime("-3 days",strtotime($sekarang)));

                    // $instansis=pegawai::where('instansi_id','!=',null)->get();
                    // // dd($instansis);
                    // foreach ($instansis as $key => $instansi) {
                    //   $cekrekap=rekapbulanan::leftJoin('pegawais','pegawais.id','=','rekapbulanans.pegawai_id')
                    //       ->where('rekapbulanans.pegawai_id','=',$instansi->id)
                    //       ->where('rekapbulanans.periode','=',$awal)
                    //       ->count();
                    //   // dd($cekrekap);

                    //   $cekatt = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //       ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                    //       ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                    //       ->where('atts.tanggal_att','>=',$awal)
                    //       ->where('atts.tanggal_att','<=',$akhir)
                    //       ->where('atts.pegawai_id','=',$instansi->id)
                    //       ->count();
                    //   // dd($cekatt);
                    //   if (($cekrekap > 0) || ($cekatt==0))
                    //   {

                    //   }else {
                    //     // $idpegawais=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //     //     ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                    //     //     ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                    //     //     ->where('atts.tanggal_att','>=',$awal)
                    //     //     ->where('atts.tanggal_att','<=',$akhir)
                    //     //     ->select('atts.pegawai_id')
                    //     //     ->distinct()
                    //     //     ->get();

                    //         $idpegawais=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //             ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                    //             ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                    //             // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                    //             ->where('atts.tanggal_att','>=',$awal)
                    //             ->where('atts.tanggal_att','<=',$akhir)
                    //             ->select('atts.pegawai_id')
                    //             ->distinct()
                    //             ->get();
                    //           // dd($idpegawais);
                    //         foreach ($idpegawais as $key => $idpegawai) {

                    //           $harikerja = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //               // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                    //               ->where('atts.tanggal_att','>=',$awal)
                    //               ->where('atts.tanggal_att','<=',$akhir)
                    //               ->where('atts.jenisabsen_id','!=','11')
                    //               ->where('atts.jenisabsen_id','!=','9')
                    //               ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    //               ->select('atts.tanggal_att')
                    //               ->count();
                    //               // dd($harikerja);
                    //               $perbaikanakumulasi=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //                   ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                    //                   // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                    //                   ->where('atts.tanggal_att','>=',$awal)
                    //                   ->where('atts.tanggal_att','<=',$akhir)
                    //                   ->where('atts.jenisabsen_id','=','1')
                    //                   ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    //                   ->where('atts.jam_keluar','=',null)
                    //                   ->select('atts.*','jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal')
                    //                   ->get();

                    //               foreach ($perbaikanakumulasi as $key => $value) {

                    //                 if ($value->jam_masukjadwal > $value->jam_keluarjadwal)
                    //                 {

                    //                     if ($value->jam_masuk > $value->jam_masukjadwal)
                    //                     {
                    //                       $start = date_create($value->jam_masuk);
                    //                       $end = date_create($value->jam_keluarjadwal);
                    //                       $end=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($end)));
                    //                       $selisih=date_diff($end,$start);

                    //                         // $jamban=$value->jam_masuk;
                    //                         // $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($value->jam_keluarjadwal)));
                    //                         // $selisih=$this->kurangwaktu($jamban,$jamban2);
                    //                     }
                    //                     else
                    //                     {
                    //                       $start = date_create($value->jam_masukjadwal);
                    //                       $end = date_create($value->jam_keluarjadwal);
                    //                       $end=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($end)));
                    //                       $selisih=date_diff($end,$start);
                    //                         // $jamban=($value->jam_masukjadwal);
                    //                         // $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($value->jam_keluarjadwal)));
                    //                         // $selisih=$this->kurangwaktu($jamban,$jamban2);
                    //                     }

                    //                 }
                    //                 else{
                    //                     if ($value->jam_masuk > $value->jam_masukjadwal)
                    //                     {
                    //                       $start = date_create($value->jam_masuk);
                    //                       $end = date_create($value->jam_keluarjadwal);
                    //                       //$end=date("Y-m-d H:i:s", strtotime("+0 day", strtotime($end)));
                    //                       $selisih=date_diff($end,$start);
                    //                         // $jamban=$value->jam_masuk;
                    //                         // $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($value->jam_keluarjadwal)));
                    //                         // $selisih=$this->kurangwaktu($jamban2,$jamban);
                    //                     }
                    //                     else
                    //                     {
                    //                       $start = date_create($value->jam_masukjadwal);
                    //                       $end = date_create($value->jam_keluarjadwal);
                    //                       //$end=date("Y-m-d H:i:s", strtotime("+0 day", strtotime($end)));
                    //                       $selisih=date_diff($end,$start);
                    //                         // $jamban=($value->jam_masukjadwal);
                    //                         // $jamban2=date("Y-m-d H:i:s", strtotime("+1 day", strtotime($value->jam_keluarjadwal)));
                    //                         // $selisih=$this->kurangwaktu($jamban2,$jamban);
                    //                     }

                    //                 }

                    //                   $selisih=$selisih->format("%H:%i:%s");
                    //                   $time_array = explode(':', $selisih);
                    //                   $hours = (int)$time_array[0];
                    //                   $minutes = (int)$time_array[1];
                    //                   $seconds = (int)$time_array[2];

                    //                   $total_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;

                    //                   $average = floor($total_seconds / 2);


                    //                   $init = 685;
                    //                   $hours2 = floor($average / 3600);
                    //                   $minutes2 = floor(($average / 60) % 60);
                    //                   $seconds2 = $average % 60;

                    //                   $average=$hours2.":".$minutes2.":".$seconds2;


                    //                   $perbaikanakumulasi2=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //                       ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                    //                       // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                    //                       ->where('atts.tanggal_att','=',$value->tanggal_att)
                    //                       ->where('atts.jenisabsen_id','=','1')
                    //                       ->where('atts.id','=',$value->id)
                    //                       ->where('atts.jam_keluar','=',null)
                    //                       ->select('atts.*','jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal')
                    //                       ->first();

                    //                   $perbaikanakumulasi2->akumulasi_sehari=$selisih;
                    //                   $perbaikanakumulasi2->save();
                    //               }


                    //               $hadir = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //                   // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                    //                   ->where('atts.tanggal_att','>=',$awal)
                    //                   ->where('atts.tanggal_att','<=',$akhir)
                    //                   ->where('jenisabsen_id', '=', '1')
                    //                   ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    //                   ->select('atts.tanggal_att')
                    //                   ->count();

                    //               $ijinterlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //                   // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                    //                   ->where('atts.tanggal_att','>=',$awal)
                    //                   ->where('atts.tanggal_att','<=',$akhir)
                    //                   ->where('jenisabsen_id', '=', '10')
                    //                   ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    //                   ->count();

                    //               $absen = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //                   // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                    //                   ->where('atts.tanggal_att','>=',$awal)
                    //                   ->where('atts.tanggal_att','<=',$akhir)
                    //                   ->where('jenisabsen_id', '=', '2')
                    //                   ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    //                   ->count();

                    //               $ijin = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //                   // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                    //                   ->where('atts.tanggal_att','>=',$awal)
                    //                   ->where('atts.tanggal_att','<=',$akhir)
                    //                   ->where('jenisabsen_id', '=', '3')
                    //                   ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    //                   ->count();

                    //               $sakit = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //                   // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                    //                   ->where('atts.tanggal_att','>=',$awal)
                    //                   ->where('atts.tanggal_att','<=',$akhir)
                    //                   ->where('jenisabsen_id', '=', '5')
                    //                   ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    //                   ->count();

                    //               $cuti = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //                   // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                    //                   ->where('atts.tanggal_att','>=',$awal)
                    //                   ->where('atts.tanggal_att','<=',$akhir)
                    //                   ->where('jenisabsen_id', '=', '4')
                    //                   ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    //                   ->count();

                    //               $tugasluar = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //                   // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                    //                   ->where('atts.tanggal_att','>=',$awal)
                    //                   ->where('atts.tanggal_att','<=',$akhir)
                    //                   ->where('jenisabsen_id', '=', '7')
                    //                   ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    //                   ->count();

                    //               $tugasbelajar = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //                   // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                    //                   ->where('atts.tanggal_att','>=',$awal)
                    //                   ->where('atts.tanggal_att','<=',$akhir)
                    //                   ->where('jenisabsen_id', '=', '6')
                    //                   ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    //                   ->count();

                    //               $rapatundangan = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //                   // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)

                    //                   ->where('atts.tanggal_att','>=',$awal)
                    //                   ->where('atts.tanggal_att','<=',$akhir)
                    //                   ->where('jenisabsen_id', '=', '8')
                    //                   ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    //                   ->count();

                    //               $terlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //                   ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                    //                   ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                    //                   // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                    //                   ->where('atts.jam_masuk', '>','jadwalkerjas.jam_masukjadwal')
                    //                   ->where('atts.tanggal_att','>=',$awal)
                    //                   ->where('atts.tanggal_att','<=',$akhir)
                    //                   ->whereNotNull('atts.jam_masuk')
                    //                   ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    //                   ->count();

                    //               $tidakterlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //                   ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                    //                   ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                    //                   // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                    //                   ->where('atts.jam_masuk', '<=', 'jadwalkerjas.jam_masukjadwal')

                    //                   ->where('atts.tanggal_att','>=',$awal)
                    //                   ->where('atts.tanggal_att','<=',$akhir)
                    //                   ->whereNotNull('atts.jam_masuk')
                    //                   ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    //                   ->count();

                    //               $pulangcepat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //                   ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                    //                   ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                    //                   // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                    //                   ->where('atts.jam_keluar', '<', 'jadwalkerjas.jam_keluarjadwal')
                    //                   ->where('atts.jam_keluar', '=', null)

                    //                   ->where('atts.tanggal_att','>=',$awal)
                    //                   ->where('atts.tanggal_att','<=',$akhir)
                    //                   ->where('atts.jenisabsen_id', '=', '1')
                    //                   ->whereNotNull('atts.jam_masuk')
                    //                   ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    //                   ->count();

                    //               if (($tidakterlambat==0) && ($harikerja==0) || ($tidakterlambat==0))
                    //               {
                    //                   $presentaseapel=0;
                    //               }
                    //               else {
                    //                   $presentaseapel=$tidakterlambat/$harikerja*100;
                    //               }

                    //               if (($absen==0) && ($harikerja==0) || ($absen==0))
                    //               {
                    //                   $presentasetidakhadir=0;
                    //               }
                    //               else {
                    //                           $presentasetidakhadir=$absen/$harikerja*100;
                    //               }


                    //                   $totalakumulasi = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //                   ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                    //                   ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                    //                   // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                    //                   ->where('atts.tanggal_att','>=',$awal)
                    //                   ->where('atts.tanggal_att','<=',$akhir)
                    //                   ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    //                   ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(atts.akumulasi_sehari))) as total'))
                    //                   ->first();

                    //               // dd($totalakumulasi->total);

                    //               $totalterlambat=att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    //               ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                    //               ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                    //               // ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                    //               ->where('atts.tanggal_att','>=',$awal)
                    //               ->where('atts.tanggal_att','<=',$akhir)
                    //               ->where('atts.pegawai_id','=',$idpegawai->pegawai_id)
                    //               ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(atts.terlambat))) as total'))
                    //               ->first();

                    //               // dd($totalterlambat->total);
                    //               $totalakumulasi=$totalakumulasi->total;
                    //               $totalterlambat=$totalterlambat->total;
                    //               // dd($awal." = ".$akhir);

                    //               $table=new rekapbulanan();
                    //               $table->periode=$awal;
                    //               $table->pegawai_id=$idpegawai->pegawai_id;
                    //               $table->hari_kerja=$harikerja;
                    //               $table->hadir=$hadir;
                    //               $table->tanpa_kabar=$absen;
                    //               $table->ijin=$ijin;
                    //               $table->ijinterlambat=$ijinterlambat;
                    //               $table->sakit=$sakit;
                    //               $table->cuti=$cuti;
                    //               $table->tugas_luar=$tugasluar;
                    //               $table->tugas_belajar=$tugasbelajar;
                    //               $table->terlambat=$terlambat;
                    //               $table->rapatundangan=$rapatundangan;
                    //               $table->pulang_cepat=$pulangcepat;
                    //               $table->persentase_apel=$terlambat+$ijinterlambat+$absen;
                    //               $table->persentase_tidakhadir=$absen;
                    //               $table->total_akumulasi=$totalakumulasi;
                    //               $table->total_terlambat=$totalterlambat;
                    //               $table->instansi_id=null;
                    //               $table->save();

                    //               $table=new masterbulanan();
                    //               $table->periode=$awal;
                    //               $table->pegawai_id=$idpegawai->pegawai_id;
                    //               $table->hari_kerja=$harikerja;
                    //               $table->hadir=$hadir;
                    //               $table->tanpa_kabar=$absen;
                    //               $table->ijin=$ijin;
                    //               $table->ijinterlambat=$ijinterlambat;
                    //               $table->sakit=$sakit;
                    //               $table->cuti=$cuti;
                    //               $table->tugas_luar=$tugasluar;
                    //               $table->tugas_belajar=$tugasbelajar;
                    //               $table->terlambat=$terlambat;
                    //               $table->rapatundangan=$rapatundangan;
                    //               $table->pulang_cepat=$pulangcepat;
                    //               $table->persentase_apel=$terlambat+$ijinterlambat+$absen;
                    //               $table->persentase_tidakhadir=$absen;
                    //               $table->total_akumulasi=$totalakumulasi;
                    //               $table->total_terlambat=$totalterlambat;
                    //               $table->instansi_id=null;
                    //               $table->save();
                    //         }
                    //   }
                    // }
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


        $month=Carbon::now()->month;
        $year = Carbon::now()->year;
        $date = Carbon::createFromDate($year,$month);
        $numberOfWeeks = floor($date->daysInMonth / Carbon::DAYS_PER_WEEK);
        $start = [];
        $end = [];
        $j=1;
        for ($i=1; $i <= $date->daysInMonth ; $i++) {
            Carbon::createFromDate($year,$month,$i); 
            $start['Week: '.$j.' Start Date']= (array)Carbon::createFromDate($year,$month,$i)->startOfWeek()->toDateString();
            $end['Minggu '.$j]= (array)Carbon::createFromDate($year,$month,$i)->endOfweek()->toDateString();
            $i+=7;
            $j++; 
        }
        // $result = array_merge($start,$end);
        // $result['numberOfWeeks'] = ["$numberOfWeeks"];
        $tanggalsekarang=date('Y-m-d');

        $checkatt=attendancecheck::where('tanggalcheckattendance','=',$tanggalsekarang)->count();

        if ($checkatt > 0){
            
        }
        else{

            // $tanggalsekarang="2018-01-15";
            if ($tanggalsekarang <= $end['Minggu 1'][0]){
                $minggu=1;
            }
            elseif ($tanggalsekarang <= $end['Minggu 2'][0]){
                $minggu=2;
            }
            elseif ($tanggalsekarang <= $end['Minggu 3'][0]){
                $minggu=3;
            }
            elseif ($tanggalsekarang <= $end['Minggu 4'][0]){
                $minggu=4;
            }
            elseif ($tanggalsekarang >= $end['Minggu 4'][0]){
                $minggu=1;
            }
            // dd($minggu);

            // return dd($end['Minggu 1'][0]);

            $minggujadwals=jadwalminggu::where('minggu','=',$minggu)->get();

            foreach ($minggujadwals as $key => $minggujadwal){
                // dd($minggujadwal->jadwalkerja_id);
                $harikerjas=harikerja::leftJoin('jadwalkerjas','harikerjas.jadwalkerja_id','=','jadwalkerjas.id')
                            ->where('harikerjas.hari','=',$hari)
                            ->where('harikerjas.jadwalkerja_id','=',$minggujadwal->jadwalkerja_id)
                            ->distinct()
                            ->get(['jadwalkerja_id','hari']);

                $tanggalharini=date("Y-m-d");

                $instansis=instansi::all();
                foreach ($instansis as $kunci => $instansi){

                    foreach ($harikerjas as $key =>$jadwalkerja){
                            #relasi kan jadwalkerja harikerja dan rulejadwalpegawai
                            // $hitung=rulejadwalpegawai::where('jadwalkerja_id','=',$jadwalkerja->jadwalkerja_id)
                            //     //->where('pegawai_id','=','9930')
                            // ->where('tanggal_awalrule','<=',$tanggalharini)
                            // ->where('tanggal_akhirrule','>=',$tanggalharini)
                            // ->count();

                            $hitung=rulejadwalpegawai::leftJoin('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                            ->leftJoin('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                            ->leftJoin('harikerjas','harikerjas.jadwalkerja_id','=','jadwalkerjas.id')
                            ->where('harikerjas.instansi_id','=',$instansi->id)
                            ->where('harikerjas.hari','=',$hari)
                            ->where('pegawais.instansi_id','=',$instansi->id)
                            ->where('rulejadwalpegawais.jadwalkerja_id','=',$jadwalkerja->jadwalkerja_id)
                            //->where('pegawai_id','=','9930')
                            ->where('rulejadwalpegawais.tanggal_awalrule','<=',$tanggalharini)
                            ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalharini)
                            ->count();


                        // dd($hitung);
                        if ($hitung>0)
                        {
                        // dd($hitung);
                            $jadwalpegawais=rulejadwalpegawai::leftJoin('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                                        ->where('pegawais.instansi_id','=',$instansi->id)
                                        ->where('rulejadwalpegawais.jadwalkerja_id','=',$jadwalkerja->jadwalkerja_id)
                                        ->where('rulejadwalpegawais.tanggal_awalrule','<=',$tanggalharini)
                                        ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalharini)
                                        ->get();

                            // dd($jadwalpegawais);
                            foreach ($jadwalpegawais as $jadwalpegawai)
                            {
                                $cek=att::where('tanggal_att','=',$tanggalharini)
                                    ->where('pegawai_id','=',$jadwalpegawai->pegawai_id)
                                    ->where('jadwalkerja_id','=',$jadwalkerja->jadwalkerja_id)
                                    ->where('jenisabsen_id','=','2')
                                    ->count();
                                    // dd($cek);
                                if ($cek > 0){

                                }
                                else{
                                    $user = new att();
                                    $user->pegawai_id = $jadwalpegawai->pegawai_id;
                                    $user->tanggal_att=$tanggalharini;
                                    $user->terlambat='00:00:00';
                                    $user->apel='0';
                                    $user->jadwalkerja_id=$jadwalkerja->jadwalkerja_id;
                                    if ($jadwalkerja->sifat=="FD"){
                                        $user->jenisabsen_id = '13';
                                    }
                                    else{
                                        $user->jenisabsen_id = '2';
                                    }
                                    $user->akumulasi_sehari='00:00:00';
                                    $user->save();
                                    // dd($jadwalpegawai->pegawai_id);
                                }

                            }
                        }
                        else
                        {
                            // dd("tidak jalan");
                        }
                    }
                }
            }

            $addcheck=new attendancecheck;
            $addcheck->tanggalcheckattendance=$tanggalsekarang;
            $addcheck->statuscheckattendance="1";
            $addcheck->save();
        
        }

    }
}