<?php

namespace App\Jobs;
use App\att;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Role_Hari_Libur;
use App\Pegawai_Hari_Libur;
use App\attendancecheck;
use App\rekapbulancheck;
use App\rekapminggucheck;
use App\harikerja;
use App\instansi;
use App\jadwalminggu;
use App\pegawai;
use App\dokter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\finalrekapbulanan;
use App\rekapbulanan;
use App\masterbulanan;
use App\rulejadwalpegawai;
use App\jenisabsen;

class GenerateAttendance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        //
        $this->details=$details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $tanggalsekarang=date('Y-m-d');

        $tanggalproses=$this->details['tanggalproses'];
        $pegawai_id=$this->details['pegawai_id'];
        $jadwalkerja_id=$this->details['jadwalkerja_id'];
        $sifat=$this->details['sifat'];

        $user = new att();
        $user->pegawai_id = $pegawai_id;
        $user->tanggal_att=$tanggalproses;
        $user->terlambat='00:00:00';
        $user->apel='0';
        $user->jadwalkerja_id=$jadwalkerja_id;
        if ($sifat=="FD")
        {
            $user->jenisabsen_id = '13';

        }
        else
        {
            $user->jenisabsen_id = '2';

        }
        $user->akumulasi_sehari='00:00:00';
        $user->save();
    }
}
