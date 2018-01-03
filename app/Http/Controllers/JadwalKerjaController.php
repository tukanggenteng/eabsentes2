<?php

namespace App\Http\Controllers;

use App\jadwalkerja;
use App\rulejammasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalKerjaController extends Controller
{
    //
    public function index(Request $request){
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
        $tables=jadwalkerja::leftJoin('instansis','jadwalkerjas.instansi_id','=','instansis.id')
                ->select('jadwalkerjas.*','instansis.namaInstansi')
                ->get();
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

        if ($request->cari==""){
            $rule=rulejammasuk::leftJoin('jadwalkerjas','rulejammasuks.jadwalkerja_id','=','jadwalkerjas.id')
            ->leftJoin('instansis','jadwalkerjas.instansi_id','=','instansis.id')
            ->select('rulejammasuks.*','jadwalkerjas.jenis_jadwal','instansis.namaInstansi')
            ->paginate(40);
        }
        else
        {
            $rule=rulejammasuk::leftJoin('jadwalkerjas','rulejammasuks.jadwalkerja_id','=','jadwalkerjas.id')
            ->leftJoin('instansis','jadwalkerjas.instansi_id','=','instansis.id')
            ->select('rulejammasuks.*','jadwalkerjas.jenis_jadwal','instansis.namaInstansi')
            ->where('jadwalkerjas.jenis_jadwal','LIKE','%'.$request->cari.'%')
            ->orWhere('instansis.namaInstansi','LIKE','%'.$request->cari.'%')
            ->paginate(40);
        }
       //dd($rule);
        // $jadwalkerja=jadwalkerja::where('instansi_id',Auth::user()->instansi_id)->get();
        return view('jadwalkerja.jadwalkerja',['inforekap'=>$inforekap,'jadwalkerjas'=>$tables,'cari'=>$request->cari,'rules'=>$rule]);
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
        $user->instansi_id = $request->instansi_id[0];
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

    //    dd($jadwal);
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

    public function cari(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return response()->json([]);
        }
        $tags = jadwalkerja::
                leftJoin('instansis','jadwalkerjas.instansi_id','=','instansis.id')
                ->where('jenis_jadwal','LIKE','%'.$term.'%')
                ->orWhere('namaInstansi','LIKE','%'.$term.'%')
                ->select('jadwalkerjas.*','instansis.namaInstansi')
                ->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->jenis_jadwal."( ".$tag->jam_masukjadwal." - ".$tag->jam_keluarjadwal." )"." - ".$tag->namaInstansi];
        }
        return response()->json($formatted_tags);
    }


}
