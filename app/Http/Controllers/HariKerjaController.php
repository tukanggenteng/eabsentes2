<?php

namespace App\Http\Controllers;

use App\harikerja;
use App\jadwalkerja;
use App\jadwalminggu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HariKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        //
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }

        $jadwalkerjas=array();
        $isi=array();
        $tables=jadwalkerja::where('instansi_id','=',Auth::user()->instansi_id)
                ->orWhere('instansi_id','=','1')
                ->get();
        // dd($tables);

        foreach ($tables as $table){

            $harikerja=harikerja::where('instansi_id','=',Auth::user()->instansi_id)
            ->where('jadwalkerja_id','=',$table->id)->count();
            if ($harikerja == 0) {
                $isi['id']=($table->id);
                $isi['jenis_jadwal']=($table->jenis_jadwal);
                $isi['jam_masukjadwal']=$table->jam_masukjadwal;
                $isi['jam_keluarjadwal']=$table->jam_keluarjadwal;
                array_push($jadwalkerjas,$isi);
            }
        }

        dd($jadwalkerjas);

        $harikerja=harikerja::join('jadwalkerjas','harikerjas.jadwalkerja_id','=','jadwalkerjas.id')
            ->select('harikerjas.jadwalkerja_id','jadwalkerjas.jenis_jadwal')
            ->where('harikerjas.instansi_id','=',Auth::user()->instansi_id)
            ->distinct()
            ->get();
        $data=array();
        $subdata=array();
        $hasildata=array();
        $jadwalkerjadata=array();
        $kata="";
        foreach ($harikerja as $hasil){
            $harikerjas=harikerja::where('jadwalkerja_id','=',$hasil->jadwalkerja_id)
            ->where('instansi_id','=',Auth::user()->instansi_id)->get();
            foreach ($harikerjas as $key => $value){
                $data['hari']=($value->hari);
                array_push($hasildata,$data);
            }
            $subdata['jenis_jadwal']=$hasil->jenis_jadwal;
            $subdata['jadwalkerja_id']=$hasil->jadwalkerja_id;
            array_push($subdata,$hasildata);
            array_push($jadwalkerjadata,$subdata);
            $data="";
            $subdata="";
            $hasildata="";
            $data=array();
            $hasildata=array();
            $subdata=array();
        }
        // dd($jadwalkerjadata);


        $jadwalkerjas2=jadwalkerja::where('instansi_id','=',Auth::user()->instansi_id)
                        ->orWhere('instansi_id','=','1')
                        ->get();
        // dd($jadwalkerjas2);
        $jadwalminggu=array();
        $isiminggu=array();
        foreach ($jadwalkerjas2 as $key2 => $jadwalkerja2){
            $harikerja=jadwalminggu::where('instansi_id','=',Auth::user()->instansi_id)
            ->where('jadwalkerja_id','=',$jadwalkerja2->id)->count();
            if ($harikerja == 0) {
                $isiminggu['id']=($jadwalkerja2->id);
                $isiminggu['jenis_jadwal']=($jadwalkerja2->jenis_jadwal);
                $isiminggu['jam_masukjadwal']=$jadwalkerja2->jam_masukjadwal;
                $isiminggu['jam_keluarjadwal']=$jadwalkerja2->jam_keluarjadwal;
                array_push($jadwalminggu,$isiminggu);
            }
        }


        $minggukerja=jadwalminggu::join('jadwalkerjas','jadwalminggus.jadwalkerja_id','=','jadwalkerjas.id')
            ->select('jadwalminggus.jadwalkerja_id','jadwalkerjas.jenis_jadwal')
            ->where('jadwalminggus.instansi_id','=',Auth::user()->instansi_id)
            ->distinct()
            ->get();

        $data2=array();
        $subdata2=array();
        $hasildata2=array();
        $jadwalkerjadata2=array();
        $kata2="";
        foreach ($minggukerja as $hasil2){
            $harikerjas2=jadwalminggu::where('jadwalkerja_id','=',$hasil2->jadwalkerja_id)
            ->where('instansi_id','=',Auth::user()->instansi_id)->get();
            // dd($harikerjas2);
            foreach ($harikerjas2 as $key => $value2){
                $data2['minggu']=($value2->minggu);
                array_push($hasildata2,$data2);
            }
            $subdata2['jenis_jadwal']=$hasil2->jenis_jadwal;
            $subdata2['jadwalkerja_id']=$hasil2->jadwalkerja_id;
            array_push($subdata2,$hasildata2);
            array_push($jadwalkerjadata2,$subdata2);
            $data2="";
            $subdata2="";
            $hasildata2="";
            $data2=array();
            $hasildata2=array();
            $subdata2=array();
        }

        // dd($jadwalminggu);

        return view('jadwalkerja.harijadwalkerja',['inforekap'=>$inforekap,'jadwalminggus'=>$jadwalminggu,'jadwalminggudatas'=>$jadwalkerjadata2,'jadwalkerjas'=>$jadwalkerjas,'hasildatas'=>$jadwalkerjadata]);
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
        $this->validate($request, [
            'jadwalkerjamasuk'=>'required',
            'checkbox'=>'required'
        ]);
            //dd($request->checkbox);

        foreach ($request->checkbox as $key=> $data){
        //    dd($data);
            $user = new harikerja();
            $user->hari = $data;
            $user->jadwalkerja_id = $request->jadwalkerjamasuk;
            $user->instansi_id = Auth::user()->instansi_id;
            $user->save();
        }
        return redirect('/harikerja');
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
       //dd($id);
        $id=decrypt($id);
        $table=harikerja::where('jadwalkerja_id',$id)
        ->where('instansi_id','=',Auth::user()->instansi_id)
        ;
        //dd($table);
        $table->delete();
        return redirect('/harikerja');
    }
}
