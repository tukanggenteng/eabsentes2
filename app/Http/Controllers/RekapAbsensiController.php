<?php

namespace App\Http\Controllers;

use App\jadwalkerja;
use App\jenisabsen;
use App\pegawai;
use App\att;
use App\rekapbulanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapAbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }

        if ($request->periode==""){
            $bulan2=date('Y-m');
        }
        else
        {
            $bulan2=$request->periode;
        }

//        dd($bulan2);

        $pegawai=pegawai::where('instansi_id','=',Auth::user()->instansi_id)->paginate(40);
        return view('rekapabsen.rekappegawai',['inforekap'=>$inforekap,'pegawais'=>$pegawai,'bulan'=>$bulan2]);
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
    public function show($id,$id2)
    {
        //
        $tanggal=explode('-',$id2);
        $atts=att::join('pegawais','atts.pegawai_id','=','pegawais.id')
        ->join('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->join('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->join('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->join('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->whereYear('atts.tanggal_att','=',$tanggal[0])
        ->whereMonth('atts.tanggal_att','=',$tanggal[1])
        ->where('atts.pegawai_id','=',$id)
        ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->paginate(31);
//        dd($atts);
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }
//        dd($tanggal[0]);
        $pegawai=pegawai::where('id','=',$id)->get();
        $jadwalkerjas=att::join('pegawais','atts.pegawai_id','=','pegawais.id')
            ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            ->whereYear('atts.tanggal_att','=',$tanggal[0])
            ->whereMonth('atts.tanggal_att','=',$tanggal[1])
            ->where('atts.pegawai_id','=',$id)
            ->distinct()
            ->select('atts.jadwalkerja_id','jadwalkerjas.jenis_jadwal')
            ->get();

        $jenisabsen=jenisabsen::all()->where('jenis_absen','!=','Hadir')
            // ->where('jenis_absen','!=','Rapat/Undangan')
            ;
        return view('rekapabsen.rekappegawaitrans',['jadwalkerjas'=>$jadwalkerjas,'inforekap'=>$inforekap,'atts'=>$atts,'jenisabsens'=>$jenisabsen,'bulan'=>$id2,'pegawai'=>$pegawai]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $this->validate($request, [
            'periode'=>'required',
            'checkbox'=>'required',
            'jenisabsen'=>'required'
        ]);

        $pegawaiid=$id;
        $hapusspasi=str_replace(" ","",$request->periode);
        $tanggal=explode("-",$hapusspasi);
        $hasil=date("d",strtotime($tanggal[0]));
        $hasil2=date("d",strtotime($tanggal[1]));

        $data=array();
        $i=0;

        for ($x = (int)$hasil; $x < (int)$hasil2; $x++)
        {
//            dd($x);
            foreach ($request->checkbox as $key=> $data) {

                $tanggalbaru = date("Y-m-d", (strtotime("+" . $i . "days", strtotime($tanggal[0]))));

                $atts = att::join('jadwalkerjas', 'atts.jadwalkerja_id', '=', 'jadwalkerjas.id')
                    ->where('tanggal_att', '=', $tanggalbaru)
                    ->where('pegawai_id', '=', $id)
                    ->where('jadwalkerja_id','=',$data)
                    ->get();

                foreach ($atts as $key => $att) {
//                    dd($att['jadwalkerja_id']);
                    $akumulasistandar = jadwalkerja::where('id', '=', $data)->get();

                    $jamawalakumulasi = strtotime($akumulasistandar[0]['jam_masuk']);
                    $jamakhirakumulasi = strtotime($akumulasistandar[0]['jam_keluar']);
                    $jamtoleransi = strtotime('00:30:00');
//                    dd(date("H:i",$jamtoleransi));
                    $jamstandar = date("H:i", $jamakhirakumulasi - $jamawalakumulasi - $jamtoleransi);
                    $jamstandar2 = date("H:i", $jamakhirakumulasi - $jamawalakumulasi);

                    if ($att['akumulasi_sehari'] <= $jamstandar) {

                        $search = att::where('tanggal_att', '=', $tanggalbaru)
                            ->where('pegawai_id', '=', $id)
//                        ->where('jenisabsen_id','!=','1')
                            ->count();

                        if ($search > 0) {

                            if ($request->jenisabsen == "2") {
                                $table = att::where('tanggal_att', '=', $tanggalbaru)
                                    ->where('pegawai_id', '=', $id)
                                    ->where('jadwalkerja_id','=',$att['jadwalkerja_id'])
                                    ->first();
                                $table->jenisabsen_id = $request->jenisabsen;
                                $table->jam_masuk = null;
                                $table->masukinstansi_id=Auth::user()->instansi_id;
                                $table->jam_keluar = null;
                                $table->keluarinstansi_id=Auth::user()->instansi_id;
                                $table->akumulasi_sehari = "00:00:00";
                                $table->save();
                            } elseif  ($request->jenisabsen=="3"){
                                $table = att::where('tanggal_att', '=', $tanggalbaru)
                                    ->where('jadwalkerja_id','=',$att['jadwalkerja_id'])
                                    ->where('pegawai_id', '=', $id)
                                    ->first();

                                $table->jenisabsen_id = $request->jenisabsen;
                                $table->jam_masuk = null;
                                $table->masukinstansi_id=Auth::user()->instansi_id;
                                $table->jam_keluar = null;
                                $table->keluarinstansi_id=Auth::user()->instansi_id;
                                $table->akumulasi_sehari = "00:00:00";
                                $table->save();
                            }
                            elseif  ($request->jenisabsen=="4"){
                                $table = att::where('tanggal_att', '=', $tanggalbaru)
                                    ->where('jadwalkerja_id','=',$att['jadwalkerja_id'])
                                    ->where('pegawai_id', '=', $id)
                                    ->first();

                                $table->jenisabsen_id = $request->jenisabsen;
                                $table->jam_masuk = null;
                                $table->masukinstansi_id=Auth::user()->instansi_id;
                                $table->jam_keluar = null;
                                $table->keluarinstansi_id=Auth::user()->instansi_id;
                                $table->akumulasi_sehari = "00:00:00";
                                $table->save();
                            }
                            elseif  ($request->jenisabsen=="5"){
                                $table = att::where('tanggal_att', '=', $tanggalbaru)
                                    ->where('jadwalkerja_id','=',$att['jadwalkerja_id'])
                                    ->where('pegawai_id', '=', $id)
                                    ->first();

                                $table->jenisabsen_id = $request->jenisabsen;
                                $table->jam_masuk = null;
                                $table->masukinstansi_id=Auth::user()->instansi_id;
                                $table->jam_keluar = null;
                                $table->keluarinstansi_id=Auth::user()->instansi_id;
                                $table->akumulasi_sehari = "00:00:00";
                                $table->save();
                            }
                            elseif  ($request->jenisabsen=="6"){
                                $jadwalkerja=jadwalkerja::where('id','=',$data)->get();

                                $table = att::where('tanggal_att', '=', $tanggalbaru)
                                    ->where('jadwalkerja_id','=',$att['jadwalkerja_id'])
                                    ->where('pegawai_id', '=', $id)
                                    ->first();

                                $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masuk'],$jadwalkerja[0]['jam_keluar']);

                                $table->jenisabsen_id = $request->jenisabsen;
                                $table->jam_masuk = null;
                                $table->masukinstansi_id=Auth::user()->instansi_id;
                                $table->jam_keluar = null;
                                $table->keluarinstansi_id=Auth::user()->instansi_id;
                                $table->akumulasi_sehari = $akumulasi;
                                $table->save();
                            }
                            elseif  ($request->jenisabsen=="7"){
                                $jadwalkerja=jadwalkerja::where('id','=',$data)->get();

                                $table = att::where('tanggal_att', '=', $tanggalbaru)
                                    ->where('jadwalkerja_id','=',$att['jadwalkerja_id'])
                                    ->where('pegawai_id', '=', $id)
                                    ->first();

                                $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$jadwalkerja[0]['jam_keluarjadwal']);

                                $table->jenisabsen_id = $request->jenisabsen;
                                $table->jam_masuk = null;
                                $table->masukinstansi_id=Auth::user()->instansi_id;
                                $table->jam_keluar = null;
                                $table->keluarinstansi_id=Auth::user()->instansi_id;
                                $table->akumulasi_sehari = $akumulasi;
                                $table->save();
                            }
                            elseif  ($request->jenisabsen=="8"){
                                $jadwalkerja=jadwalkerja::where('id','=',$data)->get();

                                $table = att::where('tanggal_att', '=', $tanggalbaru)
                                    ->where('jadwalkerja_id','=',$att['jadwalkerja_id'])
                                    ->where('pegawai_id', '=', $id)
                                    ->first();

                                $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$jadwalkerja[0]['jam_keluarjadwal']);

                                $table->jenisabsen_id = $request->jenisabsen;
                                $table->masukinstansi_id=Auth::user()->instansi_id;
                                $table->keluarinstansi_id=Auth::user()->instansi_id;
                                $table->akumulasi_sehari = $akumulasi;
                                $table->save();
                            }
                            elseif  ($request->jenisabsen=="9"){
                                $table = att::where('tanggal_att', '=', $tanggalbaru)
                                    ->where('jadwalkerja_id','=',$att['jadwalkerja_id'])
                                    ->where('pegawai_id', '=', $id)
                                    ->first();

                                $table->jenisabsen_id = $request->jenisabsen;
                                $table->jam_masuk = null;
                                $table->masukinstansi_id=Auth::user()->instansi_id;
                                $table->jam_keluar = null;
                                $table->keluarinstansi_id=Auth::user()->instansi_id;
                                $table->akumulasi_sehari = "00:00:00";
                                $table->save();
                            }

                            elseif  ($request->jenisabsen=="10"){

                                $jadwalkerja=jadwalkerja::where('id','=',$data)->get();

                                $table2 = att::where('tanggal_att', '=', $tanggalbaru)
                                    ->where('jadwalkerja_id','=',$att['jadwalkerja_id'])
                                    ->where('pegawai_id', '=', $id)
                                    ->get();

//                                dd($jadwalkerja[0]['jam_masukjadwal']);
                                $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$table2[0]['jam_keluar']);

                                $table = att::where('tanggal_att', '=', $tanggalbaru)
                                    ->where('jadwalkerja_id','=',$att['jadwalkerja_id'])
                                    ->where('pega  wai_id', '=', $id)
                                    ->first();

                                $table->jenisabsen_id = $request->jenisabsen;
                                $table->jam_masuk = $jadwalkerja[0]['jam_masukjadwal'];
                                $table->masukinstansi_id=Auth::user()->instansi_id;
                                $table->keluarinstansi_id=Auth::user()->instansi_id;
                                $table->akumulasi_sehari = $akumulasi;
                                $table->save();
                            }

                        } else {

                        }
                    } else {

                    }


                }
            }
                $i++;
        }
//        dd($x);
        return redirect()->back()->with('status','Atur ijin berhasil');
//        dd($data);
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

    }


}
