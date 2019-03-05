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

        $instansi_id=$request->json('instansi');
        $macaddress=$request->json('macaddress');

        
        $datainstansi=instansi::where('id','=',$instansi_id)
                      ->first();
        $datamacaddress=macaddresse::where('macaddress','=',$macaddress)
                      ->first();

        $dataqueuepegawai= queue_pegawai::where('instansi_id','=',$instansi_id)
        ->where('macaddress_id','=',$datamacaddress->id)
        ->where('status','=',false)
        ->get();
        
        return $dataqueuepegawai;

    }

    public function post(Request $request)
    {

        $instansi_id=$request->json('instansi');
        $macaddress=$request->json('macaddress');
        $token=$request->json('token');

        $hasilbasic=$macaddress.$instansi_id.$pegawai_id;
        $hasil=$this->encryptOTP($hasilbasic); 

       
        $datainstansi=instansi::where('id','=',$instansi_id)
                      ->first();
        $datamacaddress=macaddresse::where('macaddress','=',$macaddress)
                      ->first();

        $dataqueuepegawai= queue_pegawai::where('instansi_id','=',$instansi_id)
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

    public function index_for_user(Request $request)
    {
      
    }
}
