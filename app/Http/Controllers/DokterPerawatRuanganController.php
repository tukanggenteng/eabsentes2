<?php

namespace App\Http\Controllers;

use App\fingerpegawai;
use App\pegawai;
use App\dokter;
use App\perawatruangan;
use App\ruanganuser;
use App\ruangan;
use App\instansi;
use App\atts_tran;
use App\att;
use App\User;
use App\hapusfingerpegawai;
use App\adminpegawai;
use App\rulejadwalpegawai;
use Illuminate\Http\Request;
use Validator;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class DokterPerawatRuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return view('pegawai.khusus.manajemenpegawaikhusus');

    }

    public function datapegawai(){
        $datadokter=dokter::pluck('pegawai_id')->all();
        $dataperawat=perawatruangan::pluck('pegawai_id')->all();
        $users=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select('pegawais.*','instansis.namaInstansi')
                ->whereNotIn('pegawais.id',$dataperawat)
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->get();
        return Datatables::of($users)
        ->addColumn('action', function ($users) {
            return '<button type="button" class="modal_delete btn btn-danger btn-sm" data-toggle="modal" data-nip="'.encrypt($users->nip).'" data-jabatan="'.$users->jabatan.'" data-instansi="'.$users->instansi_id.'" data-nama="'.$users->nama.'" data-id="'.encrypt($users->id).'" data-target="#modal_delete">Hapus</button>';
            })
            ->make(true);
    }

    public function datadokter(){
        $users=dokter::leftJoin('instansis','dokters.instansi_id','=','instansis.id')
                ->leftJoin('pegawais','dokters.pegawai_id','=','pegawais.id')
                ->where('dokters.instansi_id','=',Auth::user()->instansi_id)
                ->select('dokters.*','pegawais.id','pegawais.nip','pegawais.nama','instansis.namaInstansi')
                ->get();
        return Datatables::of($users)
        ->addColumn('action', function ($users) {
            return '<button type="button" class="modal_delete24 btn btn-danger btn-sm" data-toggle="modal" data-nip="'.$users->nip.'"  data-instansi="'.$users->instansi_id.'" data-nama="'.$users->nama.'" data-id="'.encrypt($users->id).'" data-target="#modal_delete24">Hapus</button>';
            })
            ->make(true);
    }

    public function dataallperawat(){
        $users=perawatruangan::leftJoin('pegawais','perawatruangans.pegawai_id','=','pegawais.id')
            ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
            ->leftJoin('ruangans','perawatruangans.ruangan_id','=','ruangans.id')
            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
            ->select('perawatruangans.*','pegawais.id','pegawais.nama','pegawais.nip','ruangans.nama_ruangan')
            ->get();

        // dd($users);
        return Datatables::of($users)
            ->addColumn('action', function ($users) {
            return '<button type="button" class="modal_deleteperawat btn btn-danger btn-sm" data-toggle="modal" data-nip="'.$users->nip.'"  data-nama="'.$users->nama.'" data-id="'.encrypt($users->pegawai_id).'" data-ruangan="'.$users->nama_ruangan.'" data-target="#modal_deleteperawat">Hapus</button>';
            })
            ->make(true);

        // dd($data);
    }

    public function dataruangan(){
        $users=ruangan::leftJoin('instansis','ruangans.instansi_id','=','instansis.id')
            ->where('ruangans.instansi_id','=',Auth::user()->instansi_id)
            ->select('ruangans.*','instansis.namaInstansi')
            ->get();
        return Datatables::of($users)
        ->addColumn('action', function ($users) {
            return '
            <div class="btn-group">
                <button type="button" class="modal_deleteruangan btn btn-danger btn-sm" data-toggle="modal" data-id="'.encrypt($users->id).'" data-ruangan="'.$users->nama_ruangan.'" data-target="#modal_deleteruangan">Hapus</button>
                <button type="button" class="modal_editruangan btn btn-success btn-sm" data-toggle="modal" data-id="'.encrypt($users->id).'" data-ruangan="'.$users->nama_ruangan.'" data-target="#modal_editruangan">Edit</button>
            </div>
            ';
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function caridokter(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return response()->json([]);
        }
        $datadokter=dokter::pluck('pegawai_id')->all();
        $dataperawat=perawatruangan::pluck('pegawai_id')->all();
        $tags = pegawai::
                where('nip','LIKE','%'.$term.'%')
                // ->orWhere('nama','LIKE','%'.$term.'%')
                ->where('instansi_id','=',Auth::user()->instansi_id)
                ->whereNotIn('id',$datadokter)
                ->whereNotIn('id',$dataperawat)
                ->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->nip." - ".$tag->nama];
        }
        return response()->json($formatted_tags);
    }

    public function cariruangan(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return response()->json([]);
        }
        $tags = ruangan::
                where('nama_ruangan','LIKE','%'.$term.'%')
                ->where('instansi_id','=',Auth::user()->instansi_id)
                ->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->nama_ruangan];
        }
        return response()->json($formatted_tags);
    }

    public function storedokter(Request $request){
        //
        // dd($request->nip);
        $table=new dokter;
        $table->pegawai_id=$request->nip;
        $table->instansi_id=Auth::user()->instansi_id;

        if ($table->save()){
            return response()->json("success");
        }
        else
        {
            return response()->json("failed");
        }
    }

    public function destroydokter(Request $request){

        $table=dokter::where('id','=',decrypt($request->delidpegawai))
                ->first();

        // dd(decrypt($request->delidpegawai));

        $pegawaiid=($table->pegawai_id);
        // dd($table->pegawai_id);
        if ($table->delete()){
            $deleterulepegawai=rulejadwalpegawai::where('pegawai_id','=',$pegawaiid);
            $deleterulepegawai->delete();
            return response()->json("success");
        }
        else
        {
            return response()->json("failed");
        }
    }

    public function storeruangan(Request $request){
        $rules=array(
            'ruangan'=>'required',
        );

        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else {
            $table= new ruangan;
            $table->nama_ruangan=$request->ruangan;
            $table->instansi_id=Auth::user()->instansi_id;

            if ($table->save()){
                return response()->json("Success");
            }else{
                return response()->json("Failed");
            }
        }

    }

    public function updateruangan(Request $request){
        $rules=array(
            'edit_ruangan'=>'required',
        );

        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else {
            $table=ruangan::where('id','=',decrypt($request->id_ruangan))->first();
            $table->nama_ruangan=($request->edit_ruangan);
            $table->instansi_id=Auth::user()->instansi_id;

            if ($table->save()){
                return response()->json("Success");
            }else{
                return response()->json("Failed");
            }
        }

    }

    public function destroyruangan(Request $request){
            $table=ruangan::where('id','=',decrypt($request->delidruangan))->first();
            if ($table->delete()){
                $table=perawatruangan::where('ruangan_id','=',decrypt($request->delidruangan))->delete();

                $ruanganusers=ruanganuser::where('ruangan_id','=',decrypt($request->delidruangan))->get();

                foreach ($ruanganusers as $ruanganuser)
                {
                    $userdata=User::where('id','=',$ruanganuser->user_id)
                        ->first();
                    $userdata->delete();
                }

                return response()->json("Success");
            }else{
                return response()->json("Failed");
            }
    }

    public function storeperawat(Request $request){
        if (Auth::user()->role->namaRole=="karu")
        {
            $user=ruanganuser::where('user_id','=',Auth::user()->id)->first();
            $table=new perawatruangan;
            $table->pegawai_id=$request->idpegawai;
            $table->ruangan_id=$user->ruangan_id;
            if ($table->save()){
                return response()->json("success");
            }
            else
            {
                return response()->json("failed");
            }
        }
        else
        {
            $table=new perawatruangan;
            $table->pegawai_id=$request->idpegawai;
            $table->ruangan_id=$request->idruangan;
            if ($table->save()){
                return response()->json("success");
            }
            else
            {
                return response()->json("failed");
            }
        }
    }

    public function destroyperawat(Request $request){
        // dd(decrypt($request->delidpegawai));
        $table=perawatruangan::where('pegawai_id','=',decrypt($request->delidpegawaiperawat))->first();
        $pegawaiid=($table->pegawai_id);
        if ($table->delete()){
            $deleterulepegawai=rulejadwalpegawai::where('pegawai_id','=',$pegawaiid);
            $deleterulepegawai->delete();
            return response()->json("Success");
        }else{
            return response()->json("Failed");
        }
}


    public function indexruangan(){
        return view('pegawai.khusus.manajemenpegawairuangan');
    }



    public function cariperawatspesifik()
    {
        //
        $term = trim($request->q);
        if (empty($term)) {
            return response()->json([]);
        }
        $datadokter=dokter::pluck('pegawai_id')->all();
        $dataperawat=perawatruangan::pluck('pegawai_id')->all();
        $tags = pegawai::
                where('nip','LIKE','%'.$term.'%')
                ->orWhere('nama','LIKE','%'.$term.'%')
                ->where('instansi_id','=',Auth::user()->instansi_id)
                ->whereNotIn('id',$datadokter)
                ->whereNotIn('id',$dataperawat)
                ->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->nip." - ".$tag->nama];
        }
        return response()->json($formatted_tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dataspesifikperawat()
    {
        $userruangan=ruanganuser::where('user_id','=',Auth::user()->id)->first();

        $datadokter=dokter::pluck('pegawai_id')->all();

        $dataperawat=perawatruangan::pluck('pegawai_id')->where('ruangan_id','=',$userruangan->ruangan_id)->all();

        $users=perawatruangan::leftJoin('pegawais','perawatruangans.pegawai_id','=','pegawais.id')
                ->leftJoin('ruangans','perawatruangans.ruangan_id','=','ruangans.id')
                ->where('perawatruangans.ruangan_id','=',$userruangan->ruangan_id)
                ->select('perawatruangans.*','pegawais.nama','pegawais.nip','ruangans.nama_ruangan')
                ->get();

        return Datatables::of($users)
        ->addColumn('action', function ($users) {
            return '<button type="button" class="modal_deleteperawat btn btn-danger btn-sm" data-toggle="modal" data-nip="'.encrypt($users->nip).'" data-jabatan="'.$users->jabatan.'" data-instansi="'.$users->instansi_id.'" data-nama="'.$users->nama.'" data-id="'.encrypt($users->pegawai_id).'" data-target="#modal_deleteperawat">Hapus</button>';
            })
            ->make(true);
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
