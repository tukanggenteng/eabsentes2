<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\lograspberry;
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

            if ($cari>1){
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
            else
            {
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
       }
       else{
           return "token invalid";
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
}
