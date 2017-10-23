<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ijinterlambat;
use Yajra\Datatables\Facades\Datatables;

class ItAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
         //
         return view('admin.it.it');

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
         $sakit=ijinterlambat::join('rekapbulanans','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
         ->join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
         ->join('instansis','ijinterlambats.instansi_id','=','instansis.id')
         ->select('ijinterlambats.*','rekapbulanans.pegawai_id','pegawais.nip','pegawais.nama','instansis.namaInstansi')
         ->where('ijinterlambats.id','=',$id)
         ->first();
         return view('admin.it.editit',['sakit'=>$sakit]);
     }

     public function datait()
     {
       $sakit=ijinterlambat::join('rekapbulanans','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
       ->join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
       ->join('instansis','ijinterlambats.instansi_id','=','instansis.id')
       ->select('ijinterlambats.*','rekapbulanans.pegawai_id','pegawais.nip','pegawais.nama','instansis.namaInstansi')
       ->get();
       return Datatables::of($sakit)
       ->addColumn('action',function($sakit){
           return '<a href="/ijinterlambat/admin/show/'.$sakit->id.'" class="btn-sm btn-success"><i class="fa fa-edit"></i></a>
           <a href="/ijinterlambat/admin/download/'.$sakit->namafile.'" class="btn-sm btn-danger"><i class="fa fa-download"></i></a>';
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

         $ijin=ijinterlambat::find($id);

         $ext=$request->file('file')->getClientOriginalExtension();
         $filename= 'revisi+'.$ijin->instansi_id.'+'.$request->id.'+'.$tgl.'.'.$ext;
         $request->file('file')->storeAs('public/file/ijinterlambat',$filename);

         $bleave = ijinterlambat::find($id)->first();

         $bleave->namafile=$filename;
         $bleave->save();
         return redirect('/ijinterlambat/admin');
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
