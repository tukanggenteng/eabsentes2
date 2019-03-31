<?php

namespace App\Jobs;
use App\att;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
        //
        echo $this->details['pegawai_id'];
        
        $user = new att();
        $user->pegawai_id = $this->details['pegawai_id'];
        $user->tanggal_att=$this->details['tanggal_att'];
        $user->terlambat=$this->details['terlambat'];
        $user->apel=$this->details['apel'];
        $user->jadwalkerja_id=$this->details['jadwalkerja_id'];
        $user->jenisabsen_id = $this->details['jenisabsen_id'];
        $user->akumulasi_sehari=$this->details['akumulasi_sehari'];
        $user->save();
    }
}
