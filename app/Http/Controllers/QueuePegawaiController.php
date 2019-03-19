<?php

namespace App\Http\Controllers;

use App\fingerpegawai;
use App\pegawai;
use App\queue_pegawai;
use App\macaddresse;

use App\instansi;
use Illuminate\Http\Request;

class QueuePegawaiController extends Controller
{
    // add column dengan ip nya
    public function index(Request $request)
    {
      
    }
    

    public function get(Request $request)
    {

        $instansi_id=$request->json('instansi');
        $macaddress=$request->json('macaddress');
        $fingerprint_ip=$request->json('fingerprint_ip');
        $queue_id=$request->json('id');
        
        $datainstansi=instansi::where('id','=',$instansi_id)
                      ->first();
        $datamacaddress=macaddresse::where('macaddress','=',$macaddress)
                      ->first();

        if ($datamacaddress->instansi_id==null)
        {
            $datamacaddress->instansi_id=$instansi_id;
            $datamacaddress->save();
        }

                     

        $dataqueuepegawai= queue_pegawai::where('instansi_id','=',$instansi_id)
                        ->where('macaddress_id','=',$datamacaddress->id)
                        ->where('fingerprint_ip','=',$fingerprint_ip);


        if ($dataqueuepegawai->count()==0)
        {
            $datapegawais=pegawai::where('instansi_id','=',$instansi_id)->get();

            foreach ($datapegawais as $key => $datapegawai)
            {

                $addqueuepegawai= new queue_pegawai();
                $addqueuepegawai->pegawai_id=$datapegawai->id;
                $addqueuepegawai->macaddress_id=$datamacaddress->id;
                $addqueuepegawai->instansi_id=$instansi_id;
                $addqueuepegawai->fingerprint_ip=$fingerprint_ip;
                $addqueuepegawai->command="daftar";
                $addqueuepegawai->status=false;
                $addqueuepegawai->save();
            }
        }
        
        return $dataqueuepegawai->where('status','=',false)->get();

    }

    public function post(Request $request)
    {

        $instansi_id=$request->json('instansi');
        $macaddress=$request->json('macaddress');
        $fingerprint_ip=$request->json('fingerprint_ip');
        $queue_id=$request->json('id'); 
        $token=$request->json('token');

        $hasilbasic=$macaddress.$instansi_id.$pegawai_id.$fingerprint_ip.$queue_id;
        $hasil=$this->encryptOTP($hasilbasic); 


        $dataqueuepegawai= queue_pegawai::where('instansi_id','=',$instansi_id)
        ->where('id','=',$queue_id)
        ->first();

        $dataqueuepegawai->status=true;

        if ($dataqueuepegawai->save())
        {
        return "Success";
        }
        else
        {
        return "Failed";
        }

    }

    public function index_for_user(Request $request)
    {
      
    }
}
