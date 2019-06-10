<?php

namespace App\Http\Controllers;

use App\fingerpegawai;
use App\pegawai;
use App\queue_pegawai;
use App\macaddresse;
use App\lograspberry;
use App\instansi;
use App\atts_tran;
use App\dokter;
use App\perawatruangan;
use App\att;
use App\rulejammasuk;
use App\hapusfingerpegawai;
use App\adminpegawai;
use App\rulejadwalpegawai;
use App\Pegawai_Hari_Libur;
use App\sourceapiconfig;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redis;
use Ixudra\Curl\Facades\Curl;
use App\Events\Timeline;
use Excel;
use App\keterangan_absen;


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
        $this->middleware('throttle:1000,1');
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
                ->select('pegawais.*','instansis.namaInstansi')
                ->paginate(30);
        }
        else {

          $pegawais=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select('pegawais.*','instansis.namaInstansi')
                ->orWhere('pegawais.nip','like','%'.$request->cari.'%')
                ->orWhere('pegawais.nama','like','%'.$request->cari.'%')
                ->orWhere('instansis.namaInstansi','like','%'.$request->cari.'%')
                ->paginate(30);
        }

        $pegawaisearch=$request->cari;

        return view('pegawai.manajpegawai',['hitungs'=>$hitung,'inforekap'=>$inforekap,'pegawais'=>$pegawais,'pegawaisearch'=>$pegawaisearch]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function data(){
        $users=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->get();
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

                 set_time_limit(0);
                 ini_set('memory_limit', '20000M');

      $pegawai=pegawai::where('nip','=',$json['nip'])
      ->count();

      if ($pegawai > 0){
          // dd($json['nip']);
        //   array_push($yg2,$json['nip']);
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
            $updatedata->instansi_id = Auth::user()->instansi_id;
            $updatedata->save();

            $dataqueuepegawai=new QueuePegawaiController();
            $dataqueuepegawai->storequeuepegawaispesific(Auth::user()->instansi_id,$updatedata->id,"daftar");




            return response()->json($updatedata);
        }

        // $this->validate($request, [
        //   'nama2'=>'required | min:3',
        //   'jabatan2'=>'required',
        //   'instansi2'=>'required',
        // ]);
    }


    public function datauser(){
        $finger=DB::raw("(SELECT pegawai_id,COUNT(pegawai_id) as finger from fingerpegawais GROUP BY pegawai_id) as fingerpegawais");
        $users=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
              ->leftJoin($finger,'fingerpegawais.pegawai_id','=','pegawais.id')
              ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
              ->select('pegawais.*','instansis.namaInstansi','fingerpegawais.*')
              ->get();
        return Datatables::of($users)
              ->addColumn('action', function ($users) {
                  return '<button type="button" class="modal_delete btn btn-danger btn-sm" data-toggle="modal" data-nip="'.encrypt($users->nip).'" data-jabatan="'.$users->jabatan.'" data-instansi="'.$users->instansi_id.'" data-nama="'.$users->nama.'" data-id="'.encrypt($users->id).'" data-target="#modal_delete">Hapus</button>';
              })
            ->make(true);
    }


    public function validasipegawaiAdm($id){

      $pegawai=pegawai::where('nip','=',$id)
      ->count();
      if ($pegawai!=0){
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

    public function validasipegawai($id){
      $pegawai=pegawai::where('nip','=',$id)
      ->where('instansi_id','!=',null)
      ->count();
      $pegawai2=pegawai::where('nip','=',$id)
      ->count();
      if (($pegawai>0) && ($pegawai2>0)){
        $ambilpegawai=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')->where('nip','=',$id)->first();
        $seluruh['status']="1";
        $seluruh['nip']=$id;
        $seluruh['namaInstansi']=$ambilpegawai->namaInstansi;
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

    //API & MANTRA --------------------------------------------------------------------------------------------------------

    public function apimantra($id)
    {
      //generate from mantra-----------------------
      $url="http://mantra.kalselprov.go.id/diskominfo_kalsel/simpeg/tampilDataPegawaiFromMantra";
      $accesskey="m0a1gcgyjd69g005c9jwqblpqtcoxe8cwp5pvpuzefbyfhxy2tbpj3dixckq0lmn"; //Kunci akses diperoleh dari permohonan akses requester
      $pardata=array();

      $pardata["q_nip"]=urlencode($id);
      $pardata["q_jumlahbaris"]=urlencode("");
      $pardata["q_awalbaris"]=urlencode("");
      $par="?".http_build_query($pardata);

      $options=array(
      	'http'=>array(
      		'ignore_errors'=>true,
      		'method'=>"GET",
      		'header'=>implode("\r\n",[
      			"Content-Type:application/x-www-form-urlencoded",
      			"Accept:application/json",
      			"Accept-Charset:UTF-8",
      			"AccessKey:$accesskey"
      		])
      	)
      );
      $context=stream_context_create($options);
      $content=file_get_contents($url.$par,false,$context);

      //END.generate from mantra-----------------------
      $decode_l1 = json_decode($content);
      //dd($decode_l1);

      if(empty($decode_l1->response)) { $nama=""; }
      else { $nama = $decode_l1->response->data->tampilDataPegawaiFromMantra[0]->nama; }

      if($nama!="")
      {
        $seluruh['status']="0";
        $seluruh['nama']=$nama;
        return $seluruh;
      }

      else
      {
        $seluruh['status']="3";
        return $seluruh;
      }

    }

    public function apimantrasimpeg($id)
    {
      //generate from mantra-----------------------
      $url="http://mantra.kalselprov.go.id/diskominfo_kalsel/simpeg/tampilDataPegawai";
      $accesskey="7sv6uqivb6ilao084n2783drp8u8jy8zj8ym7oe8bylulmfu3dm5501gu5i1pohb"; //Kunci akses diperoleh dari permohonan akses requester
      $pardata=array();

      $pardata["q_nip"]="%201903%";
      $pardata["q_jumlahbaris"]="";
      $pardata["q_awalbaris"]="";
      $par=json_encode($pardata,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

      $options=array(
      	'http'=>array(
      		'ignore_errors'=>true,
      		'method'=>"GET",
      		'header'=>implode("\r\n",[
      			"Content-Type:application/json",
      			"Accept:application/json",
      			"Accept-Charset:UTF-8",
      			"AccessKey:$accesskey"
      		]),
      		'content'=>$par
      	)
      );
      $context=stream_context_create($options);
      $content=file_get_contents($url,false,$context);
      //END.generate from mantra-----------------------
      $decode_l1 = json_decode($content);
      dd($decode_l1);
      //Get Variable from Object
      //use DD if must;
      //dd($decode_l1);
      //echo $decode_l1->response->data->tampilDataPegawai[0]->nama;

      //Trying to get property 'response' of non-object
      if(empty($decode_l1->response)) { $nama=""; }
      else { $nama = $decode_l1->response->data->tampilDataPegawai[0]->nama; }

      if($nama!="")
      {
        $seluruh['status']="0";
        $seluruh['nama']=$nama;
        return $seluruh;
      }

      else
      {
        $seluruh['status']="3";
        return $seluruh;
      }

    }

    public function simpeg($id)
    {
      set_time_limit(0);
      $data = json_decode(file_get_contents('https://simpeg.kalselprov.go.id/api/identitas'));;

      $nip = $id;
      $count = count($data); //membandingkan data di simpeg dengan menghitung jumlah data

      for($i=0;$i<$count;$i++)
      {

        if($nip==$data[$i]->nip)
        {
          dd($data[$i]->nip);
          break;
          $seluruh['status']="0";
          $seluruh['nama']=$data[$i]->nama;
          return $seluruh;
        }

        else
        {
          break;
          $seluruh['status']="3";
          return $seluruh;
        }
      }
    }

    public function simpanDataApi(Request $request)
    {
        $rules=array(
            'nama'=>'required | min:3',
            'nip'=>'required'
        );

        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else {

            $cekdata = pegawai::where('nip','=',$request->nip)->first();
            if($cekdata!="")
            {
              $updatedata = new pegawai();
              $updatedata->error = "Sudah ada data pegawai yang sama";
            }
            else
            {
              $updatedata = new pegawai();
              $updatedata->nip = $request->nip;
              $updatedata->nama = $request->nama;
              $updatedata->save();
            }

            return response()->json($updatedata);
        }
    }

    public function simpanConfigApi(Request $request)
    {
      $rules=array(
          'id'=>'required | max:1'
      );

      //neutralize config
      $updateSourceApi = sourceapiconfig::where('id',1)->update(['active' => 0]);
      $updateSourceApi = sourceapiconfig::where('id',2)->update(['active' => 0]);
      $updateSourceApi = sourceapiconfig::where('id',3)->update(['active' => 0]);

      //update
      $updateSourceApi = sourceapiconfig::where('id',$request->id)->update(['active' => 1]);

      return redirect('/pegawai/import')->with('berhasil', 'Berhasil menyimpan Konfigurasi API.');
    }

    //--------------------------------------------------------------------------------------------------------------------------------
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $deletepegawai=decrypt($request->delidpegawai);
        // dd($deletepegawai);
        $updatedata = pegawai::where('id','=',$deletepegawai)->first();
        $idpeg=$updatedata->id;
        // dd($updatedata->id);

        $rulepegawais=rulejadwalpegawai::where('pegawai_id','=',$updatedata->id)->get();
        // dd($rulepegawais);
        foreach ($rulepegawais as $key => $datarule)
        {

            $tanggalhari=date('Y-m-d');
            $atts=att::where('jadwalkerja_id','=',$datarule->jadwalkerja_id)
                            ->where('tanggal_att','=',$tanggalhari)
                            ->where('pegawai_id','=',$updatedata->id)
                            ->whereNull('jam_masuk');
            // dd($atts->count());
            if ($atts->count() > 0){
                $attsdelete=att::where('jadwalkerja_id','=',$datarule->jadwalkerja_id)
                                ->where('tanggal_att','=',$tanggalhari)
                                ->where('pegawai_id','=',$updatedata->id)
                                ->whereNull('jam_masuk')->delete();
                // dd($attsdelete);



            }
            $deleteatts=att::where('jadwalkerja_id','=',$datarule->jadwalkerja_id)
                ->where('tanggal_att','>',$tanggalhari)
                ->where('pegawai_id','=',$updatedata->pegawai_id)->delete();
            $keteranganabsendelete=keterangan_absen::where('jadwalkerja_id','=',$datarule->jadwalkerja_id)
                            ->where('pegawai_id','=',$updatedata->pegawai_id)->delete();
        }

        $deleterulepegawai=rulejadwalpegawai::where('pegawai_id','=',$updatedata->id);
        $deleterulepegawai->delete();

        $tanggalnow=date("Y-m-d");

        $countperawat=perawatruangan::where('pegawai_id','=',$idpeg)->count();

        if ($countperawat>0){
            $hapusperawat=perawatruangan::where('pegawai_id','=',$idpeg)->first();
            $hapusperawat->delete();
        }

        $countdokter=dokter::where('pegawai_id','=',$idpeg)->count();
        if ($countdokter>0){
            $hapusdokter=dokter::where('pegawai_id','=',$idpeg)->first();
            $hapusdokter->delete();
        }


        // $hapusattstrans=atts_tran::where('pegawai_id','=',$updatedata->id)
        // ->where('tanggal','=',$tanggalnow);
        // $hapusattstrans->delete();

        // $hapusatts=att::where('pegawai_id','=',$updatedata->id)
        // ->where('tanggal_att','=',$tanggalnow);
        // $hapusatts->delete();
        $dataqueuepegawai=new QueuePegawaiController();
        ($dataqueuepegawai->storequeuepegawaispesific($updatedata->instansi_id,$idpeg,"hapus"));

        $tablepegawaiharilibur=Pegawai_Hari_Libur::where('pegawai_id','=',$idpeg)->first();
        if ($tablepegawaiharilibur!=null)
        {
            $tablepegawaiharilibur->delete();
        }


        $updatedata->instansi_id = null;

        if ($updatedata->save()){

            return response()->json("Success");
        }else{
            return response()->json("Failed");
        }

        // return response()->json($updatedata);
    }

    public function cekpegawai($id){

            $finger=DB::raw("(SELECT pegawai_id,COUNT(pegawai_id) as finger from fingerpegawais group by pegawai_id) as fingerpegawais");
            $tanpapegawai=hapusfingerpegawai::pluck('pegawai_id')->all();
            $adminsidikjari=adminpegawai::pluck('pegawai_id')->all();

            $table=pegawai::
            leftJoin($finger,'fingerpegawais.pegawai_id','=','pegawais.id')
            // ->where('instansi_id','!=',null)
            ->where('instansi_id','=',$id)
            ->where('finger','=',2)
            ->whereNotIn('id',$tanpapegawai)
            ->whereNotIn('id',$adminsidikjari)
            ->get();

            return $table;


       }

       public function cekpegawai2(){

        $finger=DB::raw("(SELECT pegawai_id,COUNT(pegawai_id) as finger from fingerpegawais group by pegawai_id) as fingerpegawais");
            $tanpapegawai=hapusfingerpegawai::pluck('pegawai_id')->all();
            $adminsidikjari=adminpegawai::pluck('pegawai_id')->all();

            $table=pegawai::
            leftJoin($finger,'fingerpegawais.pegawai_id','=','pegawais.id')
            ->where('instansi_id','!=',null)
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

            $hitung=fingerpegawai::where('pegawai_id','=',$request->json('pegawai_id'))
                                                                  ->count();
            if ($hitung == 2)
            {
              $datapegawai=pegawai::where('id','=',$request->json('pegawai_id'))->first();
            //   $dataqueuepegawai=new QueuePegawaiController();
            //   $dataqueuepegawai->storequeuepegawaispesific($datapegawai->instansi_id,$datapegawai->id,"daftar");
            }
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

    public function indexuploadexcel(){
        $datas=instansi::all();
        $sumbers=sourceapiconfig::all();

        //variable untuk testing config source API
        //$sumber='/pegawai/cekAdm/'; // tes cek from eabsen database
        //$sumber='/pegawai/apimantra/'; // tes cek from Mantra Simpeg
        //$sumber='/pegawai/apimantrasimpeg/'; // tes cek from Mantra <- Simpeg
        //$sumber='/pegawai/simpeg/'; // tes cek from direct Simpeg

        return view('pegawai.importpegawai',['datas'=>$datas, 'sumbers'=>$sumbers]);
    }

    public function uploadpegawaiExcel(Request $request){
        $this->validate($request,[
            'file'=>'required',
            'instansi_id'=>'required'
        ]);

        $input=$request->all();
        $file=$request->file;
        // dd($input);
        $path=Input::file('file');
        // dd($path);
        $data=Excel::load($file,function ($reader){
        })->get();

        if (!empty($data) && $data->count()){
            $datap="";
            foreach ($data as $key){
                $nip=(string)$key->nip;

                $nip=str_replace(" ","",$nip);
                // dd($nip);
                if (!empty($key->nip))
                {
                    $table=pegawai::where('nip','=',$nip);
                    if ($table->count()>0)
                    {
                        $table=pegawai::where('nip','=',$nip)->first();
                        $table->instansi_id=$request->instansi_id;
                        $table->save();


                    }
                    else{
                        // $data=$datap.$nip."<br>";
                        // return redirect()->back()->with('error','NIP Pegawai tidak ada ! NIP '.$nip.'.');
                    }
                }
                else
                {
                    return redirect()->back()->with('error','NIP Pegawai tidak ada ! NIP '.$nip.'.');
                }


            }
            return redirect()->back()->with('berhasil','Pegawai berhasil di import !');
        }
        else{
            return redirect()->back()->with('error','Pegawai tidak berhasil di import !');
        }

    }






}
