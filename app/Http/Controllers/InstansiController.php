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
