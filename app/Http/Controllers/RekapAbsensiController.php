<?php

namespace App\Http\Controllers;

use App\jadwalkerja;
use App\jenisabsen;
use App\pegawai;
use App\att;
use App\ruanganuser;
use App\perawatruangan;
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

        $date=date('N');

        // $sekarang=date('Y-m-d');
        $hari=date('Y-m-d');
        $sekarang=date('Y-m-d');
        $status=false;
        if ($date==1){
            $hari='Senin';
            $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
            $akhir=$sekarang;
            $status=true;
        }
        elseif ($date==2){
            $hari='Selasa';
            $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
            $akhir=$sekarang;
            $status=true;
        }
        elseif ($date==3) {
          // dd("asda");
          $hari='Rabu';
          $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
          $akhir=$sekarang;
          $status=false;
        }
        elseif ($date==4) {
          // dd("asda");
          $hari='Kamis';
          $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
          $akhir=$sekarang;
          $status=false;
        }
        elseif ($date==5) {
          // dd("asda");
          $hari='Jumat';
          $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
          $akhir=$sekarang;
          $status=false;
        }
        elseif ($date==6) {
          // dd("asda");
          $hari='Sabtu';
          $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
          $akhir=$sekarang;
          $status=false;
        }
        elseif ($date==7) {
          // dd("asda");
          $hari='Minggu';
          $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
          $akhir=$sekarang;
          $status=false;
        }
        $cari=$request->caridata;
        // dd($cari);
        if (!isset($cari))
        {
            // dd("as");
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

        }
        else
        {
            $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
            ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
            ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
            ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
            ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
            ->where('atts.tanggal_att','>=',$awal)
            ->where('atts.tanggal_att','<=',$akhir)
            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
            ->where('pegawais.nip','LIKE','%'.$cari."%")
            ->orWhere('pegawais.nama','LIKE','%'.$cari."%")
            // ->orWhere('atts.tanggal_att','LIKE','%'.$cari."%")
            ->select('atts.*','jadwalkerjas.jenis_jadwal','pegawais.id as idpegawai','instansismasuk.namaInstansi as namainstansimasuk',
                'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
            ->orderBy('atts.tanggal_att','desc')
            ->paginate(30);
            // dd(($cari));
            // dd($atts);
        }

        if (Auth::user()->role->namaRole=="karu"){
            $jadwalkerjas=jadwalkerja::where('instansi_id','=',Auth::user()->instansi_id)
                ->where('sifat','!=','FD')
                ->orWhere('instansi_id','!=','1')
                // ->whereNotIn('sifat',['FD'])
                ->get();
        }else{
            $jadwalkerjas=jadwalkerja::where('instansi_id','=',Auth::user()->instansi_id)
                // ->where('sifat','!=','FD')
                ->orWhere('id','=','1')
                ->orWhere('instansi_id','=','1')
                // ->whereNotIn('sifat',['FD'])
                ->get();
        }

        

        // $jadwalkerjas=att::join('pegawais','atts.pegawai_id','=','pegawais.id')
        //     ->join('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
        //     ->where('atts.tanggal_att','>=',$awal)
        //     ->where('atts.tanggal_att','<=',$akhir)
        //     ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        //     ->distinct()
        //     ->select('atts.jadwalkerja_id','jadwalkerjas.jenis_jadwal','jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal')
        //     ->get();
        // dd($jadwalkerjas);

        if (Auth::user()->role->namaRole=="rs"){
            $jenisabsen=jenisabsen::all()->where('jenis_absen','!=','Hadir');
        }else{
            $jenisabsen=jenisabsen::all()->where('jenis_absen','!=','Hadir')
            ->where('jenis_absen','!=','Tidak Hadir');
            
        }

        
        return view('rekapabsen.rekappegawai',['cari'=>$cari,'awal'=>$awal,'akhir'=>$akhir,'jadwalkerjas'=>$jadwalkerjas,'inforekap'=>$inforekap,'atts'=>$atts,'jenisabsens'=>$jenisabsen]);
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
        $jenisabsen=jenisabsen::all()->where('id','!=','13')->where('id','!=','1');
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


        $request->jenisabsen=decrypt($request->jenisabsen);

        

        $hapusspasi=str_replace(" ","",$request->periode);
        $tanggal=explode("-",$hapusspasi);
        $hasil=date("Y-m-d",strtotime($tanggal[0]));
        $hasil2=date("Y-m-d",strtotime($tanggal[1]));

        // dd($request->checkboxnip);
        foreach ($request->checkboxnip as $key => $pegawai) {

          $id=decrypt($pegawai);

          $i=0;
          $tanggalbaru =$tanggal[0];
        //   dd($id);
        //   dd(($request->checkbox));
            // dd($hasil);
            foreach ($request->checkbox as $key => $data) {
                $data=decrypt($data);
                // $data=str_replace(array("\r", "\n"), '', $data);
                // dd($data);
                //   dd(($data));              

                  $atts = att::join('jadwalkerjas', 'atts.jadwalkerja_id', '=', 'jadwalkerjas.id')
                      ->where('atts.tanggal_att', '=', $hasil)
                      ->where('atts.id', '=', $id)
                      ->where('atts.jadwalkerja_id','=',$data)
                      ->get();
                  
                    // dd($atts);

                    foreach ($atts as $key => $att) {

                      $akumulasistandar = jadwalkerja::where('id', '=', $data)->get();

                      $jamawalakumulasi = ($akumulasistandar[0]['jam_masukjadwal']);
                      $jamakhirakumulasi = ($akumulasistandar[0]['jam_keluarjadwal']);
                      $jamtoleransi = strtotime('00:30:00');

                      $jamstandar=$this->kurangwaktu($jamakhirakumulasi,$jamawalakumulasi);

                      if ($att['akumulasi_sehari'] <= $jamstandar) {

                          $search = att::where('tanggal_att', '=', $tanggalbaru)
                              ->where('id', '=', $id)
                              ->count();

                          if ($search > 0) {
                            
                              if ($request->jenisabsen == "2") {
                                  $table = att::where('tanggal_att', '=', $tanggalbaru)
                                      ->where('id', '=', $id)
                                      ->where('jadwalkerja_id','=',$data)
                                      ->first();
                                  $table->jenisabsen_id = $request->jenisabsen;
                                  $table->jam_masuk = null;
                                  $table->keluaristirahat=null;
                                  $table->masukistirahat=null;
                                  $table->keteranganmasuk_id=null;
                                  $table->keterangankeluar_id=null;
                                  $table->apel=0;                                  
                                  $table->masukinstansi_id=Auth::user()->instansi_id;
                                  $table->jam_keluar = null;
                                  $table->terlambat = "00:00:00";
                                  $table->keluarinstansi_id=Auth::user()->instansi_id;
                                  $table->akumulasi_sehari = "00:00:00";
                                  $table->save();
                              } elseif  ($request->jenisabsen=="3"){

                                    $jadwalkerja=jadwalkerja::where('id','=',$data)->get();

                                    $table = att::where('tanggal_att', '=', $tanggalbaru)
                                      ->where('jadwalkerja_id','=',$data)
                                      ->where('id', '=', $id)
                                      ->first();
                                      
                                    if ($jadwalkerja[0]['jam_masukjadwal']>$jadwalkerja[0]['jam_keluarjadwal'])
                                    {

                                        if ($jadwalkerja[0]['sifat']=="WA"){
                                            $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                            $table->apel=1;                                  
                                            $akhir=$jadwalkerja[0]['jam_keluarjadwal'];
                                        }
                                        elseif ($jadwalkerja[0]['sifat']=="FD")
                                        {
                                            $tanggalpatokan=date("Y-m-d");
                                            $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                            $table->apel=0;                                  
                                            $akhir=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 16:00:00"));
                                        }
                                        else{
                                            $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                            $table->apel=0;      
                                            $akhir=$jadwalkerja[0]['jam_keluarjadwal'];                                                                        
                                        }

                                        // $akhir=$jadwalkerja[0]['jam_keluarjadwal'];

                                        if ($table->jam_masuk < $jadwalkerja[0]['jam_masukjadwal']) {
                                            $harike=date('N', strtotime($tanggalbaru));
                                            if (($harike==5) && ($table->jadwalkerja_id==1))
                                            {
                                                $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                                $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$awal);
                                                $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                                $table->keluaristirahat=$keluaristirahat;
                                                $table->masukistirahat=$masukistirahat;
                                            }
                                            else
                                            {
                                                $table->keluaristirahat=null;
                                                $table->masukistirahat=null;
                                                $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$jadwalkerja[0]['jam_keluarjadwal']);
                                            }
                                            
                                        }
                                        else
                                        {
                                            $harike=date('N', strtotime($tanggalbaru));
                                            if (($harike==5) && ($table->jadwalkerja_id==1))
                                            {
                                                $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                                $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$table->jam_masuk);
                                                $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                                $table->keluaristirahat=$keluaristirahat;
                                                $table->masukistirahat=$masukistirahat;
                                            }
                                            else
                                            {
                                                $table->keluaristirahat=null;
                                                $table->masukistirahat=null;
                                                $akumulasi=$this->kurangwaktu($table->jam_masuk,$jadwalkerja[0]['jam_keluarjadwal']);    
                                            }
                                                      
                                        }
                                            
                                        // $akumulasi=$this->kurangwaktu($akhir,$jadwalkerja[0]['jam_masukjadwal']);
                                    }
                                    else{
                                        if ($jadwalkerja[0]['sifat']=="WA"){
                                            $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                            $table->apel=1;                                  
                                            $akhir=$jadwalkerja[0]['jam_keluarjadwal'];
                                        }
                                        elseif ($jadwalkerja[0]['sifat']=="FD")
                                        {
                                            $tanggalpatokan=date("Y-m-d");
                                            $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                            $table->apel=0;                                  
                                            $akhir=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 16:00:00"));
                                        }
                                        else{
                                            $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                            $table->apel=0;      
                                            $akhir=$jadwalkerja[0]['jam_keluarjadwal'];                                                                        
                                        }

                                        if ($table->jam_masuk < $jadwalkerja[0]['jam_masukjadwal']) {

                                            $harike=date('N', strtotime($tanggalbaru));
                                            if (($harike==5) && ($table->jadwalkerja_id==1))
                                            {
                                                $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                                $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jadwalkerja[0]['jam_masukjadwal']);
                                                $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                                $table->keluaristirahat=$keluaristirahat;
                                                $table->masukistirahat=$masukistirahat;
                                            }
                                            else
                                            {
                                                $table->keluaristirahat=null;
                                                $table->masukistirahat=null;
                                                $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$jadwalkerja[0]['jam_keluarjadwal']);
                                            }
                                            
                                        }
                                        else
                                        {
                                            $harike=date('N', strtotime($tanggalbaru));
                                            if (($harike==5) && ($table->jadwalkerja_id==1))
                                            {
                                                $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                                $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$table->jam_masuk);
                                                $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                                $table->keluaristirahat=$keluaristirahat;
                                                $table->masukistirahat=$masukistirahat;
                                            }
                                            else
                                            {
                                                $table->keluaristirahat=null;
                                                $table->masukistirahat=null;
                                                $akumulasi=$this->kurangwaktu($table->jam_masuk,$jadwalkerja[0]['jam_keluarjadwal']);    
                                            }
                                                         
                                        }
                                        // $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$akhir);
                                    }   

                                    // $table->jenisabsen_id = $request->jenisabsen;
                                    // $table->jam_masuk = $awal;
                                    // $table->masukinstansi_id=Auth::user()->instansi_id;
                                    // $table->jam_keluar = $akhir;
                                    // $table->terlambat = "00:00:00";
                                    // $table->keluarinstansi_id=Auth::user()->instansi_id;
                                    // $table->akumulasi_sehari = $akumulasi;
                                    // $table->save();

                                    $table->jenisabsen_id = $request->jenisabsen;
                                    $table->jam_masuk = null;
                                    $table->terlambat = "00:00:00";
                                    $table->keluaristirahat=null;
                                    $table->masukistirahat=null;
                                    $table->keteranganmasuk_id=null;
                                    $table->keterangankeluar_id=null;
                                    $table->masukinstansi_id=Auth::user()->instansi_id;
                                    $table->jam_keluar = null;
                                    $table->keluarinstansi_id=Auth::user()->instansi_id;
                                    $table->akumulasi_sehari = "00:00:00";
                                    $table->save();
                              }
                              elseif  ($request->jenisabsen=="4"){

                                $jadwalkerja=jadwalkerja::where('id','=',$data)->get();

                                $table = att::where('tanggal_att', '=', $tanggalbaru)
                                  ->where('jadwalkerja_id','=',$data)
                                  ->where('id', '=', $id)
                                  ->first();
                                  
                                if ($jadwalkerja[0]['jam_masukjadwal']>$jadwalkerja[0]['jam_keluarjadwal'])
                                {

                                    if ($jadwalkerja[0]['sifat']=="WA"){
                                        $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                        $table->apel=1;                                  
                                        $akhir=$jadwalkerja[0]['jam_keluarjadwal'];
                                    }
                                    elseif ($jadwalkerja[0]['sifat']=="FD")
                                    {
                                        $tanggalpatokan=date("Y-m-d");
                                        $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                        $table->apel=0;                                  
                                        $akhir=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 16:00:00"));
                                    }
                                    else{
                                        $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                        $table->apel=0;      
                                        $akhir=$jadwalkerja[0]['jam_keluarjadwal'];                                                                        
                                    }

                                    if ($table->jam_masuk < $jadwalkerja[0]['jam_masukjadwal']) {
                                        $harike=date('N', strtotime($tanggalbaru));
                                        if (($harike==5) && ($table->jadwalkerja_id==1))
                                        {
                                            $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                            $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                            $akumulasi1=$this->kurangwaktu($keluaristirahat,$awal);
                                            $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                            $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                            $table->keluaristirahat=$keluaristirahat;
                                            $table->masukistirahat=$masukistirahat;
                                        }
                                        else
                                        {
                                            $table->keluaristirahat=null;
                                            $table->masukistirahat=null;
                                            $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$jadwalkerja[0]['jam_keluarjadwal']);
                                        }
                                        
                                    }
                                    else
                                    {
                                        $harike=date('N', strtotime($tanggalbaru));
                                        if (($harike==5) && ($table->jadwalkerja_id==1))
                                        {
                                            $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                            $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                            $akumulasi1=$this->kurangwaktu($keluaristirahat,$table->jam_masuk);
                                            $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                            $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                            $table->keluaristirahat=$keluaristirahat;
                                            $table->masukistirahat=$masukistirahat;
                                        }
                                        else
                                        {
                                            $table->keluaristirahat=null;
                                            $table->masukistirahat=null;
                                            $akumulasi=$this->kurangwaktu($table->jam_masuk,$jadwalkerja[0]['jam_keluarjadwal']);    
                                        }
                                                  
                                    }
                                    // $akumulasi=$this->kurangwaktu($akhir,$jadwalkerja[0]['jam_masukjadwal']);
                                }
                                else{
                                    // dd("asd");
                                    if ($jadwalkerja[0]['sifat']=="WA"){
                                        $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                        $table->apel=1;                                  
                                        $akhir=$jadwalkerja[0]['jam_keluarjadwal'];
                                    }
                                    elseif ($jadwalkerja[0]['sifat']=="FD")
                                    {
                                        $tanggalpatokan=date("Y-m-d");
                                        $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                        $table->apel=0;                                  
                                        $akhir=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 16:00:00"));
                                    }
                                    else{
                                        $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                        $table->apel=0;      
                                        $akhir=$jadwalkerja[0]['jam_keluarjadwal'];                                                                        
                                    }
                                    

                                    if ($table->jam_masuk < $jadwalkerja[0]['jam_masukjadwal']) {

                                        $harike=date('N', strtotime($tanggalbaru));
                                        if (($harike==5) && ($table->jadwalkerja_id==1))
                                        {
                                            $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                            $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                            $akumulasi1=$this->kurangwaktu($keluaristirahat,$jadwalkerja[0]['jam_masukjadwal']);
                                            $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                            $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                            $table->keluaristirahat=$keluaristirahat;
                                            $table->masukistirahat=$masukistirahat;
                                        }
                                        else
                                        {
                                            $table->keluaristirahat=null;
                                            $table->masukistirahat=null;
                                            $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$jadwalkerja[0]['jam_keluarjadwal']);
                                        }
                                        
                                    }
                                    else
                                    {
                                        $harike=date('N', strtotime($tanggalbaru));
                                        if (($harike==5) && ($table->jadwalkerja_id==1))
                                        {
                                            $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                            $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                            $akumulasi1=$this->kurangwaktu($keluaristirahat,$table->jam_masuk);
                                            $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                            $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                            $table->keluaristirahat=$keluaristirahat;
                                            $table->masukistirahat=$masukistirahat;
                                        }
                                        else
                                        {
                                            $table->keluaristirahat=null;
                                            $table->masukistirahat=null;
                                            $akumulasi=$this->kurangwaktu($table->jam_masuk,$jadwalkerja[0]['jam_keluarjadwal']);    
                                        }
                                                     
                                    }
                                    // $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$akhir);
                                }   

                                // $table->jenisabsen_id = $request->jenisabsen;
                                // $table->jam_masuk = $awal;
                                // $table->masukinstansi_id=Auth::user()->instansi_id;
                                // $table->jam_keluar = $akhir;
                                // $table->terlambat = "00:00:00";
                                // $table->keluarinstansi_id=Auth::user()->instansi_id;
                                // $table->akumulasi_sehari = $akumulasi;
                                // $table->save();

                                $table->jenisabsen_id = $request->jenisabsen;
                                $table->jam_masuk = null;
                                $table->terlambat = "00:00:00";
                                $table->keluaristirahat=null;
                                $table->masukistirahat=null;
                                $table->keteranganmasuk_id=null;
                                $table->keterangankeluar_id=null;
                                $table->masukinstansi_id=Auth::user()->instansi_id;
                                $table->jam_keluar = null;
                                $table->keluarinstansi_id=Auth::user()->instansi_id;
                                $table->akumulasi_sehari = "00:00:00";
                                $table->save();
                              }
                              elseif  ($request->jenisabsen=="5"){

                                $jadwalkerja=jadwalkerja::where('id','=',$data)->get();

                                $table = att::where('tanggal_att', '=', $tanggalbaru)
                                  ->where('jadwalkerja_id','=',$data)
                                  ->where('id', '=', $id)
                                  ->first();
                                  
                                if ($jadwalkerja[0]['jam_masukjadwal']>$jadwalkerja[0]['jam_keluarjadwal'])
                                {

                                    if ($jadwalkerja[0]['sifat']=="WA"){
                                        $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                        $table->apel=1;                                  
                                        $akhir=$jadwalkerja[0]['jam_keluarjadwal'];
                                    }
                                    elseif ($jadwalkerja[0]['sifat']=="FD")
                                    {
                                        $tanggalpatokan=date("Y-m-d");
                                        $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                        $table->apel=0;                                  
                                        $akhir=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 16:00:00"));
                                    }
                                    else{
                                        $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                        $table->apel=0;      
                                        $akhir=$jadwalkerja[0]['jam_keluarjadwal'];                                                                        
                                    }
                                    

                                    if ($table->jam_masuk < $jadwalkerja[0]['jam_masukjadwal']) {
                                        $harike=date('N', strtotime($tanggalbaru));
                                        if (($harike==5) && ($table->jadwalkerja_id==1))
                                        {
                                            $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                            $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                            $akumulasi1=$this->kurangwaktu($keluaristirahat,$awal);
                                            $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                            $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                            $table->keluaristirahat=$keluaristirahat;
                                            $table->masukistirahat=$masukistirahat;
                                        }
                                        else
                                        {
                                            $table->keluaristirahat=null;
                                            $table->masukistirahat=null;
                                            $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$jadwalkerja[0]['jam_keluarjadwal']);
                                        }
                                        
                                    }
                                    else
                                    {
                                        $harike=date('N', strtotime($tanggalbaru));
                                        if (($harike==5) && ($table->jadwalkerja_id==1))
                                        {
                                            $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                            $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                            $akumulasi1=$this->kurangwaktu($keluaristirahat,$table->jam_masuk);
                                            $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                            $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                            $table->keluaristirahat=$keluaristirahat;
                                            $table->masukistirahat=$masukistirahat;
                                        }
                                        else
                                        {
                                            $table->keluaristirahat=null;
                                            $table->masukistirahat=null;
                                            $akumulasi=$this->kurangwaktu($table->jam_masuk,$jadwalkerja[0]['jam_keluarjadwal']);    
                                        }
                                                  
                                    }
                                    // $akumulasi=$this->kurangwaktu($akhir,$jadwalkerja[0]['jam_masukjadwal']);
                                }
                                else{
                                    
                                    if ($jadwalkerja[0]['sifat']=="WA"){
                                        $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                        $table->apel=1;                                  
                                        $akhir=$jadwalkerja[0]['jam_keluarjadwal'];
                                    }
                                    elseif ($jadwalkerja[0]['sifat']=="FD")
                                    {
                                        $tanggalpatokan=date("Y-m-d");
                                        $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                        $table->apel=0;                                  
                                        $akhir=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 16:00:00"));
                                    }
                                    else{
                                        $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                        $table->apel=0;      
                                        $akhir=$jadwalkerja[0]['jam_keluarjadwal'];                                                                        
                                    }
                                    
                                    if ($table->jam_masuk < $jadwalkerja[0]['jam_masukjadwal']) {

                                        $harike=date('N', strtotime($tanggalbaru));
                                        if (($harike==5) && ($table->jadwalkerja_id==1))
                                        {
                                            $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                            $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                            $akumulasi1=$this->kurangwaktu($keluaristirahat,$jadwalkerja[0]['jam_masukjadwal']);
                                            $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                            $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                            $table->keluaristirahat=$keluaristirahat;
                                            $table->masukistirahat=$masukistirahat;
                                        }
                                        else
                                        {
                                            $table->keluaristirahat=null;
                                            $table->masukistirahat=null;
                                            $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$jadwalkerja[0]['jam_keluarjadwal']);
                                        }
                                        
                                    }
                                    else
                                    {
                                        $harike=date('N', strtotime($tanggalbaru));
                                        if (($harike==5) && ($table->jadwalkerja_id==1))
                                        {
                                            $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                            $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                            $akumulasi1=$this->kurangwaktu($keluaristirahat,$table->jam_masuk);
                                            $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                            $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                            $table->keluaristirahat=$keluaristirahat;
                                            $table->masukistirahat=$masukistirahat;
                                        }
                                        else
                                        {
                                            $table->keluaristirahat=null;
                                            $table->masukistirahat=null;
                                            $akumulasi=$this->kurangwaktu($table->jam_masuk,$jadwalkerja[0]['jam_keluarjadwal']);    
                                        }
                                                     
                                    }
                                    // $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$akhir);
                                }   

                                // $table->jenisabsen_id = $request->jenisabsen;
                                // $table->jam_masuk = $awal;
                                // $table->masukinstansi_id=Auth::user()->instansi_id;
                                // $table->jam_keluar = $akhir;
                                // $table->terlambat = "00:00:00";
                                // $table->keluarinstansi_id=Auth::user()->instansi_id;
                                // $table->akumulasi_sehari = $akumulasi;
                                // $table->save();

                                $table->jenisabsen_id = $request->jenisabsen;
                                $table->jam_masuk = null;
                                $table->terlambat = "00:00:00";
                                $table->keluaristirahat=null;
                                $table->masukistirahat=null;
                                $table->keteranganmasuk_id=null;
                                $table->keterangankeluar_id=null;
                                $table->masukinstansi_id=Auth::user()->instansi_id;
                                $table->jam_keluar = null;
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
                                      
                                    if ($jadwalkerja[0]['jam_masukjadwal']>$jadwalkerja[0]['jam_keluarjadwal'])
                                    {

                                        if ($jadwalkerja[0]['sifat']=="WA"){
                                            $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                            $table->apel=1;                                  
                                            $akhir=$jadwalkerja[0]['jam_keluarjadwal'];
                                        }
                                        elseif ($jadwalkerja[0]['sifat']=="FD")
                                        {
                                            $tanggalpatokan=date("Y-m-d");
                                            $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                            $table->apel=0;                                  
                                            $akhir=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 16:00:00"));
                                        }
                                        else{
                                            $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                            $table->apel=0;      
                                            $akhir=$jadwalkerja[0]['jam_keluarjadwal'];                                                                        
                                        }
                                        
                                        if ($table->jam_masuk < $jadwalkerja[0]['jam_masukjadwal']) {
                                            $harike=date('N', strtotime($tanggalbaru));
                                            if (($harike==5) && ($table->jadwalkerja_id==1))
                                            {
                                                $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                                $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$awal);
                                                $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                                $table->keluaristirahat=$keluaristirahat;
                                                $table->masukistirahat=$masukistirahat;
                                            }
                                            else
                                            {
                                                $table->keluaristirahat=null;
                                                $table->masukistirahat=null;
                                                $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$jadwalkerja[0]['jam_keluarjadwal']);
                                            }
                                            
                                        }
                                        else
                                        {
                                            $harike=date('N', strtotime($tanggalbaru));
                                            if (($harike==5) && ($table->jadwalkerja_id==1))
                                            {
                                                $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                                $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$table->jam_masuk);
                                                $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                                $table->keluaristirahat=$keluaristirahat;
                                                $table->masukistirahat=$masukistirahat;
                                            }
                                            else
                                            {
                                                $table->keluaristirahat=null;
                                                $table->masukistirahat=null;
                                                $akumulasi=$this->kurangwaktu($table->jam_masuk,$jadwalkerja[0]['jam_keluarjadwal']);    
                                            }
                                                      
                                        }
                                        // $akumulasi=$this->kurangwaktu($akhir,$jadwalkerja[0]['jam_masukjadwal']);
                                    }
                                    else{
                                        
                                        if ($jadwalkerja[0]['sifat']=="WA"){
                                            $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                            $table->apel=1;                                  
                                            $akhir=$jadwalkerja[0]['jam_keluarjadwal'];
                                        }
                                        elseif ($jadwalkerja[0]['sifat']=="FD")
                                        {
                                            $tanggalpatokan=date("Y-m-d");
                                            $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                            $table->apel=0;                                  
                                            $akhir=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 16:00:00"));
                                        }
                                        else{
                                            $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                            $table->apel=0;      
                                            $akhir=$jadwalkerja[0]['jam_keluarjadwal'];                                                                        
                                        }
                                        
                                        if ($table->jam_masuk < $jadwalkerja[0]['jam_masukjadwal']) {

                                            $harike=date('N', strtotime($tanggalbaru));
                                            if (($harike==5) && ($table->jadwalkerja_id==1))
                                            {
                                                $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                                $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jadwalkerja[0]['jam_masukjadwal']);
                                                $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                                $table->keluaristirahat=$keluaristirahat;
                                                $table->masukistirahat=$masukistirahat;
                                            }
                                            else
                                            {
                                                $table->keluaristirahat=null;
                                                $table->masukistirahat=null;
                                                $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$jadwalkerja[0]['jam_keluarjadwal']);
                                            }
                                            
                                        }
                                        else
                                        {
                                            $harike=date('N', strtotime($tanggalbaru));
                                            if (($harike==5) && ($table->jadwalkerja_id==1))
                                            {
                                                $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                                $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$table->jam_masuk);
                                                $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                                $table->keluaristirahat=$keluaristirahat;
                                                $table->masukistirahat=$masukistirahat;
                                            }
                                            else
                                            {
                                                $table->keluaristirahat=null;
                                                $table->masukistirahat=null;
                                                $akumulasi=$this->kurangwaktu($table->jam_masuk,$jadwalkerja[0]['jam_keluarjadwal']);    
                                            }
                                                         
                                        }
                                        // $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$akhir);
                                    }   

                                    // $table->jenisabsen_id = $request->jenisabsen;
                                    // $table->jam_masuk = $awal;
                                    // $table->masukinstansi_id=Auth::user()->instansi_id;
                                    // $table->jam_keluar = $akhir;
                                    // $table->terlambat = "00:00:00";
                                    // $table->keluarinstansi_id=Auth::user()->instansi_id;
                                    // $table->akumulasi_sehari = $akumulasi;
                                    // $table->save();

                                    $table->jenisabsen_id = $request->jenisabsen;
                                    $table->jam_masuk = null;
                                    $table->terlambat = "00:00:00";
                                    $table->keluaristirahat=null;
                                    $table->masukistirahat=null;
                                    $table->keteranganmasuk_id=null;
                                    $table->keterangankeluar_id=null;
                                    $table->masukinstansi_id=Auth::user()->instansi_id;
                                    $table->jam_keluar = null;
                                    $table->keluarinstansi_id=Auth::user()->instansi_id;
                                    $table->akumulasi_sehari = "00:00:00";
                                    $table->save();
                              }
                              elseif  ($request->jenisabsen=="7"){

                                $jadwalkerja=jadwalkerja::where('id','=',$data)->get();

                                    $table = att::where('tanggal_att', '=', $tanggalbaru)
                                      ->where('jadwalkerja_id','=',$data)
                                      ->where('id', '=', $id)
                                      ->first();
                                      
                                    if ($jadwalkerja[0]['jam_masukjadwal']>$jadwalkerja[0]['jam_keluarjadwal'])
                                    {

                                        if ($jadwalkerja[0]['sifat']=="WA"){
                                            $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                            $table->apel=1;                                  
                                            $akhir=$jadwalkerja[0]['jam_keluarjadwal'];
                                        }
                                        elseif ($jadwalkerja[0]['sifat']=="FD")
                                        {
                                            $tanggalpatokan=date("Y-m-d");
                                            $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                            $table->apel=0;                                  
                                            $akhir=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 16:00:00"));
                                        }
                                        else{
                                            $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                            $table->apel=0;      
                                            $akhir=$jadwalkerja[0]['jam_keluarjadwal'];                                                                        
                                        }
                                        
                                        if ($table->jam_masuk < $jadwalkerja[0]['jam_masukjadwal']) {
                                            $harike=date('N', strtotime($tanggalbaru));
                                            if (($harike==5) && ($table->jadwalkerja_id==1))
                                            {
                                                $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                                $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$awal);
                                                $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                                $table->keluaristirahat=$keluaristirahat;
                                                $table->masukistirahat=$masukistirahat;
                                            }
                                            else
                                            {
                                                $table->keluaristirahat=null;
                                                $table->masukistirahat=null;
                                                $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$jadwalkerja[0]['jam_keluarjadwal']);
                                            }
                                            
                                        }
                                        else
                                        {
                                            $harike=date('N', strtotime($tanggalbaru));
                                            if (($harike==5) && ($table->jadwalkerja_id==1))
                                            {
                                                $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                                $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$table->jam_masuk);
                                                $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                                $table->keluaristirahat=$keluaristirahat;
                                                $table->masukistirahat=$masukistirahat;
                                            }
                                            else
                                            {
                                                $table->keluaristirahat=null;
                                                $table->masukistirahat=null;
                                                $akumulasi=$this->kurangwaktu($table->jam_masuk,$jadwalkerja[0]['jam_keluarjadwal']);    
                                            }
                                                      
                                        }
                                        // $akumulasi=$this->kurangwaktu($akhir,$jadwalkerja[0]['jam_masukjadwal']);
                                    }
                                    else{
                                        
                                        if ($jadwalkerja[0]['sifat']=="WA"){
                                            $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                            $table->apel=1;                                  
                                            $akhir=$jadwalkerja[0]['jam_keluarjadwal'];
                                        }
                                        elseif ($jadwalkerja[0]['sifat']=="FD")
                                        {
                                            $tanggalpatokan=date("Y-m-d");
                                            $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                            $table->apel=0;                                  
                                            $akhir=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 16:00:00"));
                                        }
                                        else{
                                            $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                            $table->apel=0;      
                                            $akhir=$jadwalkerja[0]['jam_keluarjadwal'];                                                                        
                                        }

                                        if ($table->jam_masuk < $jadwalkerja[0]['jam_masukjadwal']) {

                                            $harike=date('N', strtotime($tanggalbaru));
                                            if (($harike==5) && ($table->jadwalkerja_id==1))
                                            {
                                                $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                                $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jadwalkerja[0]['jam_masukjadwal']);
                                                $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                                $table->keluaristirahat=$keluaristirahat;
                                                $table->masukistirahat=$masukistirahat;
                                            }
                                            else
                                            {
                                                $table->keluaristirahat=null;
                                                $table->masukistirahat=null;
                                                $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$jadwalkerja[0]['jam_keluarjadwal']);
                                            }
                                            
                                        }
                                        else
                                        {
                                            $harike=date('N', strtotime($tanggalbaru));
                                            if (($harike==5) && ($table->jadwalkerja_id==1))
                                            {
                                                $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                                $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$table->jam_masuk);
                                                $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                                $table->keluaristirahat=$keluaristirahat;
                                                $table->masukistirahat=$masukistirahat;
                                            }
                                            else
                                            {
                                                $table->keluaristirahat=null;
                                                $table->masukistirahat=null;
                                                $akumulasi=$this->kurangwaktu($table->jam_masuk,$jadwalkerja[0]['jam_keluarjadwal']);    
                                            }
                                                         
                                        }
                                        // $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$akhir);
                                    }   

                                    // $table->jenisabsen_id = $request->jenisabsen;
                                    // $table->jam_masuk = $awal;
                                    // $table->masukinstansi_id=Auth::user()->instansi_id;
                                    // $table->jam_keluar = $akhir;
                                    // $table->terlambat = "00:00:00";
                                    // $table->keluarinstansi_id=Auth::user()->instansi_id;
                                    // $table->akumulasi_sehari = $akumulasi;
                                    // $table->save();

                                    $table->jenisabsen_id = $request->jenisabsen;
                                    $table->jam_masuk = null;
                                    $table->terlambat = "00:00:00";
                                    $table->keluaristirahat=null;
                                    $table->masukistirahat=null;
                                    $table->masukinstansi_id=Auth::user()->instansi_id;
                                    $table->jam_keluar = null;

                                    $table->keteranganmasuk_id=null;
                                    $table->keterangankeluar_id=null;
                                    $table->keluarinstansi_id=Auth::user()->instansi_id;
                                    $table->akumulasi_sehari = "00:00:00";
                                    $table->save();
                              }
                              elseif  ($request->jenisabsen=="8"){

                                $jadwalkerja=jadwalkerja::where('id','=',$data)->get();

                                    $table = att::where('tanggal_att', '=', $tanggalbaru)
                                      ->where('jadwalkerja_id','=',$data)
                                      ->where('id', '=', $id)
                                      ->first();
                                      
                                    if ($jadwalkerja[0]['jam_masukjadwal']>$jadwalkerja[0]['jam_keluarjadwal'])
                                    {

                                        if ($jadwalkerja[0]['sifat']=="WA"){
                                            $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                            $table->apel=1;                                  
                                            $akhir=$jadwalkerja[0]['jam_keluarjadwal'];
                                        }
                                        elseif ($jadwalkerja[0]['sifat']=="FD")
                                        {
                                            $tanggalpatokan=date("Y-m-d");
                                            $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                            $table->apel=0;                                  
                                            $akhir=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 16:00:00"));
                                        }
                                        else{
                                            $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                            $table->apel=0;      
                                            $akhir=$jadwalkerja[0]['jam_keluarjadwal'];                                                                        
                                        }
                                        
                                        if ($table->jam_masuk < $jadwalkerja[0]['jam_masukjadwal']) {
                                            $harike=date('N', strtotime($tanggalbaru));
                                            if (($harike==5) && ($table->jadwalkerja_id==1))
                                            {
                                                $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                                $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$awal);
                                                $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                                $table->keluaristirahat=$keluaristirahat;
                                                $table->masukistirahat=$masukistirahat;
                                            }
                                            else
                                            {
                                                $table->keluaristirahat=null;
                                                $table->masukistirahat=null;
                                                $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$jadwalkerja[0]['jam_keluarjadwal']);
                                            }
                                            
                                        }
                                        else
                                        {
                                            $harike=date('N', strtotime($tanggalbaru));
                                            if (($harike==5) && ($table->jadwalkerja_id==1))
                                            {
                                                $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                                $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$table->jam_masuk);
                                                $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                                $table->keluaristirahat=$keluaristirahat;
                                                $table->masukistirahat=$masukistirahat;
                                            }
                                            else
                                            {
                                                $table->keluaristirahat=null;
                                                $table->masukistirahat=null;
                                                $akumulasi=$this->kurangwaktu($table->jam_masuk,$jadwalkerja[0]['jam_keluarjadwal']);    
                                            }
                                                      
                                        }
                                        // $akumulasi=$this->kurangwaktu($akhir,$jadwalkerja[0]['jam_masukjadwal']);
                                    }
                                    else{
                                        
                                        if ($jadwalkerja[0]['sifat']=="WA"){
                                            $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                            $table->apel=1;                                  
                                            $akhir=$jadwalkerja[0]['jam_keluarjadwal'];
                                        }
                                        elseif ($jadwalkerja[0]['sifat']=="FD")
                                        {
                                            $tanggalpatokan=date("Y-m-d");
                                            $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                            $table->apel=0;                                  
                                            $akhir=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 16:00:00"));
                                        }
                                        else{
                                            $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                            $table->apel=0;      
                                            $akhir=$jadwalkerja[0]['jam_keluarjadwal'];                                                                        
                                        }

                                        if ($table->jam_masuk < $jadwalkerja[0]['jam_masukjadwal']) {

                                            $harike=date('N', strtotime($tanggalbaru));
                                            if (($harike==5) && ($table->jadwalkerja_id==1))
                                            {
                                                $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                                $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jadwalkerja[0]['jam_masukjadwal']);
                                                $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                                $table->keluaristirahat=$keluaristirahat;
                                                $table->masukistirahat=$masukistirahat;
                                            }
                                            else
                                            {
                                                $table->keluaristirahat=null;
                                                $table->masukistirahat=null;
                                                $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$jadwalkerja[0]['jam_keluarjadwal']);
                                            }
                                            
                                        }
                                        else
                                        {
                                            $harike=date('N', strtotime($tanggalbaru));
                                            if (($harike==5) && ($table->jadwalkerja_id==1))
                                            {
                                                $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                                $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$table->jam_masuk);
                                                $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                                $table->keluaristirahat=$keluaristirahat;
                                                $table->masukistirahat=$masukistirahat;
                                            }
                                            else
                                            {
                                                $table->keluaristirahat=null;
                                                $table->masukistirahat=null;
                                                $akumulasi=$this->kurangwaktu($table->jam_masuk,$jadwalkerja[0]['jam_keluarjadwal']);    
                                            }
                                                         
                                        }
                                        // $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$akhir);
                                    }   

                                    // $table->jenisabsen_id = $request->jenisabsen;
                                    // $table->jam_masuk = $awal;
                                    // $table->masukinstansi_id=Auth::user()->instansi_id;
                                    // $table->jam_keluar = $akhir;
                                    // $table->terlambat = "00:00:00";
                                    // $table->keluarinstansi_id=Auth::user()->instansi_id;
                                    // $table->akumulasi_sehari = $akumulasi;
                                    // $table->save();

                                    $table->jenisabsen_id = $request->jenisabsen;
                                    $table->jam_masuk = null;
                                    $table->terlambat = "00:00:00";
                                    $table->keluaristirahat=null;
                                    $table->masukistirahat=null;
                                    $table->masukinstansi_id=Auth::user()->instansi_id;
                                    $table->jam_keluar = null;
                                    $table->keteranganmasuk_id=null;
                                    $table->keterangankeluar_id=null;
                                    $table->keluarinstansi_id=Auth::user()->instansi_id;
                                    $table->akumulasi_sehari = "00:00:00";
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
                                  $table->keluaristirahat=null;
                                  $table->masukistirahat=null;
                                  $table->masukinstansi_id=Auth::user()->instansi_id;
                                  $table->jam_keluar = null;
                                  $table->keteranganmasuk_id=null;
                                  $table->keterangankeluar_id=null;
                                  $table->keluarinstansi_id=Auth::user()->instansi_id;
                                  $table->akumulasi_sehari = "00:00:00";
                                  $table->save();
                              }
                              elseif  ($request->jenisabsen=="10"){

                                    $jadwalkerja=jadwalkerja::where('id','=',$data)->get();
                                    //dd($jadwalkerja);
                                    $jamkeluar = att::where('tanggal_att', '=', $tanggalbaru)
                                      ->where('jadwalkerja_id','=',$data)
                                      ->where('id', '=', $id)
                                      ->first();
                                    $table = att::where('tanggal_att', '=', $tanggalbaru)
                                      ->where('jadwalkerja_id','=',$data)
                                      ->where('id', '=', $id)
                                      ->first();  
                                    
                                    if ($jamkeluar->jam_keluar!=null){


                                        if ($jadwalkerja[0]['jam_masukjadwal']>$jadwalkerja[0]['jam_keluarjadwal'])
                                        {
                                            
                                            if ($jadwalkerja[0]['sifat']=="WA"){
                                                $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                                $table->apel=1;                                  
                                                $akhir=$jadwalkerja[0]['jam_keluarjadwal'];
                                            }
                                            elseif ($jadwalkerja[0]['sifat']=="FD")
                                            {
                                                $tanggalpatokan=date("Y-m-d");
                                                $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                                $table->apel=0;                                  
                                                $akhir=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 16:00:00"));
                                            }
                                            else{
                                                $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                                $table->apel=0;      
                                                $akhir=$jadwalkerja[0]['jam_keluarjadwal'];                                                                        
                                            }

                                            if ($jamkeluar->jam_keluar < $jadwalkerja[0]['jam_keluarjadwal']) {
                                                $akumulasi=$this->kurangwaktu($jamkeluar->jam_keluar,$jadwalkerja[0]['jam_masukjadwal']);
                                            }
                                            else
                                            {
                                                $akumulasi=$this->kurangwaktu($akhir,$jadwalkerja[0]['jam_masukjadwal']);
                                            }
                                            
                                        }
                                        else
                                        {
                                            if ($jadwalkerja[0]['sifat']=="WA"){
                                                $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                                $table->apel=1;                                  
                                                $akhir=$jadwalkerja[0]['jam_keluarjadwal'];
                                            }
                                            elseif ($jadwalkerja[0]['sifat']=="FD")
                                            {
                                                $tanggalpatokan=date("Y-m-d");
                                                $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                                $table->apel=0;                                  
                                                $akhir=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 16:00:00"));
                                            }
                                            else{
                                                $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                                $table->apel=0;      
                                                $akhir=$jadwalkerja[0]['jam_keluarjadwal'];                                                                        
                                            }

                                            if ($jamkeluar->jam_keluar < $jadwalkerja[0]['jam_keluarjadwal']) {
                                                $akumulasi=$this->kurangwaktu($jamkeluar->jam_keluar,$jadwalkerja[0]['jam_masukjadwal']);
                                            }
                                            else
                                            {
                                                $akumulasi=$this->kurangwaktu($akhir,$jadwalkerja[0]['jam_masukjadwal']);
                                            }
                                        }
                                    }
                                    else
                                    {
                                        if ($jadwalkerja[0]['jam_masukjadwal']>$jadwalkerja[0]['jam_keluarjadwal'])
                                        {
                                            if ($jadwalkerja[0]['sifat']=="WA"){
                                                $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                                $table->apel=1;                                  
                                                $akhir=$jadwalkerja[0]['jam_keluarjadwal'];
                                            }
                                            elseif ($jadwalkerja[0]['sifat']=="FD")
                                            {
                                                $tanggalpatokan=date("Y-m-d");
                                                $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                                $table->apel=0;                                  
                                                $akhir=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 16:00:00"));
                                            }
                                            else{
                                                $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                                $table->apel=0;      
                                                $akhir=$jadwalkerja[0]['jam_keluarjadwal'];                                                                        
                                            }

                                            $akumulasi=$this->kurangwaktu($akhir,$jadwalkerja[0]['jam_masukjadwal']);
                                        }
                                        else{
                                            
                                            if ($jadwalkerja[0]['sifat']=="WA"){
                                                $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                                $table->apel=1;                                  
                                                $akhir=$jadwalkerja[0]['jam_keluarjadwal'];
                                            }
                                            elseif ($jadwalkerja[0]['sifat']=="FD")
                                            {
                                                $tanggalpatokan=date("Y-m-d");
                                                $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                                $table->apel=0;                                  
                                                $akhir=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 16:00:00"));
                                            }
                                            else{
                                                $awal=date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($jadwalkerja[0]['jam_masukjadwal'])));
                                                $table->apel=0;      
                                                $akhir=$jadwalkerja[0]['jam_keluarjadwal'];                                                                        
                                            }

                                            $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$akhir);
                                        }
                                    }
                                  

                                  

                                  $table->jenisabsen_id = "1";
                                  $table->jam_masuk = $awal;
                                  $table->keteranganmasuk_id="1";
                                  $table->keterangankeluar_id=null;
                                  $table->masukinstansi_id=Auth::user()->instansi_id;
                                  $table->terlambat = "00:00:00";
                                  $table->keteranganmasuk_id=$request->jenisabsen;
                                //   if ($table->jam_keluar!=null){
                                //     $harike=date('N', strtotime($tanggalbaru));
                                //     if (($harike==5) && ($table->jadwalkerja_id==1))
                                //         {
                                //             $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                //             $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                //             $akumulasi1=$this->kurangwaktu($keluaristirahat,$awal);
                                //             $akumulasi2=$this->kurangwaktu($akhir,$masukistirahat);
                                //             $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                //             $table->keluaristirahat=$keluaristirahat;
                                //             $table->masukistirahat=$masukistirahat;
                                //         }
                                //         else
                                //         {
                                //             $table->keluaristirahat=null;
                                //             $table->masukistirahat=null;
                                //         }
                                //     $table->akumulasi_sehari=$akumulasi;

                                //   }
                                  
                                  $table->save();

                                //   $table->jenisabsen_id = $request->jenisabsen;
                                //   $table->jam_masuk = null;
                                //   $table->terlambat = "00:00:00";
                                //   $table->keluaristirahat=null;
                                //   $table->masukistirahat=null;
                                //   $table->masukinstansi_id=Auth::user()->instansi_id;
                                //   $table->jam_keluar = null;
                                //   $table->keluarinstansi_id=Auth::user()->instansi_id;
                                //   $table->akumulasi_sehari = "00:00:00";
                                //   $table->save();
                              }
                              elseif  ($request->jenisabsen=="11"){

                                  $table = att::where('tanggal_att', '=', $tanggalbaru)
                                      ->where('jadwalkerja_id','=',$data)
                                      ->where('id', '=', $id)
                                      ->first();

                                //   $table->jenisabsen_id = $request->jenisabsen;
                                //   $table->jam_masuk = null;
                                //   $table->terlambat = "00:00:00";
                                //   $table->masukinstansi_id=Auth::user()->instansi_id;
                                //   $table->jam_keluar = null;
                                //   $table->keluaristirahat=null;
                                //   $table->masukistirahat=null;
                                //   $table->keluarinstansi_id=Auth::user()->instansi_id;
                                //   $table->akumulasi_sehari = "00:00:00";
                                //   $table->save();

                                  $table->jenisabsen_id = $request->jenisabsen;
                                  $table->jam_masuk = null;
                                  $table->terlambat = "00:00:00";
                                  $table->keluaristirahat=null;
                                  $table->masukistirahat=null;
                                  $table->masukinstansi_id=Auth::user()->instansi_id;
                                  $table->jam_keluar = null;
                                  $table->keteranganmasuk_id=null;
                                  $table->keterangankeluar_id=null;
                                  $table->keluarinstansi_id=Auth::user()->instansi_id;
                                  $table->akumulasi_sehari = "00:00:00";
                                  $table->save();

                              }
                              elseif  ($request->jenisabsen=="12"){

                                $jadwalkerja=jadwalkerja::where('id','=',$data)->get();

                                $table = att::where('tanggal_att', '=', $tanggalbaru)
                                    ->where('jadwalkerja_id','=',$data)
                                    ->where('id', '=', $id)
                                    ->first();

                                if ($table->jam_masuk==null){
                                    return redirect()->back()->with('error','Jam masuk kosong !');
                                }
                                    
                                if ($jadwalkerja[0]['lewathari'])
                                {
                                    if ($jadwalkerja[0]['sifat']=="WA"){
                                        $awal=$jadwalkerja[0]['jam_masukjadwal'];
                                        // $awal=$table->jam_masuk;
                                        $table->apel=1;     
                                    }
                                    elseif ($jadwalkerja[0]['sifat']=="FD")
                                    {
                                        $tanggalpatokan=date("Y-m-d");
                                        $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." ".$jadwalkerja[0]['jam_masukjadwal']));
                                        $table->apel=0;                                  
                                    }
                                    else{
                                        $awal=$jadwalkerja[0]['jam_masukjadwal'];
                                        // $awal=$table->jam_masuk;
                                        $table->apel=0;                                                                      
                                    }
                                    

                                    if ($table->jam_masuk < $jadwalkerja[0]['jam_masukjadwal']) {
                                        $harike=date('N', strtotime($tanggalbaru));
                                        if (($harike==5) && ($table->jadwalkerja_id==1))
                                        {
                                            $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                            $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                            $akumulasi1=$this->kurangwaktu($keluaristirahat,$awal);
                                            $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                            $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                            $table->keluaristirahat=$keluaristirahat;
                                            $table->masukistirahat=$masukistirahat;
                                        }
                                        else
                                        {
                                            $table->keluaristirahat=null;
                                            $table->masukistirahat=null;
                                            $akumulasi=$this->kurangwaktu($awal,$jadwalkerja[0]['jam_keluarjadwal']);
                                        }
                                        
                                    }
                                    else
                                    {
                                        $harike=date('N', strtotime($tanggalbaru));
                                        if (($harike==5) && ($table->jadwalkerja_id==1))
                                        {
                                            $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                            $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                            $akumulasi1=$this->kurangwaktu($keluaristirahat,$table->jam_masuk);
                                            $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                            $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                            $table->keluaristirahat=$keluaristirahat;
                                            $table->masukistirahat=$masukistirahat;
                                        }
                                        else
                                        {
                                            $table->keluaristirahat=null;
                                            $table->masukistirahat=null;
                                            $akumulasi=$this->kurangwaktu($table->jam_masuk,$jadwalkerja[0]['jam_keluarjadwal']);    
                                        }
                                                     
                                    }

                                    // $akumulasi=$this->kurangwaktu($akhir,$jadwalkerja[0]['jam_masukjadwal']);
                                }
                                else{
                                    if ($jadwalkerja[0]['sifat']=="WA"){
                                        $awal=$jadwalkerja[0]['jam_masukjadwal'];
                                        $table->apel=1;     
                                    }
                                    elseif ($jadwalkerja[0]['sifat']=="FD")
                                    {
                                        $tanggalpatokan=date("Y-m-d");
                                        $awal=date("Y-m-d H:i:s", strtotime($tanggalpatokan." 08:00:00"));
                                        $table->apel=0;                                  
                                    }
                                    else{
                                        $awal=$jadwalkerja[0]['jam_masukjadwal'];
                                        $table->apel=0;                                                                      
                                    }

                                    $akhir=$jadwalkerja[0]['jam_keluarjadwal'];

                                    if ($table->jam_masuk < $jadwalkerja[0]['jam_masukjadwal']) {
                                        $harike=date('N', strtotime($tanggalbaru));
                                        if (($harike==5) && ($table->jadwalkerja_id==1))
                                        {
                                            $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                            $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                            $akumulasi1=$this->kurangwaktu($keluaristirahat,$awal);
                                            $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                            $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                            $table->keluaristirahat=$keluaristirahat;
                                            $table->masukistirahat=$masukistirahat;
                                        }
                                        else
                                        {
                                            $table->keluaristirahat=null;
                                            $table->masukistirahat=null;
                                            $akumulasi=$this->kurangwaktu($awal,$jadwalkerja[0]['jam_keluarjadwal']);
                                        }
                                    }
                                    else
                                    {
                                        $harike=date('N', strtotime($tanggalbaru));
                                        if (($harike==5) && ($table->jadwalkerja_id==1))
                                        {
                                            $keluaristirahat=date('H:i:s',strtotime('11:30:00'));
                                            $masukistirahat=date('H:i:s',strtotime('14:00:00'));
                                            $akumulasi1=$this->kurangwaktu($keluaristirahat,$table->jam_masuk);
                                            $akumulasi2=$this->kurangwaktu($jadwalkerja[0]['jam_keluarjadwal'],$masukistirahat);
                                            $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                                            $table->keluaristirahat=$keluaristirahat;
                                            $table->masukistirahat=$masukistirahat;
                                        }
                                        else
                                        {
                                            $table->keluaristirahat=null;
                                            $table->masukistirahat=null;
                                            $akumulasi=$this->kurangwaktu($table->jam_masuk,$jadwalkerja[0]['jam_keluarjadwal']);    
                                        }
                                        // $akumulasi=$this->kurangwaktu($table->jam_masuk,$jadwalkerja[0]['jam_keluarjadwal']);                 
                                    }

                                    // $akumulasi=$this->kurangwaktu($jadwalkerja[0]['jam_masukjadwal'],$akhir);

                                }  
                                $table->keterangankeluar_id=$request->jenisabsen;
                                $table->jenisabsen_id = "1";
                                $table->jam_keluar = $akhir;
                                $table->keluarinstansi_id=Auth::user()->instansi_id;
                                $table->akumulasi_sehari = $akumulasi;
                                $table->save();

                              }
                              elseif  ($request->jenisabsen=="13"){

                                    $table = att::where('tanggal_att', '=', $tanggalbaru)
                                        ->where('jadwalkerja_id','=',$data)
                                        ->where('id', '=', $id)
                                        ->first();

                                    $table->jenisabsen_id = $request->jenisabsen;
                                    $table->jam_masuk = null;
                                    $table->terlambat = "00:00:00";
                                    $table->masukinstansi_id=Auth::user()->instansi_id;
                                    $table->jam_keluar = null;
                                    $table->keluaristirahat=null;
                                    $table->keteranganmasuk_id=null;
                                    $table->keterangankeluar_id=null;
                                    $table->masukistirahat=null;
                                    $table->keluarinstansi_id=Auth::user()->instansi_id;
                                    $table->akumulasi_sehari = "00:00:00";
                                    $table->save();

                              }
                                
                            
                          } else {
                            return redirect()->back()->with('error','Data kehadiran tidak ditemukan !');
                          }
                      } else {
                        return redirect()->back()->with('error','Akumulasi melebihi aturan !');
                      }
                    }

              }

              $i++;
              $tanggalbaru = date("Y-m-d", (strtotime("+" . $i . "days", strtotime($tanggal[0]))));
        }

        return redirect()->back()->with('status','Atur ijin berhasil');

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
      $sakit=rekapbulanan::leftJoin('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
      ->leftJoin('instansis','rekapbulanans.instansi_id','=','instansis.id')
      ->select('rekapbulanans.*','pegawais.nip','pegawais.nama','instansis.namaInstansi')
      ->where('rekapbulanans.ijin','>',0)
      ->orWhere('rekapbulanans.sakit','>',0)
      ->orWhere('rekapbulanans.cuti','>',0)
      ->orWhere('rekapbulanans.ijinterlambat','>',0)
      ->orWhere('rekapbulanans.tugas_luar','>',0)
      ->orWhere('rekapbulanans.tugas_belajar','>',0)
      ->orWhere('rekapbulanans.rapatundangan','>',0)
      ->orWhere('rekapbulanans.ijinpulangcepat','>',0)
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

    public function attsdata(){
        // $users=pegawai::join('instansis','pegawais.instansi_id','=','instansis.id')
        //     ->get();
        $date=date('N');
        $hari=date('Y-m-d');
        $sekarang=date('Y-m-d');
        $status=false;
        if ($date==1){
            $hari='Senin';
            $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
            $akhir=$sekarang;
            $status=true;
        }
        elseif ($date==2){
            $hari='Selasa';
            $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
            $akhir=$sekarang;
            $status=true;
        }
        elseif ($date==3) {
          // dd("asda");
          $hari='Rabu';
          $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
          $akhir=$sekarang;
          $status=false;
        }
        elseif ($date==4) {
          // dd("asda");
          $hari='Kamis';
          $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
          $akhir=$sekarang;
          $status=false;
        }
        elseif ($date==5) {
          // dd("asda");
          $hari='Jumat';
          $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
          $akhir=$sekarang;
          $status=false;
        }
        elseif ($date==6) {
          // dd("asda");
          $hari='Sabtu';
          $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
          $akhir=$sekarang;
          $status=false;
        }
        elseif ($date==7) {
          // dd("asda");
          $hari='Minggu';
          $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
          $akhir=$sekarang;
          $status=false;
        }

        $userruangan=ruanganuser::where('user_id','=',Auth::user()->id)->count();
        if ($userruangan>0){
            $userruangan=ruanganuser::where('user_id','=',Auth::user()->id)->first();
            // $datadokter=dokter::pluck('pegawai_id')->all();
            $dataperawat=perawatruangan::where('ruangan_id','=',$userruangan->ruangan_id)->pluck('pegawai_id');

            $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
            ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
            ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
            ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
            ->leftJoin('jenisabsens as keteranganmasuk', 'keteranganmasuk.id','=','atts.keteranganmasuk_id')
            ->leftJoin('jenisabsens as keterangankeluar', 'keterangankeluar.id','=','atts.keterangankeluar_id')
            ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
            // ->where('atts.tanggal_att','>=',$awal)
            // ->where('atts.tanggal_att','<=',$akhir)
            ->where('atts.tanggal_att','=',$sekarang)
            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
            ->whereIn('atts.pegawai_id',$dataperawat)
            ->select('atts.*','jadwalkerjas.jenis_jadwal','pegawais.id as idpegawai','instansismasuk.namaInstansi as namainstansimasuk',
                'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama',
                'keteranganmasuk.jenis_absen as keteranganmasuk_id','keterangankeluar.jenis_absen as keterangankeluar_id')
            ->orderBy('atts.tanggal_att','desc')
            ->get();
        }
        else{
            $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
            ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
            ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
            ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
            ->leftJoin('jenisabsens as keteranganmasuk', 'keteranganmasuk.id','=','atts.keteranganmasuk_id')
            ->leftJoin('jenisabsens as keterangankeluar', 'keterangankeluar.id','=','atts.keterangankeluar_id')
            ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
            // ->where('atts.tanggal_att','>=',$awal)
            // ->where('atts.tanggal_att','<=',$akhir)
            ->where('atts.tanggal_att','=',$sekarang)
            // ->whereNotIn('pegawais.id',$dataperawat)
            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
            ->select('atts.*','jadwalkerjas.jenis_jadwal','pegawais.id as idpegawai','instansismasuk.namaInstansi as namainstansimasuk',
                'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama',
                'keteranganmasuk.jenis_absen as keteranganmasuk_id','keterangankeluar.jenis_absen as keterangankeluar_id')
            ->orderBy('atts.tanggal_att','desc')
            ->get();
        }

        

            

            return Datatables::of($atts)
                    ->editColumn('action', function ($atts) {
                        return '<input type="checkbox" name="checkboxnip[]" value="'.encrypt($atts->id).'" class="flat-red checkbox">';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

}
