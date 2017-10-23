<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tugasluar;
use Yajra\Datatables\Facades\Datatables;

class TlAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
         //
         return view('admin.tl.tl');

     }

     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
         //
     }

     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function store(Request $request)
     {
         //
     }

     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
         //
         $sakit=tugasluar::join('rekapbulanans','tugasluars.rekapbulanan_id','=','rekapbulanans.id')
         ->join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
         ->join('instansis','tugasluars.instansi_id','=','instansis.id')
         ->select('tugasluars.*','rekapbulanans.pegawai_id','pegawais.nip','pegawais.nama','instansis.namaInstansi')
         ->where('tugasluars.id','=',$id)
         ->first();
         return view('admin.tl.edittl',['sakit'=>$sakit]);
     }

     public function datatb()
     {
       $sakit=tugasluar::join('rekapbulanans','tugasluars.rekapbulanan_id','=','rekapbulanans.id')
       ->join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
       ->join('instansis','tugasluars.instansi_id','=','instansis.id')
       ->select('tugasluars.*','rekapbulanans.pegawai_id','pegawais.nip','pegawais.nama','instansis.namaInstansi')
       ->get();
       return Datatables::of($sakit)
       ->addColumn('action',function($sakit){
           return '<a href="/tugasluar/admin/show/'.$sakit->id.'" class="btn-sm btn-success"><i class="fa fa-edit"></i></a>
           <a href="/tugasluar/admin/download/'.$sakit->namafile.'" class="btn-sm btn-danger"><i class="fa fa-download"></i></a>';
         })
       ->make(true);

     }

     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function edit($id)
     {
         //
     }

     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request, $id)
     {
         //
         $this->validate($request, [
             'nip'=>'required',
             'nama'=>'required',
             'lama'=>'required',
             'tanggal'=>'required',
             'file'=>'mimes:jpeg,jpg,png,pdf|required|max:700',
         ]);
         $t=time();
         $tgl=date('Y-m-d-H-i-s',$t);

         $ijin=tugasluar::find($id);

         $ext=$request->file('file')->getClientOriginalExtension();
         $filename= 'revisi+'.$ijin->instansi_id.'+'.$request->id.'+'.$tgl.'.'.$ext;
         $request->file('file')->storeAs('public/file/tugasluar',$filename);

         $bleave = tugasluar::find($id)->first();

         $bleave->namafile=$filename;
         $bleave->save();
         return redirect('/tugasluar/admin');
     }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
         //
     }
}
