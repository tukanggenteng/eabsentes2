<?php

namespace App\Console\Commands;

use App\att;
use App\harikerja;
use App\instansi;
use App\pegawai;
use App\finalrekapbulanan;
use App\rekapbulanan;
use App\masterbulanan;
use App\rulejadwalpegawai;
use Illuminate\Support\Facades\DB;

use Illuminate\Console\Command;

class UpdatePegawai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdatePegawai:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Pegawai';

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

        if ($pegawai > 0)
        {
            array_push($yg2,$json['nip']);
        }
        else
        {
           $user = new pegawai();
           $user->nip = $json['nip'];
           $user->nama = $json['nama'];
           $user->instansi_id = null;
           $user->save();
        }

      }
    }
}
