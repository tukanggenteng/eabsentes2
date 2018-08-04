<?php

namespace App\Http\Controllers;

use App\pegawai;
use App\att;
use App\instansi;
use App\masterbulanan;
use App\jadwalkerja;
use App\jenisabsen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Excel;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class MonitoringController extends Controller
{
    //untuk grafik harin
    public function grafikmonitoringharian()
    {
        return view('monitoring.grafik.monitoringgrafikharian');    
    }

    public function grafikmonitoringhariandata(Request $request)
    {
        //dd($request);
        $tanggal=$request->tanggal;
        $instansi=$request->instansi_id;
        $data=array();
        $datasets=array();
        $data['tanpakabar']=[];
        for ($i=0;$i<=9;$i++)
        {
            $subdata=array();
            $angka=9;
            $tanggal2=date("Y-m-d",strtotime($i-$angka." days",strtotime($tanggal)));
            array_push($datasets,$tanggal2);
            //dd($tanggal2);   
            $tanpakabar= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->where('atts.tanggal_att','=',$tanggal2)
                            ->where('pegawais.instansi_id','=',$instansi)  
                            ->where('atts.jenisabsen_id','=','2')
                            ->count();
            //$subdata['tanpakabar']=$tanpakabar;
            array_push($data['tanpakabar'],$tanpakabar);
        }
        //dd($tanpakabar);
        //dd($tanggal." ".$instansi);
        //dd($datasets);

        $data['harikerja']=[];
        for ($i=0;$i<=9;$i++)
        {
            $subdata=array();
            $angka=9;
            $tanggal2=date("Y-m-d",strtotime($i-$angka." days",strtotime($tanggal)));
               
            $tanpakabar= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->where('atts.tanggal_att','=',$tanggal2)
                            ->where('pegawais.instansi_id','=',$instansi)  
                            ->where('atts.jenisabsen_id','!=','13')
                            ->where('atts.jenisabsen_id','!=','11')
                            ->where('atts.jenisabsen_id','!=','9')
                            ->count();
            //$subdata['tanpakabar']=$tanpakabar;
            array_push($data['harikerja'],$tanpakabar);
        }


        $data['ijin']=[];
        for ($i=0;$i<=9;$i++)
        {
            $subdata=array();
            $angka=9;
            $tanggal2=date("Y-m-d",strtotime($i-$angka." days",strtotime($tanggal)));
            $subdata['label']=$tanggal2;
               
            $count= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->where('atts.tanggal_att','=',$tanggal2)
                            ->where('pegawais.instansi_id','=',$instansi)  
                            ->where('atts.jenisabsen_id','=','3')
                            ->count();
            array_push($data['ijin'],$count);
        }
        
        $data['hadir']=[];
        for ($i=0;$i<=9;$i++)
        {
            $subdata=array();
            $angka=9;
            $tanggal2=date("Y-m-d",strtotime($i-$angka." days",strtotime($tanggal)));
            $subdata['label']=$tanggal2;
               
            $count= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->where('atts.tanggal_att','=',$tanggal2)
                            ->where('pegawais.instansi_id','=',$instansi)  
                            ->where('atts.jenisabsen_id','=','1')
                            ->count();
            array_push($data['hadir'],$count);
        }


        $data['ijinterlambat']=[];
        for ($i=0;$i<=9;$i++)
        {
            $subdata=array();
            $angka=9;
            $tanggal2=date("Y-m-d",strtotime($i-$angka." days",strtotime($tanggal)));
            $subdata['label']=$tanggal2;
               
            $count= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->where('atts.tanggal_att','=',$tanggal2)
                            ->where('pegawais.instansi_id','=',$instansi)  
                            ->where('atts.jenisabsen_id','=','10')
                            ->count();
            array_push($data['ijinterlambat'],$count);
        }

        $data['sakit']=[];
        for ($i=0;$i<=9;$i++)
        {
            $subdata=array();
            $angka=9;
            $tanggal2=date("Y-m-d",strtotime($i-$angka." days",strtotime($tanggal)));
            $subdata['label']=$tanggal2;
               
            $count= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->where('atts.tanggal_att','=',$tanggal2)
                            ->where('pegawais.instansi_id','=',$instansi)  
                            ->where('atts.jenisabsen_id','=','5')
                            ->count();
            array_push($data['sakit'],$count);
        }

        $data['tl']=[];
        for ($i=0;$i<=9;$i++)
        {
            $subdata=array();
            $angka=9;
            $tanggal2=date("Y-m-d",strtotime($i-$angka." days",strtotime($tanggal)));
            $subdata['label']=$tanggal2;
               
            $count= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->where('atts.tanggal_att','=',$tanggal2)
                            ->where('pegawais.instansi_id','=',$instansi)  
                            ->where('atts.jenisabsen_id','=','7')
                            ->count();
            array_push($data['tl'],$count);
        }


        $data['tb']=[];
        for ($i=0;$i<=9;$i++)
        {
            $subdata=array();
            $angka=9;
            $tanggal2=date("Y-m-d",strtotime($i-$angka." days",strtotime($tanggal)));
            $subdata['label']=$tanggal2;
               
            $count= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->where('atts.tanggal_att','=',$tanggal2)
                            ->where('pegawais.instansi_id','=',$instansi)  
                            ->where('atts.jenisabsen_id','=','6')
                            ->count();
            array_push($data['tb'],$count);
        }

        $data['terlambat']=[];
        for ($i=0;$i<=9;$i++)
        {
            $subdata=array();
            $angka=9;
            $tanggal2=date("Y-m-d",strtotime($i-$angka." days",strtotime($tanggal)));
            $subdata['label']=$tanggal2;
               
            $count= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->where('atts.tanggal_att','=',$tanggal2)
                            ->where('pegawais.instansi_id','=',$instansi)
                            ->where('atts.terlambat','!=','00:00:00')  
                            ->count();
            array_push($data['terlambat'],$count);
        }

        $data['rapat']=[];
        for ($i=0;$i<=9;$i++)
        {
            $subdata=array();
            $angka=9;
            $tanggal2=date("Y-m-d",strtotime($i-$angka." days",strtotime($tanggal)));
            $subdata['label']=$tanggal2;
               
            $count= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->where('atts.tanggal_att','=',$tanggal2)
                            ->where('pegawais.instansi_id','=',$instansi)
                            ->where('atts.jenisabsen_id','=','8')  
                            ->count();
            array_push($data['rapat'],$count);
        }
            

        $data['pulangcepat']=[];
        for ($i=0;$i<=9;$i++)
        {
            $subdata=array();
            $angka=9;
            $tanggal2=date("Y-m-d",strtotime($i-$angka." days",strtotime($tanggal)));
            $subdata['label']=$tanggal2;
               
            $count=  att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                    ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                                    ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                                    ->where('atts.jam_keluar', '<', 'jadwalkerjas.jam_keluarjadwal')
                                    ->where('atts.jam_keluar', '=', null)
                                    ->where('atts.tanggal_att','=',$tanggal2)
                                    ->where('atts.jenisabsen_id', '=', '1')
                                    ->where('pegawais.instansi_id','=',$instansi)
                                    ->whereNotNull('atts.jam_masuk')
                                    ->count();
            array_push($data['pulangcepat'],$count);
        }

        $data['apel']=[];
        for ($i=0;$i<=9;$i++)
        {
            $subdata=array();
            $angka=9;
            $tanggal2=date("Y-m-d",strtotime($i-$angka." days",strtotime($tanggal)));
            $subdata['label']=$tanggal2;
               
            $count=  att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                    //->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                                    //->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                                    ->where('atts.tanggal_att','=',$tanggal2)
                                    ->where('pegawais.instansi_id','=',$instansi)
                                    // ->where('atts.jenisabsen_id','=',1)
                                    // ->where('atts.jenisabsen_id','!=',2)
                                    // ->where('atts.jenisabsen_id','!=',9)
                                    // ->where('atts.jenisabsen_id','!=',11)
                                    ->where('atts.apel','=',1)
                                    ->whereNotNull('atts.apel')
                                    // ->where('atts.jenisabsen_id',$tanpaabsen)
                                    ->count();
            array_push($data['apel'],$count);
        }
        $data['datasets']=$datasets;
        $data['pegawai']=pegawai::where('instansi_id','=',$instansi)->count();
        return $data;
    }

    public function grafikmonitoringbulan()
    {
        return view('monitoring.grafik.monitoringgrafikbulan');
    }

    public function grafikmonitoringbulandata(Request $request)
    {
        $tanggal=$request->tanggal."-01";
        $instansi=$request->instansi_id;
        $data=array();
        //dd($tanggal);
        $datasets=array();
        $data['tanpakabar']=[];
        for ($i=0;$i<=12;$i++)
        {
            $subdata=array();
            $angka=12;
            $tanggal2=date("Y-m",strtotime($i-$angka." months",strtotime($tanggal)));
            
            $tanggal3=explode("-",$tanggal2);

            $bulan=$tanggal3[1];
            $tahun=$tanggal3[0];

            array_push($datasets,$tanggal2);

            // $tanpakabar=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
            //             ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            //             ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
            //             ->select(
            //                     DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja')
            //             )
            //             ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
            //             // ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                               
            //             ->whereMonth('atts.tanggal_att','=',$bulan)
            //             ->whereYear('atts.tanggal_att','=',$tahun)
            //             // ->whereYear('atts.tanggal_att','=',$tanggal[1])
            //             ->where('pegawais.instansi_id','=',$instansi)
            //             ->count();
            
            $tanpakabar= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->where('pegawais.instansi_id','=',$instansi)  
                        ->where('atts.jenisabsen_id','=','2')
                        ->count();            
                        

            //$subdata['tanpakabar']=$tanpakabar;
            array_push($data['tanpakabar'],$tanpakabar);
        }
        

        $data['harikerja']=[];
        for ($i=0;$i<=12;$i++)
        {
            $subdata=array();
            $angka=12;
            $tanggal2=date("Y-m-d",strtotime($i-$angka." months",strtotime($tanggal)));

            $tanggal3=explode("-",$tanggal2);

            $bulan=$tanggal3[1];
            $tahun=$tanggal3[0];

            // dd($bulan." + ".$tahun);

            $tanpakabar= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->whereMonth('atts.tanggal_att','=',$bulan)
                            ->whereYear('atts.tanggal_att','=',$tahun)
                            ->where('pegawais.instansi_id','=',$instansi)  
                            // ->where('atts.jenisabsen_id','!=','13')
                            // ->where('atts.jenisabsen_id','!=','11')
                            // ->where('atts.jenisabsen_id','!=','9')
                            ->count();
            // dd($tanpakabar);
            //$subdata['tanpakabar']=$tanpakabar;
            array_push($data['harikerja'],$tanpakabar);
        }

        $data['ijin']=[];
        for ($i=0;$i<=12;$i++)
        {
            $subdata=array();
            $angka=12;
            $tanggal2=date("Y-m",strtotime($i-$angka." months",strtotime($tanggal)));
            
            $tanggal3=explode("-",$tanggal2);

            $bulan=$tanggal3[1];
            $tahun=$tanggal3[0];

            $subdata['label']=$tanggal2;
               
            // $tanpakabar=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
            //             ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            //             ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
            //             ->select(
            //                     DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin')
            //             )
            //             ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
            //             // ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                               
            //             ->whereMonth('atts.tanggal_att','=',$bulan)
            //             ->whereYear('atts.tanggal_att','=',$tahun)
            //             // ->whereYear('atts.tanggal_att','=',$tanggal[1])
            //             ->where('pegawais.instansi_id','=',$instansi)
            //             ->count();

            $count= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->where('pegawais.instansi_id','=',$instansi)  
                            ->whereMonth('atts.tanggal_att','=',$bulan)
                            ->whereYear('atts.tanggal_att','=',$tahun)
                            ->where('atts.jenisabsen_id','=','3')
                            ->count();
            array_push($data['ijin'],$count);
        }
        
        $data['hadir']=[];
        for ($i=0;$i<=12;$i++)
        {
            $subdata=array();
            $angka=12;
            $tanggal2=date("Y-m",strtotime($i-$angka." months",strtotime($tanggal)));
            
            $tanggal3=explode("-",$tanggal2);

            $bulan=$tanggal3[1];
            $tahun=$tanggal3[0];

            $subdata['label']=$tanggal2;
               
            // $tanpakabar=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
            //             ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            //             ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
            //             ->select(                   
            //                     DB::raw('count(if (atts.jenisabsen_id = "1",1,null)) as hadir')
            //             )
            //             ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
            //             // ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                               
            //             ->whereMonth('atts.tanggal_att','=',$bulan)
            //             ->whereYear('atts.tanggal_att','=',$tahun)
            //             // ->whereYear('atts.tanggal_att','=',$tanggal[1])
            //             ->where('pegawais.instansi_id','=',$instansi)
            //             ->get();

            $count= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->whereMonth('atts.tanggal_att','=',$bulan)
                            ->whereYear('atts.tanggal_att','=',$tahun)
                            ->where('pegawais.instansi_id','=',$instansi)  
                            ->where('atts.jenisabsen_id','=','1')
                            ->count();
            array_push($data['hadir'],$count);
        }


        $data['ijinterlambat']=[];
        for ($i=0;$i<=12;$i++)
        {
            $subdata=array();
            $angka=12;
            $tanggal2=date("Y-m",strtotime($i-$angka." months",strtotime($tanggal)));
            
            $tanggal3=explode("-",$tanggal2);

            $bulan=$tanggal3[1];
            $tahun=$tanggal3[0];

            $subdata['label']=$tanggal2;
               
            // $count=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
            //             ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            //             ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
            //             ->select(
            //                     DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat')
            //             )
            //             ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
            //             // ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                               
            //             ->whereMonth('atts.tanggal_att','=',$bulan)
            //             ->whereYear('atts.tanggal_att','=',$tahun)
            //             // ->whereYear('atts.tanggal_att','=',$tanggal[1])
            //             ->where('pegawais.instansi_id','=',$instansi)
            //             ->count();

            $count= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->whereMonth('atts.tanggal_att','=',$bulan)
                            ->whereYear('atts.tanggal_att','=',$tahun)
                            ->where('pegawais.instansi_id','=',$instansi)  
                            ->where('atts.jenisabsen_id','=','10')
                            ->count();
            array_push($data['ijinterlambat'],$count);
        }

        $data['sakit']=[];
        for ($i=0;$i<=12;$i++)
        {
            $subdata=array();
            $angka=12;
            $tanggal2=date("Y-m",strtotime($i-$angka." months",strtotime($tanggal)));
            
            $tanggal3=explode("-",$tanggal2);

            $bulan=$tanggal3[1];
            $tahun=$tanggal3[0];

            $subdata['label']=$tanggal2;

            // $count=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
            //             ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            //             ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
            //             ->select(
            //                     DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit')
            //             )
            //             ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
            //             // ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                               
            //             ->whereMonth('atts.tanggal_att','=',$bulan)
            //             ->whereYear('atts.tanggal_att','=',$tahun)
            //             // ->whereYear('atts.tanggal_att','=',$tanggal[1])
            //             ->where('pegawais.instansi_id','=',$instansi)
            //             ->count();
               
            $count= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->whereMonth('atts.tanggal_att','=',$bulan)
                            ->whereYear('atts.tanggal_att','=',$tahun)
                            ->where('pegawais.instansi_id','=',$instansi)  
                            ->where('atts.jenisabsen_id','=','5')
                            ->count();
            array_push($data['sakit'],$count);
        }

        $data['tl']=[];
        for ($i=0;$i<=12;$i++)
        {
            $subdata=array();
            $angka=12;
            $tanggal2=date("Y-m",strtotime($i-$angka." months",strtotime($tanggal)));
            
            $tanggal3=explode("-",$tanggal2);

            $bulan=$tanggal3[1];
            $tahun=$tanggal3[0];
            $subdata['label']=$tanggal2;
               
            // $count=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
            //             ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            //             ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
            //             ->select(
            //                     DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar')
            //             )
            //             ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
            //             // ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                               
            //             ->whereMonth('atts.tanggal_att','=',$bulan)
            //             ->whereYear('atts.tanggal_att','=',$tahun)
            //             // ->whereYear('atts.tanggal_att','=',$tanggal[1])
            //             ->where('pegawais.instansi_id','=',$instansi)
            //             ->count();

            $count= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->where('pegawais.instansi_id','=',$instansi)  
                            ->whereMonth('atts.tanggal_att','=',$bulan)
                            ->whereYear('atts.tanggal_att','=',$tahun)
                            ->where('atts.jenisabsen_id','=','7')
                            ->count();
            array_push($data['tl'],$count);
        }


        $data['tb']=[];
        for ($i=0;$i<=12;$i++)
        {
            $subdata=array();
            $angka=12;
            $tanggal2=date("Y-m",strtotime($i-$angka." months",strtotime($tanggal)));
            
            $tanggal3=explode("-",$tanggal2);

            $bulan=$tanggal3[1];
            $tahun=$tanggal3[0];
            $subdata['label']=$tanggal2;

            // $tanpakabar=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
            //             ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            //             ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
            //             ->select(
            //                     DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar')
            //             )
            //             ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
            //             // ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                               
            //             ->whereMonth('atts.tanggal_att','=',$bulan)
            //             ->whereYear('atts.tanggal_att','=',$tahun)
            //             // ->whereYear('atts.tanggal_att','=',$tanggal[1])
            //             ->where('pegawais.instansi_id','=',$instansi)
            //             ->count();
                   
            $count= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->where('pegawais.instansi_id','=',$instansi)  
                            ->whereMonth('atts.tanggal_att','=',$bulan)
                            ->whereYear('atts.tanggal_att','=',$tahun)
                            ->where('atts.jenisabsen_id','=','6')
                            ->count();
            array_push($data['tb'],$count);
        }

        $data['terlambat']=[];
        for ($i=0;$i<=12;$i++)
        {
            $subdata=array();
            $angka=12;
            $tanggal2=date("Y-m",strtotime($i-$angka." months",strtotime($tanggal)));
            
            $tanggal3=explode("-",$tanggal2);

            $bulan=$tanggal3[1];
            $tahun=$tanggal3[0];
            $subdata['label']=$tanggal2;
            
            // $count=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
            //             ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            //             ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
            //             ->select(
            //                     DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat')
            //             )
            //             ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
            //             // ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                               
            //             ->whereMonth('atts.tanggal_att','=',$bulan)
            //             ->whereYear('atts.tanggal_att','=',$tahun)
            //             // ->whereYear('atts.tanggal_att','=',$tanggal[1])
            //             ->where('pegawais.instansi_id','=',$instansi)
            //             ->count();

            $count= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->where('pegawais.instansi_id','=',$instansi)
                            ->whereMonth('atts.tanggal_att','=',$bulan)
                            ->whereYear('atts.tanggal_att','=',$tahun)
                            ->where('atts.terlambat','!=','00:00:00')  
                            ->count();
            array_push($data['terlambat'],$count);
        }

        $data['rapat']=[];
        for ($i=0;$i<=12;$i++)
        {
            $subdata=array();
            $angka=12;
            $tanggal2=date("Y-m",strtotime($i-$angka." months",strtotime($tanggal)));
            
            $tanggal3=explode("-",$tanggal2);

            $bulan=$tanggal3[1];
            $tahun=$tanggal3[0];
            $subdata['label']=$tanggal2;

            // $count=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
            //             ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            //             ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
            //             ->select(
            //                     DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan')
            //             )
            //             ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
            //             // ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                               
            //             ->whereMonth('atts.tanggal_att','=',$bulan)
            //             ->whereYear('atts.tanggal_att','=',$tahun)
            //             // ->whereYear('atts.tanggal_att','=',$tanggal[1])
            //             ->where('pegawais.instansi_id','=',$instansi)
            //             ->count();
               
            $count= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                            ->where('pegawais.instansi_id','=',$instansi)
                            ->whereMonth('atts.tanggal_att','=',$bulan)
                            ->whereYear('atts.tanggal_att','=',$tahun)
                            ->where('atts.jenisabsen_id','=','8')  
                            ->count();
            array_push($data['rapat'],$count);
        }
            

        $data['pulangcepat']=[];
        for ($i=0;$i<=12;$i++)
        {
            $subdata=array();
            $angka=12;
            $tanggal2=date("Y-m",strtotime($i-$angka." months",strtotime($tanggal)));
            
            $tanggal3=explode("-",$tanggal2);

            $bulan=$tanggal3[1];
            $tahun=$tanggal3[0];
            $subdata['label']=$tanggal2;

            // $count=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
            //             ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            //             ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
            //             ->select(
            //                     DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat')
                                
            //             )
            //             ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
            //             // ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                               
            //             ->whereMonth('atts.tanggal_att','=',$bulan)
            //             ->whereYear('atts.tanggal_att','=',$tahun)
            //             // ->whereYear('atts.tanggal_att','=',$tanggal[1])
            //             ->where('pegawais.instansi_id','=',$instansi)
            //             ->count();
               
            $count=  att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                                    ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                                    ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                                    ->where('atts.jam_keluar', '<', 'jadwalkerjas.jam_keluarjadwal')
                                    ->whereNull('atts.jam_keluar')
                                    ->whereNotNull('atts.jam_masuk')
                                    ->whereMonth('atts.tanggal_att','=',$bulan)
                                    ->whereYear('atts.tanggal_att','=',$tahun)
                                    ->where('atts.jenisabsen_id', '=', '1')
                                    ->where('pegawais.instansi_id','=',$instansi)
                                    ->whereNotNull('atts.jam_masuk')
                                    ->count();
            array_push($data['pulangcepat'],$count);
        }

        $data['apel']=[];
        for ($i=0;$i<=12;$i++)
        {
            $subdata=array();
            $angka=12;
            $tanggal2=date("Y-m",strtotime($i-$angka." months",strtotime($tanggal)));
            
            $tanggal3=explode("-",$tanggal2);

            $bulan=$tanggal3[1];
            $tahun=$tanggal3[0];
            $subdata['label']=$tanggal2;

            $count=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                        ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                        ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                        ->select(
                                DB::raw('count(if (atts.apel = "1",1,null)) as apel_bulanan')
                        )
                        ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                        // ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                               
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        // ->whereYear('atts.tanggal_att','=',$tanggal[1])
                        ->where('pegawais.instansi_id','=',$instansi)
                        ->get();
            // dd($count[0]['apel_bulanan']);
               
            // $count=  att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
            //                         ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
            //                         ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
            //                         ->whereMonth('atts.tanggal_att','=',$bulan)
            //                         ->whereYear('atts.tanggal_att','=',$tahun)
            //                         ->where('pegawais.instansi_id','=',$instansi)
            //                         // ->where('atts.jenisabsen_id','=',1)
            //                         // ->where('atts.jenisabsen_id','!=',2)
            //                         // ->where('atts.jenisabsen_id','!=',9)
            //                         // ->where('atts.jenisabsen_id','!=',11)
            //                         ->where('atts.apel','=',"1")
            //                         // ->where('atts.jenisabsen_id',$tanpaabsen)
            //                         ->count();
            array_push($data['apel'],$count[0]['apel_bulanan']);
        }
        $data['datasets']=$datasets;
        $data['pegawai']=pegawai::where('instansi_id','=',$instansi)->count();
        return $data;

    }

    public function grafikmonitoringtahun()
    {
        return view('monitoring.grafik.monitoringgrafiktahun');
    }

    public function grafikmonitoringtahundata()
    {

    }


    //
    public function monitoringinstansiminggu(Request $request)
    {
        // dd($request->instansi_id[0]);

        if ($request->tanggal==""){
            $tanggal=date("Y-m");
            $pecah=explode("-",$tanggal);
            $bulan=$pecah[1];
            $tahun=$pecah[0];

            $request->tanggal=$tanggal;
        }
        else
        {
            $tanggal=$request->tanggal;
            $pecah=explode("-",$tanggal);
            $bulan=$pecah[1];
            $tahun=$pecah[0];
        }

        if ($request->jenis_absen==""){
            // dd($request->jenis_absen);
            $request->jenis_absen=1;
        }

        if ($request->jenis_absen==1){
            $order='hadir';
        }
        elseif ($request->jenis_absen==2){
            $order='tanpa_kabar';
        }
        elseif ($request->jenis_absen==3)
        {
            $order='ijin';
        }
        elseif ($request->jenis_absen==4)
        {
            $order='cuti';
        }
        elseif ($request->jenis_absen==5)
        {
            $order="sakit";
        }
        elseif ($request->jenis_absen==6)
        {
            $order='tugas_belajar';
        }
        elseif ($request->jenis_absen==7)
        {
            $order='tugas_luar';
        }
        elseif ($request->jenis_absen==8)
        {
            $order='rapatundangan';
        }
        elseif ($request->jenis_absen==10)
        {
            $order='ijinterlambat';
        }
        elseif ($request->jenis_absen==12)
        {
            $order='ijinpulangcepat';
        }
        elseif ($request->jenis_absen==20)
        {
            $order='apelbulanan';
        }
        elseif ($request->jenis_absen==21)
        {
            $order='persentase_apel';
        }elseif ($request->jenis_absen==22)
        {
            $order='persentase_kehadiran';
        }

        if ($request->metode==""){
            $request->metode='desc';
        }
        
        $url="/monitoring/instansi/export?tanggal=".$request->tanggal."&metode=".$request->metode."&jenis_absen=".$request->jenis_absen;

        
        if ($request->instansi_id[0]!=""){
            $data=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                        ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                        ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                        ->select(
                                'pegawais.id',
                                'pegawais.nip',
                                'pegawais.nama',
                                DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                                DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),                        
                                DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                                DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                                DB::raw('ROUND((((count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null))) + (count(if (atts.jenisabsen_id = "3",1,null))) + (count(if (atts.jenisabsen_id = "5",1,null))) + (count(if (atts.jenisabsen_id = "4",1,null))) + (count(if (atts.jenisabsen_id = "7",1,null))) + (count(if (atts.jenisabsen_id = "6",1,null))) + (count(if (atts.jam_keluar = "8",1,null))) + (count(if (atts.jenisabsen_id = "10",1,null))) + (count(if (atts.jenisabsen_id = "12",1,null)))) / (count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null))) * 100),2 ) as persentase_kehadiran'),
                                DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                                DB::raw('count(if (atts.apel = "0" && jadwalkerjas.sifat="FD",1,null)) as tidakapelwajibapel'),
                                DB::raw('ROUND(
                                    ( count(if (atts.apel = "1",1,null)) ) / count(if (jadwalkerjas.sifat="WA",1,null)) * 100
                                    
                                ,2) as persentase_apel'),
                                DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                                DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                                DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                                DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                                DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                                DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                                DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                                DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                                DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                                DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                                DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                                DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                                'instansis.namaInstansi',
                                'pegawais.instansi_id'
                        )
                        ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.instansi_id'))                
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->where('pegawais.instansi_id','=',$request->instansi_id[0])
                        ->orderBy($order,$request->metode)
                        ->paginate(50);

            $namainstansidata=instansi::where('id','=',$request->instansi_id)
            ->first();

            $url=$url."&instansi_id=".$request->instansi_id[0];

            $namainstansi=$namainstansidata->namaInstansi;
        }
        else
        {
            $data=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                        ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                        ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                        ->select(
                                'pegawais.id',
                                'pegawais.nip',
                                'pegawais.nama',
                                DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                                DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),                        
                                DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                                DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                                DB::raw('ROUND((((count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null))) + (count(if (atts.jenisabsen_id = "3",1,null))) + (count(if (atts.jenisabsen_id = "5",1,null))) + (count(if (atts.jenisabsen_id = "4",1,null))) + (count(if (atts.jenisabsen_id = "7",1,null))) + (count(if (atts.jenisabsen_id = "6",1,null))) + (count(if (atts.jam_keluar = "8",1,null))) + (count(if (atts.jenisabsen_id = "10",1,null))) + (count(if (atts.jenisabsen_id = "12",1,null)))) / (count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null))) * 100),2 ) as persentase_kehadiran'),
                                DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                                DB::raw('count(if (atts.apel = "0" && jadwalkerjas.sifat="FD",1,null)) as tidakapelwajibapel'),
                                DB::raw('ROUND(
                                    ( count(if (atts.apel = "1",1,null)) ) / count(if (jadwalkerjas.sifat="WA",1,null)) * 100
                                    
                                ,2) as persentase_apel'),
                                DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                                DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                                DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                                DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                                DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                                DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                                DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                                DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                                DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                                DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                                DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                                DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                                'instansis.namaInstansi',
                                'pegawais.instansi_id'
                        )
                        ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.instansi_id'))                
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->orderBy($order,$request->metode)
                        ->paginate(50);

        }                        

        
        
        $jenisabsens=jenisabsen::where('id','!=','9')
                        ->where('id','!=','11')
                        ->where('id','!=','13')
                        ->get();
        // // dd($request->metode);        
       
        return view('monitoring.mingguan.rekapmingguinstansi',['datas'=>$data,'url'=>$url,'jenis_absen2'=>($request->jenis_absen),'instansi_id'=>$request->instansi_id[0],'metode'=>$request->metode,'date'=>$request->tanggal,'tanggal'=>$request->tanggal,'jenis_absens'=>$jenisabsens]);
    }

    public function monitoringinstansimingguexport(Request $request)
    {

        if ($request->tanggal==""){
            $tanggal=date("Y-m");
            $pecah=explode("-",$tanggal);
            $bulan=$pecah[1];
            $tahun=$pecah[0];

            $request->tanggal=$tanggal;
        }
        else
        {
            $tanggal=$request->tanggal;
            $pecah=explode("-",$tanggal);
            $bulan=$pecah[1];
            $tahun=$pecah[0];
        }

        if ($request->jenis_absen==""){
            // dd($request->jenis_absen);
            $request->jenis_absen=1;
        }

        if ($request->jenis_absen==1){
            $order='hadir';
        }
        elseif ($request->jenis_absen==2){
            $order='tanpa_kabar';
        }
        elseif ($request->jenis_absen==3)
        {
            $order='ijin';
        }
        elseif ($request->jenis_absen==4)
        {
            $order='cuti';
        }
        elseif ($request->jenis_absen==5)
        {
            $order="sakit";
        }
        elseif ($request->jenis_absen==6)
        {
            $order='tugas_belajar';
        }
        elseif ($request->jenis_absen==7)
        {
            $order='tugas_luar';
        }
        elseif ($request->jenis_absen==8)
        {
            $order='rapatundangan';
        }
        elseif ($request->jenis_absen==10)
        {
            $order='ijinterlambat';
        }
        elseif ($request->jenis_absen==12)
        {
            $order='ijinpulangcepat';
        }
        elseif ($request->jenis_absen==20)
        {
            $order='apelbulanan';
        }
        elseif ($request->jenis_absen==21)
        {
            $order='persentase_apel';
        }elseif ($request->jenis_absen==22)
        {
            $order='persentase_kehadiran';
        }

        if ($request->metode==""){
            $request->metode='desc';
        }

        $data=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                        ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                        ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                        ->select(
                            'instansis.namaInstansi',
                            DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                            DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),                        
                            DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                            DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                            DB::raw('ROUND((((count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null))) + (count(if (atts.jenisabsen_id = "3",1,null))) + (count(if (atts.jenisabsen_id = "5",1,null))) + (count(if (atts.jenisabsen_id = "4",1,null))) + (count(if (atts.jenisabsen_id = "7",1,null))) + (count(if (atts.jenisabsen_id = "6",1,null))) + (count(if (atts.jam_keluar = "8",1,null))) + (count(if (atts.jenisabsen_id = "10",1,null))) + (count(if (atts.jenisabsen_id = "12",1,null)))) / (count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null))) * 100),2 ) as persentase_kehadiran'),
                            DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                            DB::raw('count(if (atts.apel = "0" && jadwalkerjas.sifat="FD",1,null)) as tidakapelwajibapel'),
                            DB::raw('ROUND(
                                ( count(if (atts.apel = "1",1,null)) ) / count(if (jadwalkerjas.sifat="WA",1,null)) * 100
                                
                            ,2) as persentase_apel'),
                            DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                            DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                            DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                            DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                            DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                            DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                            DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                            DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                            DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                            DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat')
                        )
                        ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.instansi_id'))    
                        ->whereNotNull('pegawais.instansi_id')            
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->orderBy($order,$request->metode);

        if ($request->instansi_id!="")
        {
            $data->where('pegawais.instansi_id','=',$request->instansi_id[0]);
        }

        $data=$data->get();

        set_time_limit(600);
        return Excel::create('laporaninstansibulanan',function($excel) use ($data){
                $excel->sheet('laporan',function($sheet) use ($data){
                    
                        $sheet->protect('b1k1n4pl1k451');
                        
                        $sheet->fromArray($data);
                        $sheet->cell('A1',function ($cell){$cell->setValue('Instansi'); });
                        $sheet->cell('B1',function ($cell){$cell->setValue('Periode'); });
                        $sheet->cell('C1',function ($cell){$cell->setValue('Hari Kerja'); });
                        $sheet->cell('D1',function ($cell){$cell->setValue('Hadir'); });
                        $sheet->cell('E1',function ($cell){$cell->setValue('Tanpa Kabar'); });
                        $sheet->cell('F1',function ($cell){$cell->setValue('Persentase Kehadiran'); });
                        $sheet->cell('G1',function ($cell){$cell->setValue('Apel'); });
                        $sheet->cell('H1',function ($cell){$cell->setValue('Tidak Apel Wajib Apel'); });
                        $sheet->cell('I1',function ($cell){$cell->setValue('Persentase Apel'); });
                        $sheet->cell('J1',function ($cell){$cell->setValue('Terlambat'); });
                        $sheet->cell('K1',function ($cell){$cell->setValue('Ijin'); });
                        $sheet->cell('L1',function ($cell){$cell->setValue('Ijin Terlambat'); });
                        $sheet->cell('M1',function ($cell){$cell->setValue('Sakit'); });
                        $sheet->cell('N1',function ($cell){$cell->setValue('Cuti'); });
                        $sheet->cell('O1',function ($cell){$cell->setValue('Tugas Luar'); });
                        $sheet->cell('P1',function ($cell){$cell->setValue('Tugas Belajar'); });
                        $sheet->cell('Q1',function ($cell){$cell->setValue('Ijin Kepentingan Lain'); });
                        $sheet->cell('R1',function ($cell){$cell->setValue('Pulang Cepat'); });
                        $sheet->cell('S1',function ($cell){$cell->setValue('Ijin Pulang Cepat'); });


                    });
            })->download('xls');
    }

    public function monitoringinstansiminggupersonal(Request $request,$id,$tanggal){
        // dd("sa");
        $url="/monitoring/instansi/export/".$id."/".$tanggal;
        $id=decrypt($id);
        // dd($id);
        $tanggal=decrypt($tanggal);
        // dd($tanggal);
        // dd($request->tanggal);
        $hitungtanggal=strlen($request->tanggal);
        if ($hitungtanggal > 10)
        {
            $request->tanggal=decrypt($request->tanggal);
        }
        

        if ($request->tanggal==""){
            $tanggal=date("Y-m");
            $pecah=explode("-",$tanggal);
            $bulan=$pecah[1];
            $tahun=$pecah[0];
            $request->tanggal=$tanggal;
        }
        else
        {
            $tanggal=$request->tanggal;
            $pecah=explode("-",$tanggal);
            $bulan=$pecah[1];
            $tahun=$pecah[0];
        }
        
        // dd($id);

        if ($request->jenis_absen==""){
            // dd($request->jenis_absen);
            $request->jenis_absen=1;
        }

        if ($request->jenis_absen==1){
            $order='hadir';
        }
        elseif ($request->jenis_absen==2){
            $order='tanpa_kabar';
        }
        elseif ($request->jenis_absen==3)
        {
            $order='ijin';
        }
        elseif ($request->jenis_absen==4)
        {
            $order='cuti';
        }
        elseif ($request->jenis_absen==5)
        {
            $order="sakit";
        }
        elseif ($request->jenis_absen==6)
        {
            $order='tugas_belajar';
        }
        elseif ($request->jenis_absen==7)
        {
            $order='tugas_luar';
        }
        elseif ($request->jenis_absen==8)
        {
            $order='rapatundangan';
        }
        elseif ($request->jenis_absen==10)
        {
            $order='ijinterlambat';
        }
        elseif ($request->jenis_absen==12)
        {
            $order='ijinpulangcepat';
        }
        elseif ($request->jenis_absen==20)
        {
            $order='apelbulanan';
        }

        if ($request->metode==""){
            $request->metode='desc';
        }

        $url=$url."?tanggal=".$request->tanggal."&metode=".$request->metode."&jenis_absen=".$request->jenis_absen;


        $data=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                        ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                        ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                        ->select(
                                'pegawais.id',
                                'pegawais.nip',
                                'pegawais.nama',
                                DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                                DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),                        
                                DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                                DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                                DB::raw('ROUND((((count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null))) + (count(if (atts.jenisabsen_id = "3",1,null))) + (count(if (atts.jenisabsen_id = "5",1,null))) + (count(if (atts.jenisabsen_id = "4",1,null))) + (count(if (atts.jenisabsen_id = "7",1,null))) + (count(if (atts.jenisabsen_id = "6",1,null))) + (count(if (atts.jam_keluar = "8",1,null))) + (count(if (atts.jenisabsen_id = "10",1,null))) + (count(if (atts.jenisabsen_id = "12",1,null)))) / (count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null))) * 100),2 ) as persentase_kehadiran'),
                                DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                                DB::raw('count(if (atts.apel = "0" && jadwalkerjas.sifat="FD",1,null)) as tidakapelwajibapel'),
                                DB::raw('ROUND(
                                    ( count(if (atts.apel = "1",1,null)) ) / count(if (jadwalkerjas.sifat="WA",1,null)) * 100
                                    
                                ,2) as persentase_apel'),
                                DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                                DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                                DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                                DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                                DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                                DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                                DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                                DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                                DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                                DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                                DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                                DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                                'instansis.namaInstansi',
                                'pegawais.instansi_id'
                        )
                        ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))               
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->orderBy($order,$request->metode)
                        ->where('pegawais.instansi_id','=',$id)
                        ->paginate(50);


        $jenisabsens=jenisabsen::where('id','!=','9')
                    ->where('id','!=','11')
                    ->where('id','!=','13')
                    ->get();
        $instansi=instansi::where('id','=',$id)
                        ->first();
                        
        return view('monitoring.mingguan.rekapmingguinstansidetail',['datas'=>$data,'url'=>$url,'jenis_absen2'=>($request->jenis_absen),'instansi_id'=>$instansi->id,'namainstansi'=>$instansi->namaInstansi,'metode'=>$request->metode,'date'=>$request->tanggal,'tanggal'=>$request->tanggal,'id'=>$id,'jenis_absens'=>$jenisabsens]);
    }

    public function monitoringinstansiminggupersonalexport(Request $request,$id,$tanggal){
        $url="/monitoring/instansi/export/".$id."/".$tanggal;

        $id=decrypt($id);
        // dd($id);
        $tanggal=decrypt($tanggal);
        // dd($tanggal);
        // dd($request->tanggal);
        $hitungtanggal=strlen($request->tanggal);
        if ($hitungtanggal > 10)
        {
            $request->tanggal=decrypt($request->tanggal);
        }
        

        if ($request->tanggal==""){
            $tanggal=date("Y-m");
            $pecah=explode("-",$tanggal);
            $bulan=$pecah[1];
            $tahun=$pecah[0];
            $request->tanggal=$tanggal;
        }
        else
        {
            $tanggal=$request->tanggal;
            $pecah=explode("-",$tanggal);
            $bulan=$pecah[1];
            $tahun=$pecah[0];
        }
        
        // dd($id);

        if ($request->jenis_absen==""){
            // dd($request->jenis_absen);
            $request->jenis_absen=1;
        }

        if ($request->jenis_absen==1){
            $order='hadir';
        }
        elseif ($request->jenis_absen==2){
            $order='tanpa_kabar';
        }
        elseif ($request->jenis_absen==3)
        {
            $order='ijin';
        }
        elseif ($request->jenis_absen==4)
        {
            $order='cuti';
        }
        elseif ($request->jenis_absen==5)
        {
            $order="sakit";
        }
        elseif ($request->jenis_absen==6)
        {
            $order='tugas_belajar';
        }
        elseif ($request->jenis_absen==7)
        {
            $order='tugas_luar';
        }
        elseif ($request->jenis_absen==8)
        {
            $order='rapatundangan';
        }
        elseif ($request->jenis_absen==10)
        {
            $order='ijinterlambat';
        }
        elseif ($request->jenis_absen==12)
        {
            $order='ijinpulangcepat';
        }
        elseif ($request->jenis_absen==20)
        {
            $order='apelbulanan';
        }

        if ($request->metode==""){
            $request->metode='desc';
        }

        $url=$url."?tanggal=".$request->tanggal."&metode=".$request->metode."&jenis_absen=".$request->jenis_absen;

        $data=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                        ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                        ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                        ->select(
                            'pegawais.nip',
                            'pegawais.nama',
                            DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                            DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),                        
                            DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                            DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                            DB::raw('ROUND((((count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null))) + (count(if (atts.jenisabsen_id = "3",1,null))) + (count(if (atts.jenisabsen_id = "5",1,null))) + (count(if (atts.jenisabsen_id = "4",1,null))) + (count(if (atts.jenisabsen_id = "7",1,null))) + (count(if (atts.jenisabsen_id = "6",1,null))) + (count(if (atts.jam_keluar = "8",1,null))) + (count(if (atts.jenisabsen_id = "10",1,null))) + (count(if (atts.jenisabsen_id = "12",1,null)))) / (count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null))) * 100),2 ) as persentase_kehadiran'),
                            DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                            DB::raw('count(if (atts.apel = "0" && jadwalkerjas.sifat="FD",1,null)) as tidakapelwajibapel'),
                            DB::raw('ROUND(
                                ( count(if (atts.apel = "1",1,null)) ) / count(if (jadwalkerjas.sifat="WA",1,null)) * 100
                                
                            ,2) as persentase_apel'),
                            DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                            DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                            DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                            DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                            DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                            DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                            DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                            DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                            DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                            DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                            DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                            DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                            'instansis.namaInstansi'
                        )
                        ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))               
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->orderBy($order,$request->metode)
                        ->where('pegawais.instansi_id','=',$id);


                        if ($request->nip!="")
                        {
                            $data=$data->where('pegawais.nip','=',$request->nip);
                            $url=$url."&nip=".$request->nip;
                        }
                        else
                        {
            
                        }
            
                        if ($request->nama!="")
                        {
                            $data=$data->where('pegawais.nama','like',"%".$request->nama."%");
                            $url=$url."&nama=".$request->nama;
                        }
                        else
                        {
            
                        }
            
                        $data=$data->get();
                        
                        set_time_limit(600);
                        return Excel::create('laporaninstansipegawaibulanan',function($excel) use ($data){
                            $excel->sheet('laporan',function($sheet) use ($data){
                                   
                                    $sheet->protect('b1k1n4pl1k451');
                                    
                                    $sheet->fromArray($data);
                                    $sheet->cell('A1',function ($cell){$cell->setValue('NIP'); });
                                    $sheet->cell('B1',function ($cell){$cell->setValue('Nama'); });
                                    $sheet->cell('C1',function ($cell){$cell->setValue('Periode'); });
                                    $sheet->cell('D1',function ($cell){$cell->setValue('Hari Kerja'); });
                                    $sheet->cell('E1',function ($cell){$cell->setValue('Hadir'); });
                                    $sheet->cell('F1',function ($cell){$cell->setValue('Tanpa Kabar'); });
                                    $sheet->cell('G1',function ($cell){$cell->setValue('Persentase Kehadiran'); });
                                    $sheet->cell('H1',function ($cell){$cell->setValue('Apel'); });
                                    $sheet->cell('I1',function ($cell){$cell->setValue('Tidak Apel Wajib Apel'); });
                                    $sheet->cell('J1',function ($cell){$cell->setValue('Persentase Apel'); });
                                    $sheet->cell('K1',function ($cell){$cell->setValue('Terlambat'); });
                                    $sheet->cell('L1',function ($cell){$cell->setValue('Ijin'); });
                                    $sheet->cell('M1',function ($cell){$cell->setValue('Ijin Terlambat'); });
                                    $sheet->cell('N1',function ($cell){$cell->setValue('Sakit'); });
                                    $sheet->cell('O1',function ($cell){$cell->setValue('Cuti'); });
                                    $sheet->cell('P1',function ($cell){$cell->setValue('Tugas Luar'); });
                                    $sheet->cell('Q1',function ($cell){$cell->setValue('Tugas Belajar'); });
                                    $sheet->cell('R1',function ($cell){$cell->setValue('Ijin Kepentingan Lain'); });
                                    $sheet->cell('S1',function ($cell){$cell->setValue('Pulang Cepat'); });
                                    $sheet->cell('T1',function ($cell){$cell->setValue('Ijin Pulang Cepat'); });
                                    $sheet->cell('U1',function ($cell){$cell->setValue('Akumulasi Terlambat'); });
                                    $sheet->cell('V1',function ($cell){$cell->setValue('Akumulasi Kerja'); });
                                    $sheet->cell('W1',function ($cell){$cell->setValue('Instansi'); });
            
            
                                });
                        })->download('xls');
    }

    public function monitoringinstansiminggupersonaldetail(Request $request,$id,$tanggal,$instansi_id)
    {
        $id=decrypt($id);
        // dd($id);
        $tanggal2=decrypt($tanggal);
        // dd($tanggal2);
        // dd($request->tanggal);
        
        $pecah=explode("-",$tanggal2);
        $bulan=$pecah[1];
        $tahun=$pecah[0];


        
        // dd($id);

        if ($request->jenis_absen==""){
            // dd($request->jenis_absen);
            $request->jenis_absen=1;
        }

        if ($request->jenis_absen==1){
            $order='hadir';
        }
        elseif ($request->jenis_absen==2){
            $order='tanpa_kabar';
        }
        elseif ($request->jenis_absen==3)
        {
            $order='ijin';
        }
        elseif ($request->jenis_absen==4)
        {
            $order='cuti';
        }
        elseif ($request->jenis_absen==5)
        {
            $order="sakit";
        }
        elseif ($request->jenis_absen==6)
        {
            $order='tugas_belajar';
        }
        elseif ($request->jenis_absen==7)
        {
            $order='tugas_luar';
        }
        elseif ($request->jenis_absen==8)
        {
            $order='rapatundangan';
        }
        elseif ($request->jenis_absen==10)
        {
            $order='ijinterlambat';
        }
        elseif ($request->jenis_absen==12)
        {
            $order='ijinpulangcepat';
        }
        elseif ($request->jenis_absen==20)
        {
            $order='apelbulanan';
        }

        if ($request->metode==""){
            $request->metode='desc';
        }
        // dd($tanggal2);
        $data=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                        ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                        ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                        ->select(
                                'pegawais.id',
                                'pegawais.nip',
                                'pegawais.nama',
                                DB::raw('DATE_FORMAT( tanggal_att, "%d-%m-%Y" ) as periode'),
                                DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),                        
                                DB::raw('count(if (atts.jenisabsen_id = "1",1,null)) as hadir'),
                                DB::raw('count(if (atts.apel = "1",1,null)) as apel_bulanan'),
                                DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                                DB::raw('count(if (atts.jenisabsen_id = "2",1,null)) as tanpa_kabar'),
                                DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                                DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                                DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                                DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                                DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                                DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                                DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                                DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                                DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                                DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                                DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                                'instansis.namaInstansi',
                                'pegawais.instansi_id'
                        )
                        ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'))                
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->orderBy($order,$request->metode)
                        ->where('pegawais.id','=',$id)
                        ->paginate(50);

        // $data=masterbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
        //     ->leftJoin('jadwalkerjas','masterbulanans.jadwalkerja_id','=','jadwalkerjas.id')
        //     ->leftJoin('instansis','masterbulanans.instansi_id','=','instansis.id')
        //     ->select(DB::raw('sum(masterbulanans.hari_kerja) as hari_kerja'),DB::raw('sum(masterbulanans.hadir) as hadir'),DB::raw('sum(masterbulanans.ijin) as ijin'),
        //             DB::raw('sum(masterbulanans.tanpa_kabar) as tanpa_kabar'),
        //             DB::raw('sum(masterbulanans.ijinterlambat) as ijinterlambat'),
        //             DB::raw('sum(masterbulanans.sakit) as sakit'),
        //             DB::raw('sum(masterbulanans.tugas_luar) as tugas_luar'),
        //             DB::raw('sum(masterbulanans.tugas_belajar) as tugas_belajar'),
        //             DB::raw('sum(masterbulanans.terlambat) as terlambat'),
        //             DB::raw('sum(masterbulanans.rapatundangan) as rapatundangan'),
        //             DB::raw('sum(masterbulanans.pulang_cepat) as pulang_cepat'),
        //             DB::raw('sum(masterbulanans.ijinpulangcepat) as ijinpulangcepat'),
        //             DB::raw('sum(masterbulanans.apelbulanan) as apelbulanan'),
        //             DB::raw('SEC_TO_TIME( SUM(time_to_sec(total_akumulasi))) as total_akumulasi'),
        //             DB::raw('SEC_TO_TIME( SUM(time_to_sec(total_terlambat))) as total_terlambat'),
        //             'instansis.namaInstansi',
        //             'masterbulanans.instansi_id',
        //             'masterbulanans.pegawai_id',
        //             'masterbulanans.periode',
        //             'pegawais.nip',
        //             'pegawais.nama'
        //     )
        //     ->whereMonth('masterbulanans.periode','=',$bulan)
        //     ->whereYear('masterbulanans.periode','=',$tahun)
        //     ->where('masterbulanans.pegawai_id','=',$id)
        //     ->orderBy($order,$request->metode)
        //     ->groupBy('masterbulanans.periode')            
        //     ->whereNotNull('masterbulanans.instansi_id')
        //     ->paginate(50);

            // dd($id);

        $jenisabsens=jenisabsen::where('id','!=','9')
                    ->where('id','!=','11')
                    ->where('id','!=','13')
                    ->get();
        $instansi_id=decrypt($instansi_id);
        $instansi=instansi::where('id','=',$instansi_id)
                        ->first();
        $pegawai=pegawai::where('id','=',$id)
                ->first();
                // dd($id);
        return view('monitoring.mingguan.rekapmingguinstansipersonaldetail',['datas'=>$data,'jenis_absen2'=>($request->jenis_absen),'instansi_id'=>$instansi_id,'namainstansi'=>$instansi->namaInstansi,'idpegawai'=>$pegawai->id,'nip'=>$pegawai->nip,'namapegawai'=>$pegawai->nama,'metode'=>$request->metode,'date'=>$tanggal2,'tanggal'=>$tanggal2,'id'=>$id,'jenis_absens'=>$jenisabsens]); 
    }

    public function monitoringinstansiminggupersonaldetailatt(Request $request,$id,$tanggal,$instansi_id){
        $id=decrypt($id);
        $tanggal=decrypt($tanggal);
        // $instansi_id=decrypt($instansi_id);
        // dd($instansi_id);
        // dd($id);
        $instansi_id=$instansi_id;
        $tanggalawal=date('Y-m-d',strtotime('+6 days',strtotime($tanggal)));
        $tanggalakhir=date('Y-m-d',strtotime(($tanggal)));

        // dd($tanggalawal." + ".$tanggalakhir);

        // $data=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        //                 ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
        //                 ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
        //                 ->select(
        //                         'pegawais.id',
        //                         'pegawais.nip',
        //                         'pegawais.nama',
        //                         DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
        //                         DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),                        
        //                         DB::raw('count(if (atts.jenisabsen_id = "1",1,null)) as hadir'),
        //                         DB::raw('count(if (atts.apel = "1",1,null)) as apel_bulanan'),
        //                         DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
        //                         DB::raw('count(if (atts.jenisabsen_id = "2",1,null)) as tanpa_kabar'),
        //                         DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
        //                         DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
        //                         DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
        //                         DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
        //                         DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
        //                         DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
        //                         DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
        //                         DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
        //                         DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
        //                         DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
        //                         DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
        //                         'instansis.namaInstansi',
        //                         'pegawais.instansi_id'
        //                 )
        //                 // ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))                
        //                 ->where('atts.tanggal_att','>=',$tanggalakhir)
        //                 ->where('atts.tanggal_att','<=',$tanggalawal)
        //                 // ->whereYear('atts.tanggal_att','=',$tahun)
        //                 ->where('pegawais.id','=',$id)
        //                 ->paginate(50);
                        // dd($data);

        $data=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->where('atts.tanggal_att','>=',$tanggalakhir)
        ->where('atts.tanggal_att','<=',$tanggalawal)
        ->where('pegawais.id','=',$id)
        // ->whereMonth('atts.tanggal_att','=',$bulan)
        // ->whereDay('atts.tanggal_att','=',$tanggal)
        // ->whereYear('atts.tanggal_att','=',$tahun)
        ->select('atts.*','jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('pegawais.nama','desc')
        ->paginate(30);
        
        $jenisabsens=jenisabsen::where('id','!=','9')
                    ->where('id','!=','11')
                    ->where('id','!=','13')
                    ->get();
        $instansi=instansi::where('id','=',$instansi_id)
                        ->first();
        $pegawai=pegawai::where('id','=',$id)
                ->first();
        return view('monitoring.mingguan.rekapmingguinstansipersonaldetailatt   ',['datas'=>$data,'jenis_absen2'=>($request->jenis_absen),'instansi_id'=>$instansi_id,'namainstansi'=>$instansi->namaInstansi,'idpegawai'=>$pegawai->id,'nip'=>$pegawai->nip,'namapegawai'=>$pegawai->nama,'metode'=>$request->metode,'date'=>$tanggal,'tanggal'=>$tanggal,'id'=>$id,'jenis_absens'=>$jenisabsens]); 
    }

    public function monitoringpegawaibulanan(Request $request){
        //$id=decrypt($id);
        // dd($id);
        //$tanggal=decrypt($tanggal);
        // dd($tanggal);
        // dd($request->tanggal);


        $hitungtanggal=strlen($request->tanggal);
        if ($hitungtanggal > 10)
        {
            $request->tanggal=decrypt($request->tanggal);
        }
        

        if ($request->tanggal==""){
            $tanggal=date("Y-m");
            $pecah=explode("-",$tanggal);
            $bulan=$pecah[1];
            $tahun=$pecah[0];
            $request->tanggal=$tanggal;
        }
        else
        {
            $tanggal=$request->tanggal;
            $pecah=explode("-",$tanggal);
            $bulan=$pecah[1];
            $tahun=$pecah[0];
        }
        
        // dd($id);

        if ($request->jenis_absen==""){
            // dd($request->jenis_absen);
            $request->jenis_absen=1;
        }

        if ($request->jenis_absen==1){
            $order='hadir';
        }
        elseif ($request->jenis_absen==2){
            $order='tanpa_kabar';
        }
        elseif ($request->jenis_absen==3)
        {
            $order='ijin';
        }
        elseif ($request->jenis_absen==4)
        {
            $order='cuti';
        }
        elseif ($request->jenis_absen==5)
        {
            $order="sakit";
        }
        elseif ($request->jenis_absen==6)
        {
            $order='tugas_belajar';
        }
        elseif ($request->jenis_absen==7)
        {
            $order='tugas_luar';
        }
        elseif ($request->jenis_absen==8)
        {
            $order='rapatundangan';
        }
        elseif ($request->jenis_absen==10)
        {
            $order='ijinterlambat';
        }
        elseif ($request->jenis_absen==12)
        {
            $order='ijinpulangcepat';
        }
        elseif ($request->jenis_absen==20)
        {
            $order='apelbulanan';
        }
        elseif ($request->jenis_absen==21)
        {
            $order='persentase_apel';
        }elseif ($request->jenis_absen==22)
        {
            $order='persentase_kehadiran';
        }

        if ($request->metode==""){
            $request->metode='desc';
        }

        $url="/monitoring/pegawai/export?tanggal=".$request->tanggal."&metode=".$request->metode."&jenis_absen=".$request->jenis_absen;

            $data=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                        ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                        ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                        ->select(
                                'pegawais.id',
                                'pegawais.nip',
                                'pegawais.nama',
                                DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                                DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),                        
                                DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                                DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                                DB::raw('ROUND((((count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null))) + (count(if (atts.jenisabsen_id = "3",1,null))) + (count(if (atts.jenisabsen_id = "5",1,null))) + (count(if (atts.jenisabsen_id = "4",1,null))) + (count(if (atts.jenisabsen_id = "7",1,null))) + (count(if (atts.jenisabsen_id = "6",1,null))) + (count(if (atts.jam_keluar = "8",1,null))) + (count(if (atts.jenisabsen_id = "10",1,null))) + (count(if (atts.jenisabsen_id = "12",1,null)))) / (count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null))) * 100),2 ) as persentase_kehadiran'),
                                DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                                DB::raw('count(if (atts.apel = "0" && jadwalkerjas.sifat="FD",1,null)) as tidakapelwajibapel'),
                                DB::raw('ROUND(
                                    ( count(if (atts.apel = "1",1,null)) ) / count(if (jadwalkerjas.sifat="WA",1,null)) * 100
                                    
                                ,2) as persentase_apel'),
                                DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                                DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                                DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                                DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                                DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                                DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                                DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                                DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                                DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                                DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                                DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                                DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                                'instansis.namaInstansi',
                                'pegawais.instansi_id'
                        )
                        ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                        ->orderBy($order,$request->metode)
                        ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->whereNotNull('pegawais.instansi_id');
            

            // dd($data);

            if ($request->nip!="")
            {
                $data=$data->where('pegawais.nip','=',$request->nip);
                $url=$url."&nip=".$request->nip;
            }
            else
            {

            }

            if ($request->nama!="")
            {
                $data=$data->where('pegawais.nama','like',"%".$request->nama."%");
                $url=$url."&nama=".$request->nama;
            }
            else
            {

            }

            $data=$data->paginate(50);

            // dd($data);
        $jenisabsens=jenisabsen::where('id','!=','9')
                    ->where('id','!=','11')
                    ->where('id','!=','13')
                    ->get();
        //$instansi=instansi::where('id','=',$id)
        //                ->first();

        
                        
        return view('monitoring.pegawai.rekappegawaibulanan',['datas'=>$data,'url'=>$url,'jenis_absen2'=>($request->jenis_absen),'metode'=>$request->metode,'date'=>$request->tanggal,'nama'=>$request->nama,'nip'=>$request->nip,'tanggal'=>$request->tanggal,'jenis_absens'=>$jenisabsens]);
    }

    public function monitoringpegawaibulanexport(Request $request)
    {
        if ($request->tanggal==""){
            $tanggal=date("Y-m");
            $pecah=explode("-",$tanggal);
            $bulan=$pecah[1];
            $tahun=$pecah[0];
            $request->tanggal=$tanggal;
        }
        else
        {
            $tanggal=$request->tanggal;
            $pecah=explode("-",$tanggal);
            $bulan=$pecah[1];
            $tahun=$pecah[0];
        }
        
        // dd($id);

        // dd($request->jenis_absen);

        if ($request->jenis_absen==""){
            // dd($request->jenis_absen);
            $request->jenis_absen=1;
        }

        if ($request->jenis_absen==1){
            $order='hadir';
        }
        elseif ($request->jenis_absen==2){
            $order='tanpa_kabar';
        }
        elseif ($request->jenis_absen==3)
        {
            $order='ijin';
        }
        elseif ($request->jenis_absen==4)
        {
            $order='cuti';
        }
        elseif ($request->jenis_absen==5)
        {
            $order="sakit";
        }
        elseif ($request->jenis_absen==6)
        {
            $order='tugas_belajar';
        }
        elseif ($request->jenis_absen==7)
        {
            $order='tugas_luar';
        }
        elseif ($request->jenis_absen==8)
        {
            $order='rapatundangan';
        }
        elseif ($request->jenis_absen==10)
        {
            $order='ijinterlambat';
        }
        elseif ($request->jenis_absen==12)
        {
            $order='ijinpulangcepat';
        }
        elseif ($request->jenis_absen==20)
        {
            $order='apelbulanan';
        }
        elseif ($request->jenis_absen==21)
        {
            $order='persentase_apel';
        }elseif ($request->jenis_absen==22)
        {
            $order='persentase_kehadiran';
        }

        if ($request->metode==""){
            $request->metode='desc';
        }

        $url="/monitoring/pegawai/export?tanggal=".$request->tanggal."&metode=".$request->metode."&jenis_absen=".$request->jenis_absen;

            $data=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                        ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                        ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                        ->select(
                                'pegawais.nip',
                                'pegawais.nama',
                                DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                                DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),                        
                                DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                                DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                                DB::raw('ROUND((((count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null))) + (count(if (atts.jenisabsen_id = "3",1,null))) + (count(if (atts.jenisabsen_id = "5",1,null))) + (count(if (atts.jenisabsen_id = "4",1,null))) + (count(if (atts.jenisabsen_id = "7",1,null))) + (count(if (atts.jenisabsen_id = "6",1,null))) + (count(if (atts.jam_keluar = "8",1,null))) + (count(if (atts.jenisabsen_id = "10",1,null))) + (count(if (atts.jenisabsen_id = "12",1,null)))) / (count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null))) * 100),2 ) as persentase_kehadiran'),
                                DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                                DB::raw('count(if (atts.apel = "0" && jadwalkerjas.sifat="FD",1,null)) as tidakapelwajibapel'),
                                DB::raw('ROUND(
                                    ( count(if (atts.apel = "1",1,null)) ) / count(if (jadwalkerjas.sifat="WA",1,null)) * 100
                                    
                                ,2) as persentase_apel'),
                                DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                                DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                                DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                                DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                                DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                                DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                                DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                                DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                                DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                                DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                                DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                                DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                                'instansis.namaInstansi'
                        )
                        ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                        ->orderBy($order,$request->metode)
                        ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->whereNotNull('pegawais.instansi_id');
            

            // dd($data);

            if ($request->nip!="")
            {
                $data=$data->where('pegawais.nip','=',$request->nip);
                $url=$url."&nip=".$request->nip;
            }
            else
            {

            }

            if ($request->nama!="")
            {
                $data=$data->where('pegawais.nama','like',"%".$request->nama."%");
                $url=$url."&nama=".$request->nama;
            }
            else
            {

            }

            $data=$data->get();

            // dd($data);
            set_time_limit(600);
            return Excel::create('laporanpegawaibulanan',function($excel) use ($data){
                $excel->sheet('laporan',function($sheet) use ($data){
                       
                        $sheet->protect('b1k1n4pl1k451');
                        
                        $sheet->fromArray($data);
                        $sheet->cell('A1',function ($cell){$cell->setValue('NIP'); });
                        $sheet->cell('B1',function ($cell){$cell->setValue('Nama'); });
                        $sheet->cell('C1',function ($cell){$cell->setValue('Periode'); });
                        $sheet->cell('D1',function ($cell){$cell->setValue('Hari Kerja'); });
                        $sheet->cell('E1',function ($cell){$cell->setValue('Hadir'); });
                        $sheet->cell('F1',function ($cell){$cell->setValue('Tanpa Kabar'); });
                        $sheet->cell('G1',function ($cell){$cell->setValue('Persentase Kehadiran'); });
                        $sheet->cell('H1',function ($cell){$cell->setValue('Apel'); });
                        $sheet->cell('I1',function ($cell){$cell->setValue('Tidak Apel Wajib Apel'); });
                        $sheet->cell('J1',function ($cell){$cell->setValue('Persentase Apel'); });
                        $sheet->cell('K1',function ($cell){$cell->setValue('Terlambat'); });
                        $sheet->cell('L1',function ($cell){$cell->setValue('Ijin'); });
                        $sheet->cell('M1',function ($cell){$cell->setValue('Ijin Terlambat'); });
                        $sheet->cell('N1',function ($cell){$cell->setValue('Sakit'); });
                        $sheet->cell('O1',function ($cell){$cell->setValue('Cuti'); });
                        $sheet->cell('P1',function ($cell){$cell->setValue('Tugas Luar'); });
                        $sheet->cell('Q1',function ($cell){$cell->setValue('Tugas Belajar'); });
                        $sheet->cell('R1',function ($cell){$cell->setValue('Ijin Kepentingan Lain'); });
                        $sheet->cell('S1',function ($cell){$cell->setValue('Pulang Cepat'); });
                        $sheet->cell('T1',function ($cell){$cell->setValue('Ijin Pulang Cepat'); });
                        $sheet->cell('U1',function ($cell){$cell->setValue('Akumulasi Terlambat'); });
                        $sheet->cell('V1',function ($cell){$cell->setValue('Akumulasi Kerja'); });
                        $sheet->cell('W1',function ($cell){$cell->setValue('Instansi'); });


                    });
            })->download('xls');
    }

    
    public function monitoringpegawaiminggu(Request $request,$id,$tanggal)
    {
        $id=decrypt($id);
        // dd($id);
        $tanggal2=decrypt($tanggal);
        // dd($tanggal2);
        // dd($request->tanggal);
        
        $pecah=explode("-",$tanggal2);
        $bulan=$pecah[1];
        $tahun=$pecah[0];


        
        // dd($id);

        if ($request->jenis_absen==""){
            // dd($request->jenis_absen);
            $request->jenis_absen=1;
        }

        if ($request->jenis_absen==1){
            $order='hadir';
        }
        elseif ($request->jenis_absen==2){
            $order='tanpa_kabar';
        }
        elseif ($request->jenis_absen==3)
        {
            $order='ijin';
        }
        elseif ($request->jenis_absen==4)
        {
            $order='cuti';
        }
        elseif ($request->jenis_absen==5)
        {
            $order="sakit";
        }
        elseif ($request->jenis_absen==6)
        {
            $order='tugas_belajar';
        }
        elseif ($request->jenis_absen==7)
        {
            $order='tugas_luar';
        }
        elseif ($request->jenis_absen==8)
        {
            $order='rapatundangan';
        }
        elseif ($request->jenis_absen==10)
        {
            $order='ijinterlambat';
        }
        elseif ($request->jenis_absen==12)
        {
            $order='ijinpulangcepat';
        }
        elseif ($request->jenis_absen==20)
        {
            $order='apelbulanan';
        }

        if ($request->metode==""){
            $request->metode='desc';
        }

        $data=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
            ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
            ->select(
                    'pegawais.id',
                    'pegawais.nip',
                    'pegawais.nama',
                    DB::raw('tanggal_att as periode'),
                    DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),                        
                    DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                    DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                    DB::raw('ROUND((((count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null))) + (count(if (atts.jenisabsen_id = "3",1,null))) + (count(if (atts.jenisabsen_id = "5",1,null))) + (count(if (atts.jenisabsen_id = "4",1,null))) + (count(if (atts.jenisabsen_id = "7",1,null))) + (count(if (atts.jenisabsen_id = "6",1,null))) + (count(if (atts.jam_keluar = "8",1,null))) + (count(if (atts.jenisabsen_id = "10",1,null))) + (count(if (atts.jenisabsen_id = "12",1,null)))) / (count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null))) * 100),2 ) as persentase_kehadiran'),
                    DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                    DB::raw('count(if (atts.apel = "0" && jadwalkerjas.sifat="FD",1,null)) as tidakapelwajibapel'),
                    DB::raw('ROUND(
                        ( count(if (atts.apel = "1",1,null)) ) / count(if (jadwalkerjas.sifat="WA",1,null)) * 100
                        
                    ,2) as persentase_apel'),
                    DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                    DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                    DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                    DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                    DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                    DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                    DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                    DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                    DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                    DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                    DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                    DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                    'instansis.namaInstansi',
                    'pegawais.instansi_id'
            )
            ->whereMonth('atts.tanggal_att','=',$bulan)
            ->whereYear('atts.tanggal_att','=',$tahun)
            ->where('pegawais.nip','=',$id)
            ->orderBy($order,$request->metode)
            ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
            ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                           
            ->whereNotNull('pegawais.instansi_id')
            ->paginate(50);

            //dd($id);

        $jenisabsens=jenisabsen::where('id','!=','9')
                    ->where('id','!=','11')
                    ->where('id','!=','13')
                    ->get();
        //$instansi_id=decrypt($instansi_id);
        //$instansi=instansi::where('id','=',$instansi_id)
        //                ->first();
        $pegawai=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->where('pegawais.nip','=',$id)
                ->select('pegawais.*','instansis.namaInstansi')
                ->first();
        //dd($pegawai);

        return view('monitoring.pegawai.rekappegawaiminggu',['datas'=>$data,'jenis_absen2'=>($request->jenis_absen),'namainstansi'=>$pegawai->namaInstansi,'idpegawai'=>$pegawai->id,'nip'=>$pegawai->nip,'namapegawai'=>$pegawai->nama,'metode'=>$request->metode,'date'=>$tanggal2,'tanggal'=>$tanggal2,'id'=>$id,'jenis_absens'=>$jenisabsens]); 
    }

    public function monitoringpegawaihari(Request $request,$id,$tanggal){
        $id=decrypt($id);
        $tanggal=decrypt($tanggal);
        // $instansi_id=decrypt($instansi_id);
        // dd($instansi_id);
        //$instansi_id=$instansi_id;
        $tanggalawal=date('Y-m-d',strtotime('-7 days',strtotime($tanggal)));
        dd($id);
        $tanggalakhir=date('Y-m-d',strtotime('-1 days',strtotime($tanggal)));
        $data=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->where('atts.tanggal_att','>=',$tanggalawal)
        ->where('atts.tanggal_att','<=',$tanggalakhir)
        ->where('pegawais.id','=',$id)
        // ->whereMonth('atts.tanggal_att','=',$bulan)
        // ->whereDay('atts.tanggal_att','=',$tanggal)
        // ->whereYear('atts.tanggal_att','=',$tahun)
        ->select('atts.*','jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('pegawais.nama','desc')
        ->paginate(30);
        $jenisabsens=jenisabsen::where('id','!=','9')
                    ->where('id','!=','11')
                    ->where('id','!=','13')
                    ->get();
        //$instansi=instansi::where('id','=',$instansi_id)
        //                ->first();
        $pegawai=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->where('pegawais.id','=',$id)
                ->select('pegawais.*','instansis.namaInstansi')
                ->first();
        //dd($pegawai);
        return view('monitoring.pegawai.rekappegawaihari',['datas'=>$data,'jenis_absen2'=>($request->jenis_absen),'namainstansi'=>$pegawai->namaInstansi,'idpegawai'=>$pegawai->id,'nip'=>$pegawai->nip,'namapegawai'=>$pegawai->nama,'metode'=>$request->metode,'date'=>$tanggal,'tanggal'=>$tanggal,'id'=>$id,'jenis_absens'=>$jenisabsens]); 
    }


}   
