<?php

namespace App\Http\Controllers;
use App\adminpegawai;
use App\triger;
use App\hapusfingerpegawai;
use App\historyfingerpegawai;
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

        $dataadmin=adminpegawai::leftJoin('pegawais','adminpegawais.pegawai_id','=','pegawais.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select('adminpegawais.*','pegawais.nip','pegawais.nama','instansis.namaInstansi')
                ->get();
        // dd($datas);
        return view('trigger.trigger',['status'=>$trigger,'datas'=>$datas,'admins'=>$dataadmin]);
    }

    public function edit(Request $request) {
        $tables=triger::where('id','=','1')->first();
        $tables->status=$request->triger;
        $tables->save();

        return redirect('/trigger')->with('success', 'Berhasil menyimpan trigger.');
    }

    public function hapus(Request $request){
        $this->validate($request, [
            'pegawai'=>'required'
        ]);

        $validasi=hapusfingerpegawai::where('pegawai_id','=',$request->pegawai[0])
                ->count();
        
        if ($validasi>0){

        }
        else{
            $table=new hapusfingerpegawai;
            $table->pegawai_id=$request->pegawai[0];
            $table->save();

            $loadtables=lograspberry::all();

            foreach ($loadtables as $key => $loadtable) {
                $tablehistory=new  historyfingerpegawai;
                $tablehistory->pegawai_id=$loadtable->pegawai_id;
                $tablehistory->iphapus=$loadtable->alamatip;
                $tablehistory->statushapus="0";
                $tablehistory->instansi_id=$loadtable->instansi_id;
                $tablehistory->save();
            }
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

    public function postadmindata(Request $request){
        $this->validate($request, [
            'pegawai2'=>'required'
        ]);

        $validasi=adminpegawai::where('pegawai_id','=',$request->pegawai2[0])
                ->count();
        
        if ($validasi>0){

        }
        else{
            $table=new adminpegawai;
            $table->pegawai_id=$request->pegawai2[0];
            $table->save();
        }

        return redirect('/trigger');
    }

    public function hapusadmindata(Request $request){
        $this->validate($request, [
            'checkbox3'=>'required'
        ]);

        foreach ($request->checkbox3 as $key=> $data){
            $data=decrypt($data);
            // dd($data);
            $table=adminpegawai::where('id','=',$data);
            // dd($table);
            $table->delete();
        }

        return redirect('/trigger');
    }
}
