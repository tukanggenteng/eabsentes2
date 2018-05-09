<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\historyfingerpegawai;
use App\lograspberry;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;

class LogRaspberryController extends Controller
{
    //
    public function postlog(Request $request)
    {
       $ip=$request->json('ip');
       $versi=$request->json('versi');
       $jumlahmac=$request->json('jumlahmac');
       $jumlahpegawaifinger=$request->json('jumlahpegawaifinger');
       $jumlahadminfinger=$request->json('jumlahadminfinger');
       $jumlahabsensifinger=$request->json('jumlahabsensifinger');
       $jumlahpegawailocal=$request->json('jumlahpegawailocal');
       $jumlahadminlocal=$request->json('jumlahadminlocal');
       $jumlahabsensilocal=$request->json('jumlahabsensilocal');
       $token=$request->json('token');
       $instansi=$request->json('instansi_id');
       $hasilbasic=$ip.$versi.$jumlahmac.$jumlahpegawaifinger.$jumlahadminfinger.
                    $jumlahabsensifinger.$jumlahpegawailocal.$jumlahadminlocal.
                    $jumlahabsensilocal.$instansi;
       $hasil=$this->encryptOTP($hasilbasic);
       $statusauth=false;

       if ($token==$hasil){
            $cari=lograspberry::where('alamatip','=',$ip)
            ->where('instansi_id','=',$instansi)
            ->count();
            // dd($cari);
            if ($cari==0){
                
                $table= new lograspberry();
                $table->alamatip=$ip;
                $table->instansi_id=$instansi;
                $table->jumlahmac=$jumlahmac;
                $table->jumlahpegawaifinger=$jumlahpegawaifinger;
                $table->jumlahadminfinger=$jumlahadminfinger;
                $table->jumlahabsensifinger=$jumlahabsensifinger;
                $table->jumlahpegawailocal=$jumlahpegawailocal;
                $table->jumlahadminlocal=$jumlahadminlocal;
                $table->jumlahabsensilocal=$jumlahabsensilocal;
                $table->versi=$versi;
                $table->save();

                return "Success";
            }
            else
            {

                $table=lograspberry::where('alamatip','=',$ip)
                ->where('instansi_id','=',$instansi)
                ->first();

                $table->jumlahmac=$jumlahmac;
                $table->jumlahpegawaifinger=$jumlahpegawaifinger;
                $table->jumlahadminfinger=$jumlahadminfinger;
                $table->jumlahabsensifinger=$jumlahabsensifinger;
                $table->jumlahpegawailocal=$jumlahpegawailocal;
                $table->jumlahadminlocal=$jumlahadminlocal;
                $table->jumlahabsensilocal=$jumlahabsensilocal;
                $table->versi=$versi;
                $table->save();

                return "Success";
            }
       }
       else{
           return "Failed";
       }
    }

    public function index(){
        return view('raspberry.raspberry');
    }

    public function data(){
        $tables=lograspberry::leftJoin('instansis','lograspberrys.instansi_id','=','instansis.id')
        ->select(['lograspberrys.*','instansis.namaInstansi'])
        ->get();
        return Datatables::of($tables)
            ->make(true);
    }

    public function datainstansi(){
        $tables=lograspberry::leftJoin('instansis','lograspberrys.instansi_id','=','instansis.id')
        ->where('lograspberrys.instansi_id','=',Auth::user()->instansi_id)
        ->select(['lograspberrys.*','instansis.namaInstansi'])
        ->get();
        return Datatables::of($tables)
            ->make(true);
    }

    public function indexinstansi(){
        return view('raspberry.datapegawairaspberry');
    }

    public function datasidikjariinstansi(){
        $tables=historyfingerpegawai::leftJoin('instansis','historyfingerpegawais.instansi_id','=','instansis.id')
        ->leftJoin('pegawais','historyfingerpegawais.pegawai_id','=','pegawais.id')
        ->where('historyfingerpegawais.instansi_id','=',Auth::user()->instansi_id)
        ->where('historyfingerpegawais.statushapus','=','0')
        ->select(['historyfingerpegawais.*','instansis.namaInstansi','pegawais.nip','pegawais.nama'])
        ->get();
        return Datatables::of($tables)
            ->editColumn('statushapus',function($table){
                if ($table->statushapus==1){
                    $status='<span class="badge bg-green">Diperbarui</span>';
                }
                else
                {
                    $status='<span class="badge bg-red">Belum diperbarui</span>';
                }
                return $status;
             })
            ->editColumn('iphapus',function($table){
                if ($table->iphapus=="10.10.10.10"){
                    $status='Finger A';
                }
                elseif ($table->iphapus=="10.10.10.20")
                {
                    $status='Finger B';
                }elseif ($table->iphapus=="10.10.10.30")
                {
                    $status='Finger C';
                }elseif ($table->iphapus=="10.10.10.40")
                {
                    $status='Finger D';
                }elseif ($table->iphapus=="10.10.10.50")
                {
                    $status='Finger E';
                }
                return $status;
            })
            ->rawColumns(['statushapus'])
            ->make(true);
    }

    public function indexfinger(){
        return view('raspberry.fingerpegawairaspberry');
    }
}
