<?php

namespace App\Http\Controllers;

use App\jadwalkerja;
use App\rulejammasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalKerjaController extends Controller
{
    //
    public function index(){
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }

        $jadwalkerja=array();
        $isi=array();
        $tables=jadwalkerja::where('instansi_id','=',Auth::user()->instansi_id)->get();
        // dd($tables);
        // foreach ($tables as $table){
        //     $harikerja=rulejammasuk::where('jadwalkerja_id','=',$table->id)->count();
        //     if ($harikerja == 0) {
        //         $isi['id']=($table->id);
        //         $isi['jenis_jadwal']=($table->jenis_jadwal);
        //         array_push($jadwalkerja,$isi);
        //     }
        // }
        //
        // dd($jadwalkerja);


        $rule=rulejammasuk::join('jadwalkerjas','rulejammasuks.jadwalkerja_id','=','jadwalkerjas.id')->select('rulejammasuks.*','jadwalkerjas.jenis_jadwal')->where('rulejammasuks.instansi_id',Auth::user()->instansi_id)->get();
//        dd($rule);
        // $jadwalkerja=jadwalkerja::where('instansi_id',Auth::user()->instansi_id)->get();
        return view('jadwalkerja.jadwalkerja',['inforekap'=>$inforekap,'jadwalkerjas'=>$tables,'rules'=>$rule]);
    }

    public function store(Request $request){
        $this->validate($request, [
            'jenisjadwal'=>'required',
            'awal'=>'required|min:5',
            'pulang'=>'required|min:5'
        ]);

        $user = new jadwalkerja;
        $user->jenis_jadwal = $request->jenisjadwal;
        $user->jam_masukjadwal = $request->awal;
        $user->jam_keluarjadwal = $request->pulang;
        $user->instansi_id = $request->instansi_id;
        $user->save();
        // dd("tes");
        return redirect('/jadwalkerja');

    }

    public function editshow($id){
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }
        $id = decrypt($id);
        // dd($id);
        $jadwal=jadwalkerja::where('id','=',$id)->first();

       // dd($jadwal);
        return view('jadwalkerja.editjadwalkerja',['inforekap'=>$inforekap,'jadwals'=>$jadwal]);
    }

    public function editstore(Request $request){
        $this->validate($request, [
            'jenisjadwal'=>'required|min:5',
            'awal'=>'required|min:5',
            'pulang'=>'required|min:5'
        ]);
        $table=jadwalkerja::where('id','=',$request->id)->first();
        $table->jam_masukjadwal=$request->awal;
        $table->jam_keluarjadwal=$request->pulang;
        $table->jenis_jadwal=$request->jenisjadwal;
        $table->save();
        return redirect('/jadwalkerja');
    }

    public function deletestore($id){
        $id = decrypt($id);
        $table=jadwalkerja::find($id);
        $table->delete();
        return redirect('/jadwalkerja');
    }


}
