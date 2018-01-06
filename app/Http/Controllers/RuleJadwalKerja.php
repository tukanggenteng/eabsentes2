<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\jadwalkerja;
use App\rulejammasuk;
use Illuminate\Http\Request;

class RuleJadwalKerja extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
        $this->validate($request, [
            'jadwalkerjamasuk'=>'required',
            'awalmasuk'=>'required',
            'bataspulang'=>'required'
        ]);



        $hitung=rulejammasuk::where('jadwalkerja_id','=',$request->jadwalkerjamasuk)->count();

        if ($hitung>0)
        {

        }
        else{
            $user = new rulejammasuk();
            $user->jadwalkerja_id = $request->jadwalkerjamasuk[0];
            $user->jamsebelum_masukkerja = $request->awalmasuk;
            $user->jamsebelum_pulangkerja = $request->bataspulang;
            $user->instansi_id = $request->instansi_id;
            $user->save();
        }

        
        return redirect('/jadwalkerja');
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
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }
        $id=decrypt($id);
        $jadwalkerja=jadwalkerja::where('instansi_id',Auth::user()->instansi_id)->get();
        $rule=rulejammasuk::where('id','=',$id)->first();
//        dd($rule);
        return view('jadwalkerja.editrulejadwalkerja',['inforekap'=>$inforekap,'jadwalkerjas'=>$jadwalkerja,'rule'=>$rule]);
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
            'jadwalkerjamasuk'=>'required',
            'awalmasuk'=>'required',
            'bataspulang'=>'required'
        ]);

        $table=rulejammasuk::where('id','=',$request->id)->first();
        $table->jadwalkerja_id = $request->jadwalkerjamasuk;
        $table->jamsebelum_masukkerja = $request->awalmasuk;
        $table->jamsebelum_pulangkerja = $request->bataspulang;
        $table->instansi_id = $request->instansi_id;
        $table->save();
        return redirect('/jadwalkerja');
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
        $id=decrypt($id);
        $table=rulejammasuk::where('id','=',$id)->first();
        $table->delete();
        return redirect('/jadwalkerja');
    }
}
