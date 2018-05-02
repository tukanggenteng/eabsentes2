<?php

namespace App\Http\Controllers;
use App\historyfingerpegawai;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

class HistoryFingerPegawaiController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('throttle:4000000,1');
    }

    public function index(){
        // dd("jalan");
        return view('raspberry.historyfingerpegawai');
    }

    public function data(){
        $tables=historyfingerpegawai::leftJoin('instansis','historyfingerpegawais.instansi_id','=','instansis.id')
        ->leftJoin('pegawais','historyfingerpegawais.pegawai_id','=','pegawais.id')
        ->select(['historyfingerpegawais.*','instansis.namaInstansi','pegawais.nama','pegawais.nip'])
        ->get();
        return Datatables::of($tables)
            ->make(true);
    } 
 
    public function getdata($ip,$instansi_id){
        // dd("sd");
        $tableedit=historyfingerpegawai::leftJoin('pegawais','historyfingerpegawais.pegawai_id','=','pegawais.id')
                    ->where('historyfingerpegawais.iphapus','=',$ip)
                    ->where('historyfingerpegawais.instansi_id','=',$instansi_id)
                    ->where('historyfingerpegawais.statushapus','=','0')
                    ->select('historyfingerpegawais.*','pegawais.nama')
                    ->get();
        // $tableedit->statushapus="1";
        // $tableedit->save();

        return $tableedit;
    }

    public function edit(Request $request){
        $pegawai_id=$request->json('pegawai_id');
        $ip=$request->json('ip');
        $instansi_id=$request->json('instansi_id');
        $status=$request->json('status');
        $token=$request->json('token');

        $auth=$this->encryptOTP($pegawai_id.$status.$ip.$instansi_id);

        if ($auth==$token){
            $tableedit=historyfingerpegawai::where('iphapus','=',$ip)
                    ->where('pegawai_id','=',$pegawai_id)
                    ->where('instansi_id','=',$instansi_id)
                    ->first();
            $tableedit->statushapus="1";
            $tableedit->save();

            return "Success";
        }
        else
        {
            return "Invalid Token";
        }

    }
}
