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
    
    public function index(Request $request)
    {
      
    }
    

    public function get(Request $request)
    {
        $pegawai_id= $request->json('pegawai');
        $instansi_id=$request->json('instansi');
        $macaddress=$request->json('macaddress');

        $datapegawai=pegawai::where('nip','=',$pegawai_id)
                      ->first();
        $datainstansi=instansi::where('id','=',$instansi_id)
                      ->first();
        $datamacaddress=macaddresse::where('macaddress','=',$macaddress)
                      ->first();

        if (($datapegawai!=null) && ($datainstansi!=null) && ($datamacaddress!=null))
        {
          $dataqueuepegawai= queue_pegawai::where('pegawai_id','=',$pegawai_id)
                                          ->where('instansi_id','=',$instansi_id)
                                          ->where('macaddress_id','=',$datamacaddress->id)
                                          ->where('status','=',false)
                                          ->get();
          return $dataqueuepegawai;
                                          
        }
        else
        {
          return "Null";
        }


    }

    public function post(Request $request)
    {
        $pegawai_id= $request->json('pegawai');
        $instansi_id=$request->json('instansi');
        $macaddress=$request->json('macaddress');
        $token=$request->json('token');

        $hasilbasic=$macaddress.$instansi_id.$pegawai_id;
        $hasil=$this->encryptOTP($hasilbasic); 

        $datapegawai=pegawai::where('nip','=',$pegawai_id)
                      ->first();
        $datainstansi=instansi::where('id','=',$instansi_id)
                      ->first();
        $datamacaddress=macaddresse::where('macaddress','=',$macaddress)
                      ->first();

        if (($datapegawai!=null) && ($datainstansi!=null) && ($datamacaddress!=null) && ($token==$hasil))
        {
          $dataqueuepegawai= queue_pegawai::where('pegawai_id','=',$pegawai_id)
                                          ->where('instansi_id','=',$instansi_id)
                                          ->where('macaddress_id','=',$datamacaddress->id)
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
        else
        {
          return "Failed";
        }

    }

    public function index_for_user(Request $request)
    {
      
    }
}
