<?php

namespace App\Console\Commands;
use App\raspberrystatu;
use App\instansi;
use Illuminate\Console\Command;

class CheckRaspberry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CheckRaspberry:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengecek Status Raspberry';

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
        $datas=raspberrystatu::all();
        foreach ($datas as $key => $data){
            if($data->jamstatus_raspberry=='00:00:00'){

            }
            else
            {
                // dd("as");
                $waktudata=date("H:i:s",strtotime($data->jamstatus_raspberry));
                $waktudata = date_create($waktudata);
                
                $waktusekarang=date("H:i:s");
                $waktusekarang=date_create($waktusekarang);
    
                $hasil=date_diff($waktusekarang,$waktudata);
                
                if ($hasil >= "00:15:00")
                {
                    $update=raspberrystatu::where('instansi_id','=',$data->instansi_id)->first();
                    $update->status="Offline";
                    $update->save();

                }
                else
                {

                }
            }


        }
        

    }
}
