<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\ijin;
use App\User;
use Yajra\Datatables\Facades\Datatables;

class IjinAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.ijin.ijin');

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
        $ijin=ijin::join('rekapbulanans','ijins.rekapbulanan_id','=','rekapbulanans.id')
        ->join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
        ->join('instansis','ijins.instansi_id','=','instansis.id')
        ->select('ijins.*','rekapbulanans.pegawai_id','pegawais.nip','pegawais.nama','instansis.namaInstansi')
        ->where('ijins.id','=',$id)
        ->first();
        return view('admin.ijin.editijin',['ijin'=>$ijin]);
    }

    public function dataijin()
    {
      $ijin=ijin::join('rekapbulanans','ijins.rekapbulanan_id','=','rekapbulanans.id')
      ->join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
      ->join('instansis','ijins.instansi_id','=','instansis.id')
      ->select('ijins.*','rekapbulanans.pegawai_id','pegawais.nip','pegawais.nama','instansis.namaInstansi')
      ->get();
      // dd($ijin);
      return Datatables::of($ijin)
      ->addColumn('action',function($ijin){
            if ($ijin->namafile==1){
                $status='<span class="badge bg-green">Terlaporkan</span>';
            }
            else
            {
            $status='<span class="badge bg-red">Tidak Terlaporkan</span>';
            }
            return $status;
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
    public function update(Request $request,$id)
    {
        //
        // dd("asdasd");

        $this->validate($request, [
            'nip'=>'required',
            'nama'=>'required',
            'lama'=>'required',
            'tanggal'=>'required',
            'file'=>'mimes:jpeg,jpg,png,pdf|required|max:700',
        ]);
        $t=time();
        $tgl=date('Y-m-d-H-i-s',$t);

        $ijin=ijin::find($id);

        $ext=$request->file('file')->getClientOriginalExtension();
        $filename= 'revisi+'.$ijin->instansi_id.'+'.$request->id.'+'.$tgl.'.'.$ext;
        $request->file('file')->storeAs('public/file/ijin',$filename);

        $bleave = ijin::find($id)->first();

        // Storage::delete('app/file/ijin/dodo+1+1+2017-10-08-16-21-41.PNG');
        // unlink(storage_path('/app/public/file/ijin/dodo+1+1+2017-10-08-16-21-41.PNG'));
        $bleave->namafile=$filename;
        $bleave->save();
        return redirect('/ijin/admin');
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
