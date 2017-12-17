<?php

namespace App\Http\Controllers;
use App\raspberrystatu;
use App\instansi;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;

class RaspberryStatusController extends Controller
{
    //
    public function index(){
        return view('raspberry.raspberry');
    }

    //api
    public function data(){
        $tables=raspberrystatu::leftJoin('instansis','raspberrystatus.instansi_id','=','instansis.id')
        ->get();
        return Datatables::of($tables)
            ->make(true);
    }

    public function update(Request $request){
        $instansi=$request->json('instansi_id');
        $tanggal=$request->json('tanggal');
        $jam=$request->json('jam');
        $token=$request->json('token');

        $hasiltoken=$this->encryptOTPRaspi($jam.$tanggal.$instansi);

        if ($token==$hasiltoken){
            $updatestatus=raspberrystatu::where('instansi_id','=',$instansi)->first();
            $updatestatus->status='Online';
            $updatestatus->jamstatus_raspberry=$jam;
            $updatestatus->tanggal_raspberry=$tanggal;
            $updatestatus->save();
            return "Success";
        }
        else
        {
            return "Fail";
        }


    }
}
