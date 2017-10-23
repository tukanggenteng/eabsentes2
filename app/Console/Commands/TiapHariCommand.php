<?php

namespace App\Console\Commands;
use App\att;
use App\harikerja;
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
        $date=date('N');
        if ($date=1){
            $hari='Senin';
        }
        elseif ($date=2){
            $hari='Selasa';
        }
        elseif ($date=3){
            $hari='Rabu';
        }
        elseif ($date=4){
            $hari='Kamis';
        }
        elseif ($date=5){
            $hari='Jumat';
        }
        elseif ($date=6){
            $hari='Sabtu';
        }
        elseif ($date=7){
            $hari='Minggu';
        }


        $harikerjas=harikerja::where('hari','=',$hari)->get();
        $tanggalharini=date("Y-m-d");
        foreach ($harikerjas as $key =>$jadwalkerja){
            $hitung=rulejadwalpegawai::where('jadwakerja_id','=',$jadwalkerja->jadwalkerja_id)
                ->where('tanggal_awalrule','>=',$tanggalharini)
                ->where('tanggal_akhirtule','<=',$tanggalharini)
                ->count();

            if ($hitung>0)
            {
                $jadwalpegawais=rulejadwalpegawai::where('jadwakerja_id','=',$jadwalkerja->jadwalkerja_id)
                    ->get();
                foreach ($jadwalpegawais as $jadwalpegawai)
                {
                    $user = new att();
                    $user->pegawai_id = $jadwalpegawai->pegawai_id;
                    $user->tanggal_att=$tanggalharini;
                    $user->jadwalkerja_id=$jadwalkerja->jadwalkerja_id;
                    $user->jenis_absen = '2';
                    $user->save();
                }
            }
            else
            {

            }
        }
    }
}
