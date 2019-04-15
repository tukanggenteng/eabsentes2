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
class ChangeAttPegawaiLiburNasional implements ShouldQueue
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
        //


        $tanggalproses=$this->details['tanggal_att'];
        $pegawai_id=$this->details['pegawai_id'];
        $jadwalkerja_id=$this->details['jadwalkerja_id'];
        $command=$this->details['command'];

        
        if ($command=="change")
        {
            $changeatt=att::where('tanggal_att','=',$tanggalproses)
            ->where('pegawai_id','=',$pegawai_id)
            ->where('jadwalkerja_id','=',$jadwalkerja_id)
            ->first();

            $changeatt->jenisabsen_id = 9;
            $changeatt->jam_masuk = null;
            $changeatt->keluaristirahat=null;
            $changeatt->masukistirahat=null;
            $changeatt->keteranganmasuk_id=null;
            $changeatt->keterangankeluar_id=null;
            $changeatt->apel=0;                                  
            $changeatt->masukinstansi_id=null;
            $changeatt->jam_keluar = null;
            $changeatt->terlambat = "00:00:00";
            $changeatt->keluarinstansi_id=null;
            $changeatt->akumulasi_sehari = "00:00:00";
            $changeatt->save();    
        }
        else
        {
            $changeatt=att::where('tanggal_att','=',$tanggalproses)
                        ->where('pegawai_id','=',$pegawai_id)
                        ->where('jadwalkerja_id','=',$jadwalkerja_id)
                        ->first();
            
            $changeatt->jenisabsen_id = 2;
            $changeatt->jam_masuk = null;
            $changeatt->keluaristirahat=null;
            $changeatt->masukistirahat=null;
            $changeatt->keteranganmasuk_id=null;
            $changeatt->keterangankeluar_id=null;
            $changeatt->apel=0;                                  
            $changeatt->masukinstansi_id=null;
            $changeatt->jam_keluar = null;
            $changeatt->terlambat = "00:00:00";
            $changeatt->keluarinstansi_id=null;
            $changeatt->akumulasi_sehari = "00:00:00";
            $changeatt->save();
        }
                  

    }
}
