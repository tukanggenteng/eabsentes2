<?php

namespace App\Http\Controllers;

use App\fingerpegawai;
use App\pegawai;
use App\queue_pegawai;
use App\macaddresse;
use App\lograspberry;
use App\instansi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class QueuePegawaiController extends Controller
{

    public function index(Request $request)
    {
        $instansis=instansi::where('namaInstansi','!=','Admin')
                    ->get();
        
        if (Auth::user()->role_id==2)
        {
            $instansi_id=$request->instansi_id;
        }             
        else
        {
            $instansi_id=Auth::user()->instansi_id;
        }

        $dataqueuepegawais=queue_pegawai::leftJoin('pegawais','pegawais.id','queue_pegawais.pegawai_id')
                            ->leftJoin('instansis','instansis.id','=','queue_pegawais.instansi_id')
                            ->leftJoin('macaddresses','queue_pegawais.macaddress_id','=','macaddresses.id');

        if (isset($request->nip))
        {
            $dataqueuepegawais=$dataqueuepegawais->where('pegawais.nip','like','%'.$request->nip.'%');
        }

        if (isset($instansi_id))
        {
            $dataqueuepegawais=$dataqueuepegawais->where('queue_pegawais.instansi_id','=',$instansi_id);
        }

        if (isset($request->fingerprint_ip))
        {
            $dataqueuepegawais=$dataqueuepegawais->where('queue_pegawais.fingerprint_ip','like','%'.$request->fingerprint_ip.'%');
        }
                            
        $dataqueuepegawais=$dataqueuepegawais->paginate(30);

        return view('queue.pegawai',['dataqueuepegawais'=>$dataqueuepegawais,
                                    'instansis'=>$instansis,
                                    'request_instansi'=>$instansi_id,
                                    'request_nip'=>$request->nip,
                                    'request_fingerprint_ip'=>$request->fingerprint_ip]);
    }
    

    public function get(Request $request)
    {

        $instansi_id=$request->json('instansi');
        $macaddress=$request->json('macaddress');
        $fingerprint_ip=$request->json('fingerprint_ip');
        $command="daftar";
        
        // dd($request);

        $dataqueuepegawai=$this->storequeuepegawai($instansi_id,$macaddress,$fingerprint_ip,$command);


        return $dataqueuepegawai;

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

    
    public function storequeuepegawai($instansi_id,$macaddress,$fingerprint_ip,$command)
    {
        $datainstansi=instansi::where('id','=',$instansi_id)
                      ->first();
        $datamacaddress=macaddresse::where('macaddress','=',$macaddress)
                      ->first();

        if ($datamacaddress==null)
        {
            return $datamacaddress;
        }

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
                $lograspberries=lograspberry::where('instansi_id','=',$instansi_id)
                    ->groupBy(DB::raw('alamatip'))
                    ->get();
                
                    foreach ($lograspberries as $key => $lograspberry)
                    {
                        $addqueuepegawai= new queue_pegawai();
                        $addqueuepegawai->pegawai_id=$datapegawai->id;
                        $addqueuepegawai->macaddress_id=$datamacaddress->id;
                        $addqueuepegawai->instansi_id=$instansi_id;
                        $addqueuepegawai->fingerprint_ip=$lograspberry->alamatip;
                        $addqueuepegawai->command=$command;
                        $addqueuepegawai->status=false;
                        $addqueuepegawai->save(); 
                    }
                        
            }
        }
        
        return $dataqueuepegawai->where('status','=',false)->get();
    }
    

    public function storequeuepegawaispesific($instansi_id,$pegawai_id,$command)
    {
 

        $dataqueuepegawais= queue_pegawai::where('instansi_id','=',$instansi_id)
                ->where('pegawai_id','=',$pegawai_id)->groupBy(DB::raw('fingerprint_ip'))->get();
        
        $cekdataqueuepegawais=$dataqueuepegawais;

        if ($cekdataqueuepegawais->first()==null)
        {
            return false;
        }



        if ($dataqueuepegawais->count()!=0)
        {
            
            // dd($dataqueuepegawais);
            

            foreach ($dataqueuepegawais as $key => $dataqueuepegawai)
            {
                $addqueuepegawai= new queue_pegawai();
                $addqueuepegawai->pegawai_id=$pegawai_id;
                $addqueuepegawai->macaddress_id=$dataqueuepegawai->macaddress_id;
                $addqueuepegawai->instansi_id=$instansi_id;
                $addqueuepegawai->fingerprint_ip=$dataqueuepegawai->fingerprint_ip;
                $addqueuepegawai->command=$command;
                $addqueuepegawai->status=false;
                $addqueuepegawai->save(); 
        
            
                    
            }
        }

        return true; 
    }
}
