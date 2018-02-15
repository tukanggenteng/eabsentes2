<?php

namespace App\Http\Controllers;
use App\historycrashraspberry;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

class HistoryCrashRaspberryController extends Controller
{
    //
    public function index(){
        return view('raspberry.historycrashraspberry');
    }

    public function data(){
        $tables=historycrashraspberry::leftJoin('instansis','historycrashraspberrys.instansi_id','=','instansis.id')
        ->select(['historycrashraspberrys.*','instansis.namaInstansi'])
        ->get();
        return Datatables::of($tables)
            ->make(true);
    }

    public function post(Request $request){
        $instansi=$request->json('instansi_id');
        $keterangan=$request->json('keterangan');
        $token=$request->json('token');

        $auth=$this->encryptOTP($ip.$instansi.$keterangan);

        if ($auth==$token){
            $cek=historycrashraspberry::where('instansi_id','=',$instansi)
                ->where('ip','=',$ip)
                ->count();

            $table=new historycrashraspberry;
            $table->instansi_id=$instansi;
            $table->keterangan=$keterangan;
            $table->save();

            return "Success";

            // if ($cek > 0){
            //     $table=new historycrashraspberry;
            //     $table->ip=$ip;
            //     $table->instansi_id=$instansi;
            //     $table->keterangan=$keterangan;
            //     $table->save();

            //     return "Success";
            // }
            // else{

            //     $table=historycrashraspberry::where('ip','=',$ip)
            //             ->where('instansi_id','=',$instansi)
            //             ->first();
            //     $table->keterangan=$keterangan;
            //     $table->save();


            //     return "Success";
            // }
        }
        else
        {
            return "Invalid Token";

        }

    }
}
