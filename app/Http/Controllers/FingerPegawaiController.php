<?php

namespace App\Http\Controllers;
use App\pegawai;
use App\fingerpegawai;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class FingerPegawaiController extends Controller
{
    //
    public function index(Request $request){

      if ($request->cari=="")
      {
	$finger=DB::raw("(SELECT pegawai_id,COUNT(pegawai_id) as finger from fingerpegawais group by pegawai_id) as fingerpegawais");
        $pegawais=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
              ->leftJoin($finger,'fingerpegawais.pegawai_id','=','pegawais.id')
              //->select('pegawais.*','instansis.namaInstansi',DB::raw('COUNT(fingerpegawais.pegawai_id) as finger'))
              ->orderBy('fingerpegawais.finger','desc')
              ->paginate(30);
      }
      else {
        // dd("asd");
	$finger=DB::raw("(SELECT pegawai_id,COUNT(pegawai_id) as finger from fingerpegawais GROUP BY pegawai_id) as fingerpegawais");
        $pegawais=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
              ->leftJoin($finger,'fingerpegawais.pegawai_id','=','pegawais.id')
              //->select('pegawais.*','instansis.namaInstansi',DB::raw('COUNT(fingerpegawais.pegawai_id) as finger'))
              ->orWhere('pegawais.nip','like','%'.$request->cari.'%')
              ->orWhere('pegawais.nama','like','%'.$request->cari.'%')
              ->orWhere('instansis.namaInstansi','like','%'.$request->cari.'%')
              ->orderBy('fingerpegawais.finger','desc')
              ->paginate(30);
      }

      return view('finger.fingerpegawai',['pegawais'=>$pegawais,'cari'=>$request->cari]);
    }

    public function show($id){

      $id=decrypt($id);

      $pegawais=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
            ->select('pegawais.*','instansis.namaInstansi')
            ->where('pegawais.nip','=',$id)
            ->first();
      //dd($pegawais->id);
      $fingers=fingerpegawai::where('pegawai_id','=',$pegawais->id)
                ->get();
       //dd($fingers);
      return view('finger.editfingerpegawai',['pegawais'=>$pegawais,'fingers'=>$fingers]);
    }

    public function destroy($id){
      $id=decrypt($id);
      $hapus=fingerpegawai::where('id','=',$id)->first();
	//dd($hapus);
      $hapus->delete();
      return redirect()->back();
    }
}
