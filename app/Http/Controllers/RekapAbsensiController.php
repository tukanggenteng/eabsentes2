<?php

namespace App\Http\Controllers;

use App\jadwalkerja;
use App\jenisabsen;
use App\pegawai;
use App\att;
use App\rekapbulanan;
use App\masterbulanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;

class RekapAbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }

//        dd($bulan2);

        // $pegawai=pegawai::where('instansi_id','=',Auth::user()->instansi_id)->paginate(40);
        // return view('rekapabsen.rekappegawai',['inforekap'=>$inforekap,'pegawais'=>$pegawai,'bulan'=>$bulan2]);

        $date=date('N');

        $sekarang=date('Y-m-d');
        $status=false;
        if ($date==1){
            $hari='Senin';
            $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
            $akhir=date("Y-m-d",strtotime("-1 days",strtotime($sekarang)));
            $status=true;
        }
        elseif ($date==2){
            $hari='Selasa';
            $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
            $akhir=date("Y-m-d",strtotime("-1 days",strtotime($sekarang)));
            $status=true;
        }
        elseif ($date==3) {
          // dd("asda");
          $hari='Rabu';
          $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
          $akhir=date("Y-m-d",strtotime("-1 days",strtotime($sekarang)));
          $status=false;
        }
        elseif ($date==4) {
          // dd("asda");
          $hari='Kamis';
          $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
          $akhir=date("Y-m-d",strtotime("-1 days",strtotime($sekarang)));
          $status=false;
        }
        elseif ($date==5) {
          // dd("asda");
          $hari='Jumat';
          $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
          $akhir=date("Y-m-d",strtotime("-1 days",strtotime($sekarang)));
          $status=false;
        }
        elseif ($date==6) {
          // dd("asda");
          $hari='Sabtu';
          $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
          $akhir=date("Y-m-d",strtotime("-1 days",strtotime($sekarang)));
          $status=false;
        }
        elseif ($date==7) {
          // dd("asda");
          $hari='Minggu';
          $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
          $akhir=date("Y-m-d",strtotime("-1 days",strtotime($sekarang)));
          $status=false;
        }

        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->where('atts.tanggal_att','>=',$awal)
        ->where('atts.tanggal_att','<=',$akhir)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('atts.*','jadwalkerjas.jenis_jadwal','pegawais.id as idpegawai','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->paginate(30);


        $jadwalkerjas=att::join('pegawais','atts.pegawai_id','=','pegawais.id')
            ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            ->where('atts.tanggal_att','>=',$awal)
            ->where('atts.tanggal_att','<=',$akhir)
            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
            ->distinct()
            ->select('atts.jadwalkerja_id','jadwalkerjas.jenis_jadwal')
            ->get();
        // dd($jadwalkerjas);
        $jenisabsen=jenisabsen::all()->where('jenis_absen','!=','Hadir');
        return view('rekapabsen.rekappegawai',['awal'=>$awal,'akhir'=>$akhir,'jadwalkerjas'=>$jadwalkerjas,'inforekap'=>$inforekap,'atts'=>$atts,'jenisabsens'=>$jenisabsen]);
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
        $id=decrypt($id);
        $id2=decrypt($id2);
        $tanggal=explode('-',$id2);
        // dd($tanggal);
        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->whereYear('atts.tanggal_att','=',$tanggal[0])
        ->whereMonth('atts.tanggal_att','=',$tanggal[1])
        ->where('atts.pegawai_id','=',$id)
        ->select('atts.*','pegawais.id as idpegawai','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->paginate(31);

        // dd($atts);
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
        // dd($jadwalkerjas);
        $jenisabsen=jenisabsen::all()->where('jenis_absen','!=','Hadir')
            // ->where('jenis_absen','!=','Rapat/Undangan')
            ;
        $awal=date("Y/m/d");
        $awal=date("Y/m/d",strtotime("-7 days", strtotime($awal)));
        $akhir=date("Y/m/d");
        $akhir=date("Y/m/d",strtotime("-1 days", strtotime($akhir)));
        return view('rekapabsen.rekappegawaitrans',['awal'=>$awal,'akhir'=>$akhir,'jadwalkerjas'=>$jadwalkerjas,'inforekap'=>$inforekap,'atts'=>$atts,'jenisabsens'=>$jenisabsen,'bulan'=>$id2,'pegawai'=>$pegawai]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

        $this->validate($request, [
            'periode'=>'required',
            'checkbox'=>'required',
            'checkboxnip'=>'required',
            'jenisabsen'=>'required'
        ]);

        $hapusspasi=str_replace(" ","",$request->periode);
        $tanggal=explode("-",$hapusspasi);
        $hasil=date("d",strtotime($tanggal[0]));
        $hasil2=date("d",strtotime($tanggal[1]));

        // dd($request->checkboxnip);
        foreach ($request->checkboxnip as $key => $pegawai) {

          $id=$pegawai;

          $i=0;
          $tanggalbaru =$tanggal[0];

              for ($x = (int)$hasil; $x <= (int)$hasil2; $x++)
              {
                // dd("sda");

                  foreach ($request->checkbox as $key=> $data) {
                      // dd($data);
                      $atts = att::join('jadwalkerjas', 'atts.jadwalkerja_id', '=', 'jadwalkerjas.id')
                          ->where('atts.tanggal_att', '=', $tanggalbaru)
                          ->where('atts.id', '=', $id)
                          ->where('atts.jadwalkerja_id','=',$data)
                          ->get();

                      // dd($atts);


                        foreach ($atts as $key => $att) {
                          $akumulasistandar = jadwalkerja::where('id', '=', $data)->get();

                          $jamawalakumulasi = ($akumulasistandar[0]['jam_masukjadwal']);
                          $jamakhirakumulasi = ($akumulasistandar[0]['jam_keluarjadwal']);
                          $jamtoleransi = strtotime('00:30:00');
                          // dd($jamawalakumulasi);
                          // $jamstandar = date("H:i:s", $this->kurangwaktu($jamakhirakumulasi,$jamawalakumulasi));

                          $jamstandar=$this->kurangwaktu($jamakhirakumulasi,$jamawalakumulasi);
                          // dd($jamstandar);
                          if ($att['akumulasi_sehari'] <= $jamstandar) {

                              $search = att::where('tanggal_att', '=', $tanggalbaru)
                                  ->where('id', '=', $id)
                                  ->count();
                              // dd($search);
                              if ($search > 0) {
                                  // dd('aasdas');
                                  if ($request->jenisabsen == "2") {
                                      $table = att::where('tanggal_att', '=', $tanggalbaru)
                                          ->where('id', '=', $id)
                                          ->where('jadwalkerja_id','=',$data)
                                          ->first();
                                      $table->jenisabsen_id = $request->jenisabsen;
                                      $table->jam_masuk = null;
                                      $table->masukinstansi_id=Auth::user()->instansi_id;
                                      $table->jam_keluar = null;
                                      $table->terlambat = "00:00:00";
                                      $table->keluarinstansi_id=Auth::user()->instansi_id;
                                      $table->akumulasi_sehari = "00:00:00";
                                      $table->save();
                                  } elseif  ($request->jenisabsen=="3"){
                                      $table = att::where('tanggal_att', '=', $tanggalbaru)
                                          ->where('jadwalkerja_id','=',$data)
                                          ->where('id', '=', $id)
                                          ->first();
                                      $table->jenisabsen_id = $request->jenisabsen;
                                      $table->jam_masuk = null;
                                      $table->masukinstansi_id=Auth::user()->instansi_id;
                                      $table->jam_keluar = null;
                                      $table->terlambat = "00:00:00";
                                      $table->keluarinstansi_id=Auth::user()->instansi_id;
                                      $table->akumulasi_sehari = "00:00:00";
                                      $table->save();
                                  }
                                  elseif  ($request->jenisabsen=="4"){
                                      $table = att::where('tanggal_att', '=', $tanggalbaru)
                                          ->where('jadwalkerja_id','=',$data)
                                          ->where('id', '=', $id)
                                          ->first();

                                      $table->jenisabsen_id = $request->jenisabsen;
                                      $table->jam_masuk = null;
                                      $table->masukinstansi_id=Auth::user()->instansi_id;
                                      $table->jam_keluar = null;
                                      $table->terlambat = "00:00:00";
                                      $table->keluarinstansi_id=Auth::user()->instansi_id;
                                      $table->akumulasi_sehari = "00:00:00";
                                      $table->save();
                                  }
                                  elseif  ($request->jenisabsen=="5"){
                                      $table = att::where('tanggal_att', '=', $tanggalbaru)
                                          ->where('jadwalkerja_id','=',$data)
                                          ->where('id', '=', $id)
                                          ->first();

                                      $table->jenisabsen_id = $request->jenisabsen;
                                      $table->jam_masuk = null;
                                      $table->masukinstansi_id=Auth::user()->instansi_id;
                                      $table->jam_keluar = null;
                                      $table->terlambat = "00:00:00";
                                      $table->keluarinstansi_id=Auth::user()->instansi_id;
                                      $table->akumulasi_sehari = "00:00:00";
                                      $table->save();
                                  }
                                  elseif  ($request->jenisabsen=="6"){
                                      $jadwalkerja=jadwalkerja::where('id','=',$data)->get();

                                      $table = att::where('tanggal_att', '=', $tanggalbaru)
                                          ->where('jadwalkerja_id','=',$data)
                                          ->where('id', '=', $id)
                                          ->first();

                                      $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masuk'],$jadwalkerja[0]['jam_keluar']);

                                      $table->jenisabsen_id = $request->jenisabsen;
                                      $table->jam_masuk = null;
                                      $table->masukinstansi_id=Auth::user()->instansi_id;
                                      $table->jam_keluar = null;
                                      $table->terlambat = "00:00:00";
                                      $table->keluarinstansi_id=Auth::user()->instansi_id;
                                      $table->akumulasi_sehari = $akumulasi;
                                      $table->save();
                                  }
                                  elseif  ($request->jenisabsen=="7"){
                                      $jadwalkerja=jadwalkerja::where('id','=',$data)->get();

                                      $table = att::where('tanggal_att', '=', $tanggalbaru)
                                          ->where('jadwalkerja_id','=',$data)
                                          ->where('id', '=', $id)
                                          ->first();

                                      $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$jadwalkerja[0]['jam_keluarjadwal']);

                                      $table->jenisabsen_id = $request->jenisabsen;
                                      $table->jam_masuk = null;
                                      $table->masukinstansi_id=Auth::user()->instansi_id;
                                      $table->jam_keluar = null;
                                      $table->terlambat = "00:00:00";
                                      $table->keluarinstansi_id=Auth::user()->instansi_id;
                                      $table->akumulasi_sehari = $akumulasi;
                                      $table->save();
                                  }
                                  elseif  ($request->jenisabsen=="8"){
                                      $jadwalkerja=jadwalkerja::where('id','=',$data)->get();

                                      $table = att::where('tanggal_att', '=', $tanggalbaru)
                                          ->where('jadwalkerja_id','=',$data)
                                          ->where('id', '=', $id)
                                          ->first();

                                      $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$jadwalkerja[0]['jam_keluarjadwal']);

                                      $table->jenisabsen_id = $request->jenisabsen;
                                      $table->masukinstansi_id=Auth::user()->instansi_id;
                                      $table->keluarinstansi_id=Auth::user()->instansi_id;
                                      $table->terlambat = "00:00:00";
                                      $table->akumulasi_sehari = $akumulasi;
                                      $table->save();
                                  }
                                  elseif  ($request->jenisabsen=="9"){
                                      $table = att::where('tanggal_att', '=', $tanggalbaru)
                                          ->where('jadwalkerja_id','=',$data)
                                          ->where('id', '=', $id)
                                          ->first();

                                      $table->jenisabsen_id = $request->jenisabsen;
                                      $table->jam_masuk = null;
                                      $table->terlambat = "00:00:00";
                                      $table->masukinstansi_id=Auth::user()->instansi_id;
                                      $table->jam_keluar = null;
                                      $table->keluarinstansi_id=Auth::user()->instansi_id;
                                      $table->akumulasi_sehari = "00:00:00";
                                      $table->save();
                                  }

                                  elseif  ($request->jenisabsen=="10"){

                                      $jadwalkerja=jadwalkerja::where('id','=',$data)->get();

                                      $table2 = att::where('tanggal_att', '=', $tanggalbaru)
                                          ->where('jadwalkerja_id','=',$data)
                                          ->where('id', '=', $id)
                                          ->get();

                                      $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$table2[0]['jam_keluar']);

                                      $table = att::where('tanggal_att', '=', $tanggalbaru)
                                          ->where('jadwalkerja_id','=',$data)
                                          ->where('id', '=', $id)
                                          ->first();

                                      $table->jenisabsen_id = $request->jenisabsen;
                                      $table->jam_masuk = $jadwalkerja[0]['jam_masukjadwal'];
                                      $table->masukinstansi_id=Auth::user()->instansi_id;
                                      $table->keluarinstansi_id=Auth::user()->instansi_id;
                                      $table->terlambat = "00:00:00";
                                      $table->akumulasi_sehari = $akumulasi;
                                      $table->save();
                                  }
                                  elseif  ($request->jenisabsen=="11"){

                                      $jadwalkerja=jadwalkerja::where('id','=',$data)->get();

                                      $table2 = att::where('tanggal_att', '=', $tanggalbaru)
                                          ->where('jadwalkerja_id','=',$data)
                                          ->where('id', '=', $id)
                                          ->get();

                                      $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$table2[0]['jam_keluar']);

                                      $table = att::where('tanggal_att', '=', $tanggalbaru)
                                          ->where('jadwalkerja_id','=',$data)
                                          ->where('id', '=', $id)
                                          ->first();

                                      $table->jenisabsen_id = $request->jenisabsen;
                                      $table->jam_masuk = null;
                                      $table->terlambat = "00:00:00";
                                      $table->masukinstansi_id=Auth::user()->instansi_id;
                                      $table->jam_keluar = null;
                                      $table->keluarinstansi_id=Auth::user()->instansi_id;
                                      $table->akumulasi_sehari = "00:00:00";
                                      $table->save();

                                  }

                              } else {

                              }
                          } else {

                          }
                        }

                  }

                  $i++;
                  $tanggalbaru = date("Y-m-d", (strtotime("+" . $i . "days", strtotime($tanggal[0]))));
              }

        }

        return redirect()->back()->with('status','Atur ijin berhasil');

//        dd($x);
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

    public function datarekapadmin()
    {
      $sakit=masterbulanan::join('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
      ->join('instansis','masterbulanans.instansi_id','=','instansis.id')
      ->select('masterbulanans.*','pegawais.nip','pegawais.nama','instansis.namaInstansi')
      ->get();
      return Datatables::of($sakit)
      ->make(true);
    }

    public function datarekapuser()
    {
      $sakit=masterbulanan::join('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
      ->join('instansis','masterbulanans.instansi_id','=','instansis.id')
      ->select('masterbulanans.*','pegawais.nip','pegawais.nama','instansis.namaInstansi')
      ->where('masterbulanans.instansi_id','=',Auth::user()->instansi_id)
      ->get();
      return Datatables::of($sakit)
      ->make(true);
    }

    public function indexrekap(){
        
      return view('rekapabsen.rekapabsenmingguan');
    }

    public function indexrekapadmin(){
      return view('admin.rekapabsen.rekapabsenmingguan');
    }

}
