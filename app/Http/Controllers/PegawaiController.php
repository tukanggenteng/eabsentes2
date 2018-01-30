<?php

namespace App\Http\Controllers;

use App\fingerpegawai;
use App\pegawai;
use App\instansi;
use App\atts_tran;
use App\att;
use App\hapusfingerpegawai;
use App\adminpegawai;
use App\rulejadwalpegawai;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redis;
use Ixudra\Curl\Facades\Curl;
use App\Events\Timeline;

use Illuminate\Support\Facades\DB;
use Validator;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('throttle:500000,1');
    }


    public function index(Request $request)
    {
        //
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }

        $hitung=pegawai::all()->count();

        if ($request->cari=="")
        {
          $pegawais=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->paginate(30);
        }
        else {

          $pegawais=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->orWhere('pegawais.nip','like','%'.$request->cari.'%')
                ->orWhere('pegawais.nama','like','%'.$request->cari.'%')
                ->orWhere('instansis.namaInstansi','like','%'.$request->cari.'%')
                ->paginate(30);
        }


        return view('pegawai.manajpegawai',['hitungs'=>$hitung,'inforekap'=>$inforekap,'pegawais'=>$pegawais]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function data(){
        $users=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')->get();
        return Datatables::of($users)
        ->addColumn('action', function ($users) {
            return '<button type="button" class="modal_delete btn btn-danger btn-sm" data-toggle="modal" data-nip="'.$users->nip.'" data-jabatan="'.$users->jabatan.'" data-instansi="'.$users->instansi_id.'" data-nama="'.$users->nama.'" data-id="'.encrypt($users->id).'" data-target="#modal_delete">Hapus</button>';
            })
            ->make(true);
    }

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
      //    $response=Curl::to('https://simpeg.kalselprov.go.id/api/identitas')
	    // ->withTimeout(600)
      //   //     // ->asJSON()
      //        ->get();
      //   //     // dd($response);
      //    $jsons=json_decode($response,true);
      //    dd(count($response));




      $url="https://simpeg.kalselprov.go.id/api/identitas";
      $ch = curl_init();
      // Disable SSL verification
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      // Will return the response, if false it print the response
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // Set the url
      curl_setopt($ch, CURLOPT_URL,$url);
      // Execute
      $result=curl_exec($ch);
      // Closing
      curl_close($ch);
      // dd($result);

      //Will dump a beauty json :3
      $jsons=(json_decode($result, true));

      $yg2=array();
        //
      // dd(($jsons));
     foreach ($jsons as $key=>$json)
     {

                 // set_time_limit(0);
                 // ini_set('memory_limit', '20000M');

      $pegawai=pegawai::where('nip','=',$json['nip'])
      ->count();

      if ($pegawai > 0){
          // dd($json['nip']);
          array_push($yg2,$json['nip']);
      }
      else{
         $user = new pegawai();
         $user->nip = $json['nip'];
         $user->nama = $json['nama'];
         $user->instansi_id = null;
         $user->save();
      }

        //  $user = new pegawai();
        //  $user->nip = $json['nip'];
        //  $user->nama = $json['nama'];
        //  $user->instansi_id = null;
        //  $user->save();
        // meambil lawan satkernya
          //  $pegawai=pegawai::where('nip','=',$json['nip'])
          //  ->count();
          //
          //  if ($pegawai > 0){
          //    if (($json['kode_satker']==null) || ($json['kode_satker']=="0") || ($json['kode_unker']=="") || ($json['kode_unker']=="")){
          //
          //    }
          //    else {
          //
          //          $response2=Curl::to('https://simpeg.kalselprov.go.id/api/satker?kode='.$json['kode_satker'])
          //              ->get();
          //          $jsonssatker=json_decode($response2,true);
          //
          //          if ($json['satker']==$json['unker'])
          //          {
          //              $instansi=instansi::where('kode','=',$json['kode_unker'])
          //                  ->get();
          //              $user=pegawai::where('nip','=',$json['nip'])
          //              ->first();
          //              $user->nip = $json['nip'];
          //              $user->nama = $json['nama'];
          //              $user->instansi_id = $instansi[0]['id'];
          //              $user->save();
          //          }
          //          elseif (($json['satker']!=$json['unker']) && ($jsonssatker[0]['upt']==null) || ($jsonssatker[0]['upt']=="0"))
          //          {
          //            $instansi=instansi::where('kode','=',$json['kode_unker'])
          //                ->get();
          //            $user=pegawai::where('nip','=',$json['nip'])
          //             ->first();
          //            $user->nip = $json['nip'];
          //            $user->nama = $json['nama'];
          //            $user->instansi_id = $instansi[0]['id'];
          //            $user->save();
          //          }
          //          elseif (($json['satker']!=$json['unker']) && ($jsonssatker[0]['upt']=="1")) {
          //            $instansi=instansi::where('kode','=',$json['kode_unker'])
          //                ->get();
          //            $user=pegawai::where('nip','=',$json['nip'])
          //            ->first();
          //           //  $user = new pegawai();
          //            $user->nip = $json['nip'];
          //            $user->nama = $json['nama'];
          //            $user->instansi_id = $instansi[0]['id'];
          //            $user->save();
          //          }
          //     }
          //  }
          //  else {
          //
          //   // dd($json['nip']);
          //   if (($json['kode_satker']==null) || ($json['kode_satker']=="0") || ($json['kode_unker']=="") || ($json['kode_unker']=="")){
          //
          //   }
          //   else {
          //     $response2=Curl::to('https://simpeg.kalselprov.go.id/api/satker?kode='.$json['kode_satker'])
          //         ->get();
          //        //  dd($response2);
          //     $jsonssatker=json_decode($response2,true);
          //     // dd($json['kode_satker']);
          //     if ($json['satker']==$json['unker'])
          //     {
          //         $instansi=instansi::where('kode','=',$json['kode_unker'])
          //             ->get();
          //         $user = new pegawai();
          //         $user->nip = $json['nip'];
          //         $user->nama = $json['nama'];
          //         $user->instansi_id = $instansi[0]['id'];
          //         $user->save();
          //     }
          //     elseif (($json['satker']!=$json['unker']) && ($jsonssatker[0]['upt']==null))
          //     {
          //       $instansi=instansi::where('kode','=',$json['kode_unker'])
          //           ->get();
          //         // dd($json['kode_unker']);
          //       $user = new pegawai();
          //       $user->nip = $json['nip'];
          //       $user->nama = $json['nama'];
          //       $user->instansi_id = $instansi[0]['id'];
          //       $user->save();
          //     }
          //     elseif (($json['satker']!=$json['unker']) && ($jsonssatker[0]['upt']=="1")) {
          //       $instansi=instansi::where('kode','=',$json['kode_unker'])
          //           ->get();
          //
          //       $user = new pegawai();
          //       $user->nip = $json['nip'];
          //       $user->nama = $json['nama'];
          //       $user->instansi_id = $instansi[0]['id'];
          //       $user->save();
          //     }
          //   }
          // }


       }
       return "berhasil";

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
        $instansis=instansi::all();
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }
        return view('pegawai.manajemenpegawaiuser',['instansis'=>$instansis,'inforekap'=>$inforekap]);
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
    public function update(Request $request)
    {
        $tes=$request->instansi;
        // dd($request->nip);
        // $updatedata = pegawai::where('nip','=',$request->nip)->get();
        // dd($updatedata);
        // $updatedata->instansi_id = $tes;
        // $updatedata->save();
        // return response()->json($updatedata);
        $rules=array(
            'nama'=>'required | min:3',
            'instansi'=>'required'
        );

        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else {
            $updatedata = pegawai::where('nip','=',$request->nip)->first();
            // dd($updatedata);
            $updatedata->instansi_id = $tes;
            $updatedata->save();
            return response()->json($updatedata);
        }

        // $this->validate($request, [
        //   'nama2'=>'required | min:3',
        //   'jabatan2'=>'required',
        //   'instansi2'=>'required',
        // ]);
    }


    public function datauser(){
        $users=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
              ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
              ->select('pegawais.*','instansis.namaInstansi')
              ->get();
        return Datatables::of($users)
              ->addColumn('action', function ($users) {
                  return '<button type="button" class="modal_delete btn btn-danger btn-sm" data-toggle="modal" data-nip="'.$users->nip.'" data-jabatan="'.$users->jabatan.'" data-instansi="'.$users->instansi_id.'" data-nama="'.$users->nama.'" data-id="'.encrypt($users->id).'" data-target="#modal_delete">Hapus</button>';
              })
            ->make(true);
    }


    public function validasipegawai($id){
      $pegawai=pegawai::where('nip','=',$id)
      ->where('instansi_id','!=',null)
      ->count();
      $pegawai2=pegawai::where('nip','=',$id)
      ->count();
      if (($pegawai>0) && ($pegawai2>0)){
        $ambilpegawai=pegawai::where('nip','=',$id)->first();
        $seluruh['status']="1";
        $seluruh['nip']=$id;
        $seluruh['nama']=$ambilpegawai->nama;
        $seluruh['instansi_id']=$ambilpegawai->instansi_id;
        return $seluruh;
      }
      elseif (($pegawai==0) && ($pegawai2>0)){
        $ambilpegawai=pegawai::where('nip','=',$id)->first();
        $seluruh['status']="0";
        $seluruh['nama']=$ambilpegawai->nama;
        return $seluruh;
      }
      else{
        $seluruh['status']="3";
        return $seluruh;
      }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $deletepegawai=($request->delidpegawai);
        $updatedata = pegawai::where('nip','=',$deletepegawai)->first();
        // dd($updatedata->id);
        $deleterulepegawai=rulejadwalpegawai::where('pegawai_id','=',$updatedata->id);
        $deleterulepegawai->delete();
        $tanggalnow=date("Y-m-d");
        $hapusattstrans=atts_tran::where('pegawai_id','=',$updatedata->id)
        ->where('tanggal','=',$tanggalnow);
        $hapusattstrans->delete();

        $hapusatts=att::where('pegawai_id','=',$updatedata->id)
        ->where('tanggal_att','=',$tanggalnow);
        $hapusatts->delete();

        $updatedata->instansi_id = null;
        $updatedata->save();


        return response()->json($updatedata);
    }

    public function cekpegawai($id){

        $finger=DB::raw("(SELECT pegawai_id,COUNT(pegawai_id) as finger from fingerpegawais group by pegawai_id) as fingerpegawais");
            $tanpapegawai=hapusfingerpegawai::pluck('pegawai_id')->all();
            $adminsidikjari=adminpegawai::pluck('pegawai_id')->all();

            $table=pegawai::
            leftJoin($finger,'fingerpegawais.pegawai_id','=','pegawais.id')
            ->where('instansi_id','!=',null)
            ->where('instansi_id','=',$id)
            ->where('finger','=',2)
            ->whereNotIn('id',$tanpapegawai)
            ->whereNotIn('id',$adminsidikjari)
            ->get();

            return $table;

        
	   }

     public function cekpegawaiinstansi($id){
        $table=pegawai::where('instansi_id','=',$id)->get();
     	return $table;
 	   }

    public function ambilfinger($id){
        $table=fingerpegawai::where('pegawai_id','=',$id)->get();
        return $table;
    }

    public function addfinger(Request $request){

        $hitung=fingerpegawai::where('pegawai_id','=',$request->json('pegawai_id'))
                ->count();
        if ($hitung == 2)
        {
            return "Full";
        }
        else{
            $user = new fingerpegawai();
            $user->pegawai_id = $request->json('pegawai_id');
            $user->size=$request->json('size');
            $user->valid=$request->json('valid');
            $user->templatefinger = $request->json('templatefinger');
            $user->save();
            return "Succes";
        }

        
    }


    public function datapegawaiadmin(Request $request){
      $users=pegawai::join('instansis','pegawais.instansi_id','=','instansis.id')
            ->get();
      return Datatables::of($users)
            ->addColumn('action', function ($users) {
                return '<button type="button" class="modal_delete btn btn-danger btn-sm" data-toggle="modal" data-nip="'.$users->nip.'" data-jabatan="'.$users->jabatan.'" data-instansi="'.$users->instansi_id.'" data-nama="'.$users->nama.'" data-id="'.encrypt($users->id).'" data-target="#modal_delete">Hapus</button>';
            })
          ->make(true);
    }

    public function pagepegawaiadmin(Request $request)
    {
      return view('admin.pegawai.manajemenpegawai');
    }

    public function getadmin(){
      $table=adminpegawai::leftJoin('pegawais','adminpegawais.pegawai_id','=','pegawais.id')
                ->get();
      return $table;
    }

    public function cekpegawaiparams($id){
        $finger=DB::raw("(SELECT pegawai_id,COUNT(pegawai_id) as finger from fingerpegawais group by pegawai_id) as fingerpegawais");
        $tanpapegawai=hapusfingerpegawai::pluck('pegawai_id')->all();
        $adminsidikjari=adminpegawai::pluck('pegawai_id')->all();

        $table=pegawai::
        leftJoin($finger,'fingerpegawais.pegawai_id','=','pegawais.id')
        ->where('instansi_id','!=',null)
        ->where('id','=',$id)
        ->where('finger','=',2)
        ->whereNotIn('id',$tanpapegawai)
        ->whereNotIn('id',$adminsidikjari)
        ->get();


    //   $finger=DB::raw("(SELECT pegawai_id,COUNT(pegawai_id) as finger from fingerpegawais group by pegawai_id) as fingerpegawais");
    //    $table=pegawai::
    //    leftJoin($finger,'fingerpegawais.pegawai_id','=','pegawais.id')
    //    ->where('instansi_id','!=',null)
    //    ->where('id','=',$id)
    //    ->where('finger','>=',2)
    //    ->get();

    	return $table;
    }

    public function getadminparams($id){
      $table=adminpegawai::where('id','=',$id)
                ->get();
      return $table;
    }

    public function cari(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return response()->json([]);
        }
        $tags = pegawai::
                where('nip','LIKE','%'.$term.'%')
                ->orWhere('nama','LIKE','%'.$term.'%')
                ->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->nip." - ".$tag->nama];
        }
        return response()->json($formatted_tags);
    }

}
