<?php

namespace App\Http\Controllers;
use App\keterangan_absen;
use Illuminate\Http\Request;
use App\ruanganuser;
use App\perawatruangan;
use App\pegawai;
use App\att;
use App\jenisabsen;
use Illuminate\Support\Facades\Auth;

use App\jadwalkerja;

class KeteranganAbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
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

        $pegawai=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id);

        


        $userruangan=ruanganuser::where('user_id','=',Auth::user()->id)->count();
        if ($userruangan>0){
            $userruangan=ruanganuser::where('user_id','=',Auth::user()->id)->first();
            // $jadwalkerja_iddokter=dokter::pluck('pegawai_id')->all();
            $jadwalkerja_idperawat=perawatruangan::where('ruangan_id','=',$userruangan->ruangan_id)->pluck('pegawai_id');

            if (isset($request->text))
            {
                $pegawai=$pegawai->where('pegawais.nip','like','%'.$request->text.'%')->orWhere('pegawais.nama','like','%'.$request->text.'%')->where('pegawais.instansi_id','=',Auth::user()->instansi_id);
            }

            $pegawai=$pegawai->whereIn('pegawais.id','=',$jadwalkerja_idperawat)
                    ->select('pegawais.*','instansis.namaInstansi')
                    ->paginate(30);
        }
        else{
            if (isset($request->text))
            {
                $pegawai=$pegawai->where('pegawais.nip','like','%'.$request->text.'%')->orWhere('pegawais.nama','like','%'.$request->text.'%')->where('pegawais.instansi_id','=',Auth::user()->instansi_id);
            }

            $pegawai=$pegawai->select('pegawais.*','instansis.namaInstansi')
                    ->paginate(30);
        }

        return view('keterangan_absen.datapegawai',['pegawais'=>$pegawai,'jadwalkerjas'=>$jadwalkerjas,'text'=>$request->text]);        
    }

    public function showpegawai($pegawai_id)
    {
        //
        $pegawai_id=decrypt($pegawai_id);
        // $jadwalkerjas=jadwalkerja::where('instansi_id','=',Auth::user()->instansi_id)->get();

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
        return view('keterangan_absen.showpegawai',['pegawai_id'=>$pegawai_id,'jadwalkerjas'=>$jadwalkerjas]);
    }

    public function create($pegawai_id,$jadwalkerja_id)
    {
        //
        $pegawai=pegawai::where('id','=',decrypt($pegawai_id))->first();
        $jadwalkerja=jadwalkerja::where('id','=',decrypt($jadwalkerja_id))->first();
        if (Auth::user()->role->namaRole=="rs"){
            $jenisabsens=jenisabsen::where('jenis_absen','!=','Hadir')->paginate(3);
        }else{
            $jenisabsens=jenisabsen::where('jenis_absen','!=','Hadir')
            ->where('jenis_absen','!=','Tidak Hadir')->paginate(3);
            
        }

        return view('keterangan_absen.createketeranganabsen',['pegawai_id'=>$pegawai_id,'jadwalkerja_id'=>$jadwalkerja_id,'jadwalkerjadata'=>$jadwalkerja,'pegawai'=>$pegawai,'jenisabsens'=>$jenisabsens]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function calendardata(Request $request)
    {
        $tanggal=date('Y-m-d');
        $pegawai_id=decrypt($request->pegawai_id);
        $jadwalkerja_id=decrypt($request->jadwalkerja_id);
        //konsep
        $userruangan=ruanganuser::where('user_id','=',Auth::user()->id)->count();
        if ($userruangan>0){
            $userruangan=ruanganuser::where('user_id','=',Auth::user()->id)->first();
            // $jadwalkerja_iddokter=dokter::pluck('pegawai_id')->all();
            $jadwalkerja_idperawat=perawatruangan::where('ruangan_id','=',$userruangan->ruangan_id)->pluck('pegawai_id');

            $keteranganabsen=keterangan_absen::leftJoin('pegawais','keterangan_absens.pegawai_id','=','pegawais.id')
                        ->leftJoin('jenisabsens','jenisabsens.id','=','keterangan_absens.jenisabsen_id')
                        ->whereIn('keterangan_absens.pegawai_id',$jadwalkerja_idperawat)
                        ->where('keterangan_absens.jadwalkerja_id','=',$jadwalkerja_id)
                        ->where('keterangan_absens.pegawai_id','=',$pegawai_id)
                        // ->where('keterangan_absens.tanggal','>=',$tanggal)
                        ->select('keterangan_absens.*','pegawais.nama','jenisabsens.jenis_absen')
                        ->get();


            
        }
        else{
            
            $keteranganabsen=keterangan_absen::leftJoin('pegawais','keterangan_absens.pegawai_id','=','pegawais.id')
                        // ->whereNotIn('keterangan_absens.pegawai_id',$jadwalkerja_idperawat)
                        ->leftJoin('jenisabsens','jenisabsens.id','=','keterangan_absens.jenisabsen_id')
                        // ->where('keterangan_absens.tanggal','>=',$tanggal)
                        ->where('keterangan_absens.jadwalkerja_id','=',$jadwalkerja_id)
                        ->where('keterangan_absens.pegawai_id','=',$pegawai_id)
                        ->select('keterangan_absens.*','pegawais.nama','jenisabsens.jenis_absen')
                        ->get();
        }

        $awal=date('Y-m-d',strtotime($request->start));
        $akhir=date('Y-m-d',strtotime($request->end));
        $event=array();


        foreach ($keteranganabsen as $data){
            $e=array();
            $banyak=$this->difftanggal($data->tanggal,$data->tanggal);
            // $banyak=$this->difftanggal($awal,$akhir);
            // dd($banyak);
            for ($x = 0; $x < $banyak+1; $x++)
            {
                if (($awal <= date('Y-m-d', strtotime($data->tanggal.' +'.$x.' day '))) && ($akhir>=date('Y-m-d', strtotime($data->tanggal.' +'.$x.' day ')))) {
                    $e['id']=encrypt($data->id);
                    $e['title']=$data->jenis_absen;
                    // $tanggalawal=date('Y-m-d',strtotime($data->tanggal_awalrule.' '.$x.' day'));
                    $e['start']=date('Y-m-d H:i:s', strtotime($data->tanggal.' +'.$x.' day 00:00:00'));
                    $e['end']=date('Y-m-d H:i:s', strtotime($data->tanggal.' +'.$x.' day 23:59:59'));

                    // $e['start']=date('Y-m-d H:i:s', strtotime($awal.' '.$x.' day '.$data->jam_masukjadwal));
                    // $e['end']=date('Y-m-d H:i:s', strtotime($awal.' '.$x.' day '.$data->jam_keluarjadwal));
                    $e['allDay']=false;

                    $warna=rtrim("rgb(221, 75, 57)",")");
                    $warna2=ltrim($warna,"rgb(");
                    $warna3=explode(", ",$warna2);

                    $warna4=$this->fromRGB($warna3[0],$warna3[1],$warna3[2]);
                    $e['backgroundColor']=$warna4;
                    $e['borderColor']=$warna4;
                    array_push($event,$e);
                }
            }
        }
        // dd($event);
        return $event;

    }

    public function storecalendardata(Request $request)
    {
        $pegawai_id=decrypt($request->pegawai_id);
        $jadwalkerja_id=decrypt($request->jadwalkerja_id);
        $tanggal=$request->awalrule;
        $jenisabsen_id=decrypt($request->jenisabsen_id);
        // dd($jadwalkerja_id);
        $tanggalhariini=date('Y-m-d');

        // if ((($jenisabsen_id=="10") || ($jenisabsen_id=="12")) && ($tanggal!=$tanggalhariini))
        // {
        //     return response()->json("failed");
        // }

        $tanggalbulan=date('Y-m',strtotime($tanggal));
        $tanggalhariinibulan=date('Y-m',strtotime($tanggalhariini));

        // if ($tanggal < $tanggalhariini)
        // {
        //     return response()->json("failed");
        // }
        

        if ($tanggalbulan != $tanggalhariinibulan)
        {
            return response()->json("failed1");
        }
        


        $caridata=att::where('pegawai_id','=',$pegawai_id)
                    ->where('jadwalkerja_id','=',$jadwalkerja_id)
                    ->first();
        $cariketeranganabsen=keterangan_absen::where('tanggal','=',$tanggal)
                            ->where('pegawai_id','=',$pegawai_id)
                            ->where('jadwalkerja_id','=',$jadwalkerja_id)
                            ->where('jenisabsen_id','=',$jenisabsen_id)
                            ->first();

        $cekketeranganabsen=keterangan_absen::where('tanggal','=',$tanggal)
                            ->where('pegawai_id','=',$pegawai_id)
                            ->where('jadwalkerja_id','=',$jadwalkerja_id)
                            ->where('jenisabsen_id','!=',"10")
                            ->where('jenisabsen_id','!=',"12")
                            ->first();

        if (($cekketeranganabsen!=null))
        {
            return response()->json("failed2");
        }

        if (($cariketeranganabsen!=null))
        {
            return response()->json("failed3");
        }

        $cariketeranganabsen2=keterangan_absen::where('tanggal','=',$tanggal)
                            ->where('pegawai_id','=',$pegawai_id)
                            ->where('jadwalkerja_id','=',$jadwalkerja_id)
                            ->where('jenisabsen_id','=',"10")
                            ->orWhere('jenisabsen_id','=',"12")
                            ->first();
        if ($cariketeranganabsen2!=null)
        {
            return response()->json("failed4");
        }

        if ($caridata==null)
        {
            return response()->json("failed5");
        }
        else
        {
            if ($jenisabsen_id == "2") {
                $table = att::where('tanggal_att', '=', $tanggal)
                    // ->where('id', '=', $id)
                    ->where('pegawai_id','=',$pegawai_id)
                    ->where('jadwalkerja_id','=',$jadwalkerja_id)
                    ->first();
                $table->jenisabsen_id = $jenisabsen_id;
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
            } 
            elseif  ($jenisabsen_id=="3"){

                  $jadwalkerja=jadwalkerja::where('id','=',$jadwalkerja_id)->get();

                  $table = att::where('tanggal_att', '=', $tanggal)
                    ->where('jadwalkerja_id','=',$jadwalkerja_id)
                    ->where('pegawai_id','=',$pegawai_id)
                    // ->where('id', '=', $id)
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
                          $harike=date('N', strtotime($tanggal));
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
                          $harike=date('N', strtotime($tanggal));
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

                          $harike=date('N', strtotime($tanggal));
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
                          $harike=date('N', strtotime($tanggal));
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

                  // $table->jenisabsen_id = $jenisabsen_id;
                  // $table->jam_masuk = $awal;
                  // $table->masukinstansi_id=Auth::user()->instansi_id;
                  // $table->jam_keluar = $akhir;
                  // $table->terlambat = "00:00:00";
                  // $table->keluarinstansi_id=Auth::user()->instansi_id;
                  // $table->akumulasi_sehari = $akumulasi;
                  // $table->save();

                  $table->jenisabsen_id = $jenisabsen_id;
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
            elseif  ($jenisabsen_id=="4"){

              $jadwalkerja=jadwalkerja::where('id','=',$jadwalkerja_id)->get();

              $table = att::where('tanggal_att', '=', $tanggal)
                ->where('jadwalkerja_id','=',$jadwalkerja_id)
                // ->where('id', '=', $id)
                ->where('pegawai_id','=',$pegawai_id)
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
                      $harike=date('N', strtotime($tanggal));
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
                      $harike=date('N', strtotime($tanggal));
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

                      $harike=date('N', strtotime($tanggal));
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
                      $harike=date('N', strtotime($tanggal));
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

              // $table->jenisabsen_id = $jenisabsen_id;
              // $table->jam_masuk = $awal;
              // $table->masukinstansi_id=Auth::user()->instansi_id;
              // $table->jam_keluar = $akhir;
              // $table->terlambat = "00:00:00";
              // $table->keluarinstansi_id=Auth::user()->instansi_id;
              // $table->akumulasi_sehari = $akumulasi;
              // $table->save();

              $table->jenisabsen_id = $jenisabsen_id;
              $table->jam_masuk = null;
              $table->terlambat = "00:00:00";
              $table->keteranganmasuk_id=null;
              $table->keterangankeluar_id=null;
              $table->keluaristirahat=null;
              $table->masukistirahat=null;
              $table->masukinstansi_id=Auth::user()->instansi_id;
              $table->jam_keluar = null;
              $table->keluarinstansi_id=Auth::user()->instansi_id;
              $table->akumulasi_sehari = "00:00:00";
              $table->save();
            }
            elseif  ($jenisabsen_id=="5"){

              $jadwalkerja=jadwalkerja::where('id','=',$jadwalkerja_id)->get();

              $table = att::where('tanggal_att', '=', $tanggal)
                ->where('jadwalkerja_id','=',$jadwalkerja_id)
                // ->where('id', '=', $id)
                ->where('pegawai_id','=',$pegawai_id)

                ->first();
                // dd($table);
                
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
                      $harike=date('N', strtotime($tanggal));
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
                      $harike=date('N', strtotime($tanggal));
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

                      $harike=date('N', strtotime($tanggal));
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
                      $harike=date('N', strtotime($tanggal));
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

              // $table->jenisabsen_id = $jenisabsen_id;
              // $table->jam_masuk = $awal;
              // $table->masukinstansi_id=Auth::user()->instansi_id;
              // $table->jam_keluar = $akhir;
              // $table->terlambat = "00:00:00";
              // $table->keluarinstansi_id=Auth::user()->instansi_id;
              // $table->akumulasi_sehari = $akumulasi;
              // $table->save();

              $table->jenisabsen_id = $jenisabsen_id;
              $table->jam_masuk = null;
              $table->terlambat = "00:00:00";
              $table->keluaristirahat=null;
              $table->keteranganmasuk_id=null;
              $table->keterangankeluar_id=null;
              $table->masukistirahat=null;
              $table->masukinstansi_id=Auth::user()->instansi_id;
              $table->jam_keluar = null;
              $table->keluarinstansi_id=Auth::user()->instansi_id;
              $table->akumulasi_sehari = "00:00:00";
              $table->save();
            }
            elseif  ($jenisabsen_id=="6"){
              $jadwalkerja=jadwalkerja::where('id','=',$jadwalkerja_id)->get();

                  $table = att::where('tanggal_att', '=', $tanggal)
                    ->where('jadwalkerja_id','=',$jadwalkerja_id)
                    // ->where('id', '=', $id)
                    ->where('pegawai_id','=',$pegawai_id)
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
                          $harike=date('N', strtotime($tanggal));
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
                          $harike=date('N', strtotime($tanggal));
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

                          $harike=date('N', strtotime($tanggal));
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
                          $harike=date('N', strtotime($tanggal));
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

                  // $table->jenisabsen_id = $jenisabsen_id;
                  // $table->jam_masuk = $awal;
                  // $table->masukinstansi_id=Auth::user()->instansi_id;
                  // $table->jam_keluar = $akhir;
                  // $table->terlambat = "00:00:00";
                  // $table->keluarinstansi_id=Auth::user()->instansi_id;
                  // $table->akumulasi_sehari = $akumulasi;
                  // $table->save();

                  $table->jenisabsen_id = $jenisabsen_id;
                  $table->jam_masuk = null;
                  $table->terlambat = "00:00:00";
                  $table->keluaristirahat=null;
                  $table->keteranganmasuk_id=null;
                  $table->keterangankeluar_id=null;
                  $table->masukistirahat=null;
                  $table->masukinstansi_id=Auth::user()->instansi_id;
                  $table->jam_keluar = null;
                  $table->keluarinstansi_id=Auth::user()->instansi_id;
                  $table->akumulasi_sehari = "00:00:00";
                  $table->save();
            }
            elseif  ($jenisabsen_id=="7"){

              $jadwalkerja=jadwalkerja::where('id','=',$jadwalkerja_id)->get();

                  $table = att::where('tanggal_att', '=', $tanggal)
                    ->where('jadwalkerja_id','=',$jadwalkerja_id)
                    // ->where('id', '=', $id)
                    ->where('pegawai_id','=',$pegawai_id)

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
                          $harike=date('N', strtotime($tanggal));
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
                          $harike=date('N', strtotime($tanggal));
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

                          $harike=date('N', strtotime($tanggal));
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
                          $harike=date('N', strtotime($tanggal));
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

                  // $table->jenisabsen_id = $jenisabsen_id;
                  // $table->jam_masuk = $awal;
                  // $table->masukinstansi_id=Auth::user()->instansi_id;
                  // $table->jam_keluar = $akhir;
                  // $table->terlambat = "00:00:00";
                  // $table->keluarinstansi_id=Auth::user()->instansi_id;
                  // $table->akumulasi_sehari = $akumulasi;
                  // $table->save();

                  $table->jenisabsen_id = $jenisabsen_id;
                  $table->jam_masuk = null;
                  $table->terlambat = "00:00:00";
                  $table->keteranganmasuk_id=null;
                  $table->keterangankeluar_id=null;
                  $table->keluaristirahat=null;
                  $table->masukistirahat=null;
                  $table->masukinstansi_id=Auth::user()->instansi_id;
                  $table->jam_keluar = null;
                  $table->keluarinstansi_id=Auth::user()->instansi_id;
                  $table->akumulasi_sehari = "00:00:00";
                  $table->save();
            }
            elseif  ($jenisabsen_id=="8"){

              $jadwalkerja=jadwalkerja::where('id','=',$jadwalkerja_id)->get();

                  $table = att::where('tanggal_att', '=', $tanggal)
                    ->where('jadwalkerja_id','=',$jadwalkerja_id)
                    // ->where('id', '=', $id)
                    ->where('pegawai_id','=',$pegawai_id)
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
                          $harike=date('N', strtotime($tanggal));
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
                          $harike=date('N', strtotime($tanggal));
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

                          $harike=date('N', strtotime($tanggal));
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
                          $harike=date('N', strtotime($tanggal));
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

                  // $table->jenisabsen_id = $jenisabsen_id;
                  // $table->jam_masuk = $awal;
                  // $table->masukinstansi_id=Auth::user()->instansi_id;
                  // $table->jam_keluar = $akhir;
                  // $table->terlambat = "00:00:00";
                  // $table->keluarinstansi_id=Auth::user()->instansi_id;
                  // $table->akumulasi_sehari = $akumulasi;
                  // $table->save();

                  $table->jenisabsen_id = $jenisabsen_id;
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
            elseif  ($jenisabsen_id=="9"){
                $table = att::where('tanggal_att', '=', $tanggal)
                    ->where('jadwalkerja_id','=',$jadwalkerja_id)
                    // ->where('id', '=', $id)
                    ->where('pegawai_id','=',$pegawai_id)

                    ->first();

                $table->jenisabsen_id = $jenisabsen_id;
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
            elseif  ($jenisabsen_id=="10"){

                  $jadwalkerja=jadwalkerja::where('id','=',$jadwalkerja_id)->get();
                  //dd($jadwalkerja);
                  $jamkeluar = att::where('tanggal_att', '=', $tanggal)
                    ->where('jadwalkerja_id','=',$jadwalkerja_id)
                    // ->where('id', '=', $id)
                    ->where('pegawai_id','=',$pegawai_id)
                    ->first();
                  $table = att::where('tanggal_att', '=', $tanggal)
                    ->where('jadwalkerja_id','=',$jadwalkerja_id)
                    // ->where('id', '=', $id)
                    ->where('pegawai_id','=',$pegawai_id)

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
                $table->masukinstansi_id=Auth::user()->instansi_id;
                $table->terlambat = "00:00:00";

                // $table->keteranganmasuk_id="10";
                $table->keterangankeluar_id=null;
                $table->keteranganmasuk_id=$jenisabsen_id;
              //   if ($table->jam_keluar!=null){
              //     $harike=date('N', strtotime($tanggal));
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

              //   $table->jenisabsen_id = $jenisabsen_id;
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
            elseif  ($jenisabsen_id=="11"){

                $table = att::where('tanggal_att', '=', $tanggal)
                    ->where('jadwalkerja_id','=',$jadwalkerja_id)
                    // ->where('id', '=', $id)
                    ->where('pegawai_id','=',$pegawai_id)

                    ->first();

              //   $table->jenisabsen_id = $jenisabsen_id;
              //   $table->jam_masuk = null;
              //   $table->terlambat = "00:00:00";
              //   $table->masukinstansi_id=Auth::user()->instansi_id;
              //   $table->jam_keluar = null;
              //   $table->keluaristirahat=null;
              //   $table->masukistirahat=null;
              //   $table->keluarinstansi_id=Auth::user()->instansi_id;
              //   $table->akumulasi_sehari = "00:00:00";
              //   $table->save();

                $table->jenisabsen_id = $jenisabsen_id;
                $table->jam_masuk = null;
                $table->terlambat = "00:00:00";
                $table->keluaristirahat=null;
                $table->masukistirahat=null;
                $table->masukinstansi_id=Auth::user()->instansi_id;
                $table->jam_keluar = null;
                $table->keluarinstansi_id=Auth::user()->instansi_id;
                $table->keteranganmasuk_id=null;
                $table->keterangankeluar_id=null;
                $table->akumulasi_sehari = "00:00:00";
                $table->save();

            }
            elseif  ($jenisabsen_id=="12"){

              $jadwalkerja=jadwalkerja::where('id','=',$jadwalkerja_id)->get();

              $table = att::where('tanggal_att', '=', $tanggal)
                  ->where('jadwalkerja_id','=',$jadwalkerja_id)
                //   ->where('id', '=', $id)
                ->where('pegawai_id','=',$pegawai_id)

                  ->first();

              if ($table->jam_masuk==null){
                return response()->json("failed5");
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
                      $harike=date('N', strtotime($tanggal));
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
                      $harike=date('N', strtotime($tanggal));
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
                      $harike=date('N', strtotime($tanggal));
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
                      $harike=date('N', strtotime($tanggal));
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
              $table->keterangankeluar_id=$jenisabsen_id;
              $table->jenisabsen_id = "1";
              $table->jam_keluar = $akhir;
              $table->keluarinstansi_id=Auth::user()->instansi_id;
              $table->akumulasi_sehari = $akumulasi;
              $table->save();

            }
            elseif  ($jenisabsen_id=="13"){

                  $table = att::where('tanggal_att', '=', $tanggal)
                      ->where('jadwalkerja_id','=',$jadwalkerja_id)
                    //   ->where('id', '=', $id)
                    ->where('pegawai_id','=',$pegawai_id)

                      ->first();

                  $table->jenisabsen_id = $jenisabsen_id;
                  $table->jam_masuk = null;
                  $table->terlambat = "00:00:00";
                  $table->masukinstansi_id=Auth::user()->instansi_id;
                  $table->jam_keluar = null;
                  $table->keluaristirahat=null;
                  $table->masukistirahat=null;
                  $table->keluarinstansi_id=Auth::user()->instansi_id;
                  $table->akumulasi_sehari = "00:00:00";
                  $table->save();

            }
        }

        $keteranganabsen=new keterangan_absen();
        $keteranganabsen->tanggal=$tanggal;
        $keteranganabsen->jadwalkerja_id=$jadwalkerja_id;
        $keteranganabsen->pegawai_id=$pegawai_id;
        $keteranganabsen->jenisabsen_id=$jenisabsen_id;
        

        if ($keteranganabsen->save())
        {
            return response()->json("success");
        }
        else
        {
            return response()->json("failed6");
        }




    }


    public function destroycalendardata(Request $request)
    {
        //
        $id=decrypt($request->id);


        $tanggalhariini=date('Y-m-d');

        
        
        $getketeranganabsen=keterangan_absen::where('id','=',$id)
                            ->first();

        
        // dd($getketeranganabsen);

        if (($getketeranganabsen==null))
        {
            return response()->json("failed");
        }
        // if ($getketeranganabsen->tanggal < $tanggalhariini)
        // {
        //     return response()->json("failed");
        // }

        $getatt=att::where('pegawai_id','=',$getketeranganabsen->pegawai_id)
                    ->where('jadwalkerja_id','=',$getketeranganabsen->jadwalkerja_id)
                    ->where('tanggal_att','=',$getketeranganabsen->tanggal)
                    ->first();
        if ($getatt==null)
        {
            return response()->json("failed");
        }
        else
        {
            if ($getketeranganabsen->jenisabsen_id==10)
            {
                $getatt->jenisabsen_id = "2";
                $getatt->jam_masuk = null;
                $getatt->keluaristirahat=null;
                $getatt->masukistirahat=null;
                $getatt->keteranganmasuk_id=null;
                $getatt->keterangankeluar_id=null;
                $getatt->apel=0;                                  
                $getatt->masukinstansi_id=Auth::user()->instansi_id;
                $getatt->jam_keluar = null;
                $getatt->terlambat = "00:00:00";
                $getatt->keluarinstansi_id=Auth::user()->instansi_id;
                $getatt->akumulasi_sehari = "00:00:00";
                $getatt->save();

                $deleteketeranganabsen=keterangan_absen::where('tanggal','=',$getketeranganabsen->tanggal)
                                        ->where('pegawai_id','=',$getketeranganabsen->pegawai_id)
                                        ->where('jadwalkerja_id','=',$getketeranganabsen->jadwalkerja_id)
                                        ->where('jenisabsen_id','=',"12")
                                        ->first();
                if ($deleteketeranganabsen!=null)
                {
                    $deleteketeranganabsen->delete();
                }
            }
            elseif ($getketeranganabsen->jenisabsen_id==12)
            {

                $getatt->keterangankeluar_id=null;
                $getatt->jam_keluar = null;
                $getatt->keluarinstansi_id=Auth::user()->instansi_id;
                $getatt->akumulasi_sehari = "00:00:00";
                $getatt->save();
            }
            else
            {
                $getatt->jenisabsen_id = "2";
                $getatt->jam_masuk = null;
                $getatt->keluaristirahat=null;
                $getatt->masukistirahat=null;
                $getatt->keteranganmasuk_id=null;
                $getatt->keterangankeluar_id=null;
                $getatt->apel=0;                                  
                $getatt->masukinstansi_id=Auth::user()->instansi_id;
                $getatt->jam_keluar = null;
                $getatt->terlambat = "00:00:00";
                $getatt->keluarinstansi_id=Auth::user()->instansi_id;
                $getatt->akumulasi_sehari = "00:00:00";
                $getatt->save();
            }

            
        }

        

        if ($getketeranganabsen->delete())
        {
            return response()->json("success");
        }
        else
        {
            return response()->json("failed");
        }

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
