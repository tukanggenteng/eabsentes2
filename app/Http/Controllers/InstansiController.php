<?php

namespace App\Http\Controllers;

use App\instansi;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Yajra\Datatables\Facades\Datatables;

class InstansiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('instansi.manajinstansi');
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

    public function data(){
        $instansi=instansi::all();
        return Datatables::of($instansi)
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // $url1='simpeg.kalselprov.go.id/api/unker';
      // $json = file_get_contents($url1);
      //
      // // deserialize data from JSON
      // $contoh = json_decode($json,true);
      // dd($contoh);

        $response=Curl::to('https://simpeg.kalselprov.go.id/api/unker')
            ->get();
            // dd($response);
        $jsons=json_decode($response,true);

        foreach ($jsons as $key=>$json)
        {
            $cari=instansi::where('kode','=',$json['kode_unker'])
                ->count();
            if (($cari>0)){
                $instansi=instansi::where('kode','=',$json['kode_unker'])
                    ->first();
                $instansi->namaInstansi=$json['unker'];
                $instansi->save();
            }
            else{
                $instansi= new instansi();
                $instansi->kode=$json['kode_unker'];
                $instansi->namaInstansi=$json['unker'];
                $instansi->save();
            }
        }

        $response2=Curl::to('https://simpeg.kalselprov.go.id/api/satker')
            ->get();
            // dd($response);
        $jsons2=json_decode($response2,true);
        
        //
        
        // $instansis=instansi::all();

        // $array_all=array_push($jsons2,$jsons);

        // foreach ($instansis as $key => $instansi) {
            
        //     if (array_search($instansi->kode,$array_all,true))
        //     {
        //         echo "ketemu <br>";
        //     }
        //     else
        //     {
        //         echo "tidak terdaftar <br>";
        //     }
        // }

        foreach ($jsons2 as $key=>$json2)
        {
          if($json2["upt"]=="1")
          {
            $cari2=instansi::where('kode','=',$json2['kode_satker'])
                ->count();
            if (($cari2>0)){
                $instansi2=instansi::where('kode','=',$json2['kode_satker'])
                    ->first();
                $instansi->namaInstansi=$json2['satker'];
                $instansi->save();
            }
            else{
                $instansi= new instansi();
                $instansi->kode=$json2['kode_satker'];
                $instansi->namaInstansi=$json2['satker'];
                $instansi->save();
            }
          }

        }

        return response()->json($instansi);
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


    public function cari(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return response()->json([]);
        }
        $tags = instansi::where('namaInstansi','LIKE','%'.$term.'%')->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->namaInstansi];
        }
        return response()->json($formatted_tags);
    }
}
