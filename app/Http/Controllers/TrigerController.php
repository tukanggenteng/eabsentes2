<?php

namespace App\Http\Controllers;
use App\triger;
use App\hapusfingerpegawai;
use Illuminate\Http\Request;

class TrigerController extends Controller
{
    //
    
    public function __construct()
    {
        $this->middleware('throttle:500000,1');
    }

    public function index(){
        $table=triger::all();
        return $table;
    }

    public function form() {
        $tables=triger::where('id','=','1')->first();
        // dd($tables);
        $trigger=$tables->status;

        $datas=hapusfingerpegawai::leftJoin('pegawais','hapusfingerpegawais.pegawai_id','=','pegawais.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select('hapusfingerpegawais.*','pegawais.nip','pegawais.nama','instansis.namaInstansi')
                ->get();

        // dd($datas);
        return view('trigger.trigger',['status'=>$trigger,'datas'=>$datas]);
    }

    public function edit(Request $request) {
        $tables=triger::where('id','=','1')->first();
        $tables->status=$request->triger;
        $tables->save();

        return redirect('/trigger')->with('success', 'Berhasil menyimpan trigger.');
    }

    public function hapus(Request $request){
        $validasi=hapusfingerpegawai::where('pegawai_id','=',$request->pegawai[0])
                ->count();
        
        if ($validasi>0){

        }
        else{
            $table=new hapusfingerpegawai;
            $table->pegawai_id=$request->pegawai[0];
            $table->save();
        }

        return redirect('/trigger');
    }

    public function posthapus(Request $request){
        // dd($request->checkbox2);
        $this->validate($request, [
            'checkbox2'=>'required'
        ]);

        foreach ($request->checkbox2 as $key=> $data){
            $data=decrypt($data);
            // dd($data);
            $table=hapusfingerpegawai::where('id','=',$data);
            // dd($table);
            $table->delete();
        }

        return redirect('/trigger');

    }

    public function pegawaihapus(){
        $table=hapusfingerpegawai::all();
        return $table;
    }
}
