<?php

namespace App\Http\Controllers;
use App\att;
use App\jadwalkerja;
use App\pegawai;
use App\rulejadwalpegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalKerjaPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
//        dd($request->table_search);

        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }
//        dd($inforekap);
        $tanggalsekarang=date("Y-m-d");
//        dd($tanggalsekarang);
        if ((isset($request->table_search2)) && (isset($request->table_search)) )
        {
            $rulejadwal2=rulejadwalpegawai::join('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                ->where('jadwalkerjas.instansi_id','=',Auth::user()->instansi_id)
                //->where('tanggal_awalrule','<=',$tanggalsekarang)
                //->where('tanggal_akhirrule','>=',$tanggalsekarang)
                ->where('jadwalkerjas.jenis_jadwal','like','%'.$request->table_search2.'%')
                ->orWhere('pegawais.nip','like','%'.$request->table_search2.'%')
                ->orWhere('pegawais.nama','like','%'.$request->table_search2.'%')
                ->orWhere('rulejadwalpegawais.tanggal_awalrule','like','%'.$request->table_search2.'%')
                ->orWhere('rulejadwalpegawais.tanggal_akhirrule','like','%'.$request->table_search2.'%')
                ->select('rulejadwalpegawais.id','pegawais.nip','pegawais.nama','jadwalkerjas.jenis_jadwal','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule')
                ->paginate(30);
            $rulejadwal=pegawai::where('instansi_id','=',Auth::user()->instansi_id)
            ->where('nip','like','%'.$request->table_search.'%')
            ->orWhere('nama','like','%'.$request->table_search.'%')
            ->paginate(30);
            $jadwalkerja=jadwalkerja::where('instansi_id',Auth::user()->instansi_id)->get();
            $cari=$request->table_search;
            $cari2=$request->table_search2;
            return view('jadwalkerjapegawai.jadwalkerjapegawai',['inforekap'=>$inforekap,'jadwalkerjas'=>$jadwalkerja,'rulejadwals'=>$rulejadwal,'rulejadwals2'=>$rulejadwal2,'cari'=>$request->table_search,'cari2'=>$request->table_search2]);
        }
        if ((isset($request->table_search2)) && (!isset($request->table_search)) )
        {
            $rulejadwal2=rulejadwalpegawai::join('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                ->where('jadwalkerjas.instansi_id','=',Auth::user()->instansi_id)
                //->where('tanggal_awalrule','<=',$tanggalsekarang)
                //->where('tanggal_akhirrule','>=',$tanggalsekarang)
                ->where('jadwalkerjas.jenis_jadwal','like','%'.$request->table_search2.'%')
                ->orWhere('pegawais.nip','like','%'.$request->table_search2.'%')
                ->orWhere('pegawais.nama','like','%'.$request->table_search2.'%')
                ->orWhere('rulejadwalpegawais.tanggal_awalrule','like','%'.$request->table_search2.'%')
                ->orWhere('rulejadwalpegawais.tanggal_akhirrule','like','%'.$request->table_search2.'%')
                ->select('rulejadwalpegawais.id','pegawais.nip','pegawais.nama','jadwalkerjas.jenis_jadwal','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule')
                ->paginate(30);
            $rulejadwal=pegawai::where('instansi_id',Auth::user()->instansi_id)->paginate(30);
            $jadwalkerja=jadwalkerja::where('instansi_id',Auth::user()->instansi_id)->get();
            $cari=$request->table_search;
            return view('jadwalkerjapegawai.jadwalkerjapegawai',['inforekap'=>$inforekap,'jadwalkerjas'=>$jadwalkerja,'rulejadwals'=>$rulejadwal,'rulejadwals2'=>$rulejadwal2,'cari'=>$request->table_search,'cari2'=>$request->table_search2]);
        }
        if (isset($request->table_search)){
            $rulejadwal=pegawai::where('instansi_id','=',Auth::user()->instansi_id)
                ->where('nip','like','%'.$request->table_search.'%')
                ->orWhere('nama','like','%'.$request->table_search.'%')
                ->paginate(30);
            $rulejadwal2=rulejadwalpegawai::join('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                //->where('tanggal_awalrule','<=',$tanggalsekarang)
                //->where('tanggal_akhirrule','>=',$tanggalsekarang)
                ->where('jadwalkerjas.instansi_id','=',Auth::user()->instansi_id)
                ->select('rulejadwalpegawais.id','pegawais.nip','pegawais.nama','jadwalkerjas.jenis_jadwal','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule')
                ->paginate(30);
            $jadwalkerja=jadwalkerja::where('instansi_id',Auth::user()->instansi_id)->get();
            $cari=$request->table_search;
            return view('jadwalkerjapegawai.jadwalkerjapegawai',['inforekap'=>$inforekap,'jadwalkerjas'=>$jadwalkerja,'rulejadwals'=>$rulejadwal,'rulejadwals2'=>$rulejadwal2,'cari'=>$request->table_search,'cari2'=>$request->table_search2]);
        }
        else{
            $rulejadwal2=rulejadwalpegawai::join('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                ->where('jadwalkerjas.instansi_id','=',Auth::user()->instansi_id)
                //->where('tanggal_awalrule','<=',$tanggalsekarang)
                //->where('tanggal_akhirrule','>=',$tanggalsekarang)
                ->select('rulejadwalpegawais.id','pegawais.nip','pegawais.nama','jadwalkerjas.jenis_jadwal','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule')
                ->paginate(30);
//            dd($rulejadwal2);
            $rulejadwal=pegawai::where('instansi_id',Auth::user()->instansi_id)->paginate(30);
            $jadwalkerja=jadwalkerja::where('instansi_id',Auth::user()->instansi_id)->get();
//            dd($rulejadwal);
            return view('jadwalkerjapegawai.jadwalkerjapegawai',['inforekap'=>$inforekap,'jadwalkerjas'=>$jadwalkerja,'rulejadwals2'=>$rulejadwal2,'rulejadwals'=>$rulejadwal,'cari'=>$request->table_search,'cari2'=>$request->table_search2]);
        }

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
        $this->validate($request, [
            'checkbox'=>'required',
            'jadwalkerjamasuk'=>'required'
        ]);


        foreach ($request->checkbox as $data){
            $hapusspasi=str_replace(" ","",$request->daterange);
            $tanggal=explode("-",$hapusspasi);

            $tanggalhariini=date("Y-m-d");

//            dd($tanggal[0]."+".$tanggal[1]);

            $tanggalawal=date("Y-m-d",strtotime($tanggal[0]));
            $tanggalakhir=date("Y-m-d",strtotime($tanggal[1]));

//            dd($tanggalakhir."+".$tanggalawal);

            $verifikasi=rulejadwalpegawai::where('tanggal_awalrule','<=',$tanggalawal)
                ->where('tanggal_akhirrule','>=',$tanggalakhir)
                ->where('pegawai_id','=',$data)
                ->where('jadwalkerja_id','=',$request->jadwalkerjamasuk)
                ->count();

//            dd($verifikasi);

            if ($verifikasi>0) {
                return redirect('/jadwalkerjapegawai')->with('err','Terdapat data jadwal pegawai lebih dari 2 kali pada hari yang sama.');
            }
            else
            {
                if (($tanggalhariini <= $tanggal[0]) && ($tanggalhariini <= $tanggal[1])) {
                    $cek = att::where('tanggal_att', '=', $tanggalhariini)
                        ->where('pegawai_id','=',$data)
                        ->where('jadwalkerja_id', '=', $request->jadwalkerjamasuk)
                        ->count();
                    if ($cek == 0) {
                        $table = new att();
                        $table->pegawai_id = $data;
                        $table->jadwalkerja_id = $request->jadwalkerjamasuk;
                        $table->tanggal_att = $tanggalhariini;
                        $table->terlambat="00:00:00";
                        $table->akumulasi_sehari="00:00:00";
                        $table->jenisabsen_id = '2';
                        $table->save();
                    }
                }

                $table = new rulejadwalpegawai();
                $table->pegawai_id = $data;
                $table->tanggal_awalrule = $tanggal[0];
                $table->tanggal_akhirrule = $tanggal[1];
                $table->jadwalkerja_id = $request->jadwalkerjamasuk;
                $table->save();
            }
        }
        return redirect('/jadwalkerjapegawai');
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
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }

        $id=decrypt($id);
        $rulejadwal2=rulejadwalpegawai::join('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
            ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
            ->select('rulejadwalpegawais.id','rulejadwalpegawais.jadwalkerja_id','pegawais.nip','pegawais.nama','jadwalkerjas.jenis_jadwal','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule')
            ->where('rulejadwalpegawais.id','=',$id)
            ->get();

        $jadwalkerja=jadwalkerja::where('instansi_id',Auth::user()->instansi_id)->get();
        return view('jadwalkerjapegawai.editjadwalkerjapegawai',['inforekap'=>$inforekap,'rulejadwals'=>$rulejadwal2,'jadwalkerjas'=>$jadwalkerja]);
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
//        dd($id);
        $this->validate($request, [
            'jadwalkerjamasuk'=>'required'
        ]);
        $hapusspasi=str_replace(" ","",$request->daterange);
        $tanggal=explode("-",$hapusspasi);

        $table=rulejadwalpegawai::where('id','=',$request->id)->first();
        $table->jadwalkerja_id=$request->jadwalkerjamasuk;
        $table->tanggal_awalrule=$tanggal[0];
        $table->tanggal_akhirrule=$tanggal[1];
        $table->save();

        return redirect('/jadwalkerjapegawai');
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
//        dd($id);
        $id=decrypt($id);
        $table=rulejadwalpegawai::find($id);
        $table->delete();
        return redirect('/jadwalkerjapegawai');
    }
}
