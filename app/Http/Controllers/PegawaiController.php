<?php

namespace App\Http\Controllers;

use App\fingerpegawai;
use App\pegawai;
use App\instansi;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redis;
use Ixudra\Curl\Facades\Curl;
use App\Events\Timeline;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        return view('pegawai.manajpegawai',['inforekap'=>$inforekap]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function data(){
        $users=pegawai::with('instansi')->get();
        return Datatables::of($users)
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
        // $response=Curl::to('https://simpeg.kalselprov.go.id/api/identitas')
        //     // ->asJSON()
        //     ->get();
        //     // dd($response);
        // $jsons=json_decode($response);
        // dd($jsons);



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


        // Will dump a beauty json :3
        $jsons=(json_decode($result, true));

          //
          // dd(count($jsons));
       foreach ($jsons as $key=>$json)
       {

                   set_time_limit(0);
                   ini_set('memory_limit', '20000M');
           $pegawai=pegawai::where('nip','=',$json['nip'])
           ->count();

           if ($pegawai > 0){
             if (($json['kode_satker']==null) || ($json['kode_satker']=="0") || ($json['kode_unker']=="") || ($json['kode_unker']=="")){

             }
             else {

                   $response2=Curl::to('https://simpeg.kalselprov.go.id/api/satker?kode='.$json['kode_satker'])
                       ->get();
                      //  dd($response2);
                   $jsonssatker=json_decode($response2,true);

                  //  dd($jsonssatker);

                   if ($json['satker']==$json['unker'])
                   {
                       $instansi=instansi::where('kode','=',$json['kode_unker'])
                           ->get();
        //                dd($instansi[0]['kode']);
                       $user=pegawai::where('nip','=',$json['nip'])
                       ->first();
                      //  $user = new pegawai();
                       $user->nip = $json['nip'];
                       $user->nama = $json['nama'];
                       $user->jabatan = $json['jabatan'];
                       $user->jenispns = $json['golongan'];
                       $user->status_aktif="1";
                       $user->instansi_id = $instansi[0]['id'];
                       $user->save();
                   }
                   elseif (($json['satker']!=$json['unker']) && ($jsonssatker[0]['upt']==null) || ($jsonssatker[0]['upt']=="0"))
                   {
                     $instansi=instansi::where('kode','=',$json['kode_unker'])
                         ->get();
        //                dd($instansi[0]['kode']);
                     $user=pegawai::where('nip','=',$json['nip'])
                      ->first();
                    //  $user = new pegawai();
                     $user->nip = $json['nip'];
                     $user->nama = $json['nama'];
                     $user->jabatan = $json['jabatan'];
                     $user->jenispns = $json['golongan'];
                     $user->status_aktif="1";
                     $user->instansi_id = $instansi[0]['id'];
                     $user->save();
                   }
                   elseif (($json['satker']!=$json['unker']) && ($jsonssatker[0]['upt']=="1")) {
                     $instansi=instansi::where('kode','=',$json['kode_unker'])
                         ->get();
                     $user=pegawai::where('nip','=',$json['nip'])
                     ->first();
                    //  $user = new pegawai();
                     $user->nip = $json['nip'];
                     $user->nama = $json['nama'];
                     $user->jabatan = $json['jabatan'];
                     $user->jenispns = $json['golongan'];
                     $user->status_aktif="1";
                     $user->instansi_id = $instansi[0]['id'];
                     $user->save();
                   }
              }
           }
           else {

            // dd($json['nip']);
            if (($json['kode_satker']==null) || ($json['kode_satker']=="0") || ($json['kode_unker']=="") || ($json['kode_unker']=="")){

            }
            else {
              $response2=Curl::to('https://simpeg.kalselprov.go.id/api/satker?kode='.$json['kode_satker'])
                  ->get();
                 //  dd($response2);
              $jsonssatker=json_decode($response2,true);
              // dd($json['kode_satker']);
              if ($json['satker']==$json['unker'])
              {
                  $instansi=instansi::where('kode','=',$json['kode_unker'])
                      ->get();
   //                dd($instansi[0]['kode']);
                  $user = new pegawai();
                  $user->nip = $json['nip'];
                  $user->nama = $json['nama'];
                  $user->jabatan = $json['jabatan'];
                  $user->jenispns = $json['golongan'];
                  $user->status_aktif="1";
                  $user->instansi_id = $instansi[0]['id'];
                  $user->save();
              }
              elseif (($json['satker']!=$json['unker']) && ($jsonssatker[0]['upt']==null))
              {
                $instansi=instansi::where('kode','=',$json['kode_unker'])
                    ->get();
                  // dd($json['kode_unker']);
                $user = new pegawai();
                $user->nip = $json['nip'];
                $user->nama = $json['nama'];
                $user->jabatan = $json['jabatan'];
                $user->jenispns = $json['golongan'];
                $user->status_aktif="1";
                $user->instansi_id = $instansi[0]['id'];
                $user->save();
              }
              elseif (($json['satker']!=$json['unker']) && ($jsonssatker[0]['upt']=="1")) {
                $instansi=instansi::where('kode','=',$json['kode_unker'])
                    ->get();

                $user = new pegawai();
                $user->nip = $json['nip'];
                $user->nama = $json['nama'];
                $user->jabatan = $json['jabatan'];
                $user->jenispns = $json['golongan'];
                $user->status_aktif="1";
                $user->instansi_id = $instansi[0]['id'];
                $user->save();
              }
            }

          }


       }
       return $instansi;



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
    public function update(Request $request)
    {
        //

        $rules=array(
            'nama2'=>'required | min:3',
            'jabatan2'=>'required',
            'instansi2'=>'required',
        );

        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else {
            $updatedata = pegawai::find($request->idpegawai);
            $updatedata->nama = $request->nama2;
            $updatedata->instansi_id = $request->instansi2;
            $updatedata->jabatan = $request->jabatan2;
            $updatedata->save();

            return response()->json($updatedata);
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
        $updatedata = pegawai::find($request->delidpegawai);
        $updatedata->delete();
        return response()->json($updatedata);
    }

    public function cekpegawai(){
        $table=pegawai::all();
        return $table;
    }

    public function ambilfinger($id){
        $table=fingerpegawai::where('pegawai_id','=',$id)->get();
        return $table;
    }

    public function addfinger(Request $request){
        $user = new fingerpegawai();
        $user->pegawai_id = $request->json('pegawai_id');
        $user->size=$request->json('size');
        $user->valid=$request->json('valid');
        $user->templatefinger = $request->json('templatefinger');
        $user->save();
        return "Succes";
    }
}
