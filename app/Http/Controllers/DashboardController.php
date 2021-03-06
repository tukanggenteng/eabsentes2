<?php

namespace App\Http\Controllers;
use App\pegawai;
use App\atts_tran;
use App\att;
use App\finalrekapbulanan;
use App\masterbulanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;
use App\instansi;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        // dd($id);
          $bulan=date("m");
          $tahun=date("Y");
          $tahun2=date("Y");
              $pegawai=pegawai::where('nip','=',$id)
                ->count();
                // dd($pegawai);
              if ($pegawai>0){
                $pegawais=pegawai::where('nip','=',$id)
                  ->first();
                  $nip=$pegawais->nip;
                  $nama=$pegawais->nama;

                  $tidakhadir = att::whereMonth('tanggal_att', '=', $bulan)
                      ->whereYear('tanggal_att', '=', $tahun)
                      ->where('pegawai_id','=',$pegawais->id)
                      ->where('jenisabsen_id','=','2')
                      ->count();
                    // dd($tidakhadir);
                    $apel = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                    ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                    ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                    ->where('atts.jam_masuk', '<=', 'jadwalkerjas.jam_masukjadwal')
                    ->whereMonth('atts.tanggal_att','=',$bulan)
                    ->whereYear('atts.tanggal_att','=',$tahun)
                    ->whereNotNull('atts.jam_masuk')
                    ->where('pegawais.nip','=',$id)
                    ->where('atts.apel','=',1)
                    // ->where('atts.jenisabsen_id','!=',2)
                    // ->where('atts.jenisabsen_id','!=',9)
                    // ->where('atts.jenisabsen_id','!=',11)
                    // ->where('atts.jenisabsen_id',$tanpaabsen)
                    ->count();

                        //   dd($apel);
                  $totalakumulasi = att::
                  whereMonth('tanggal_att','=',$bulan)
                  ->whereYear('tanggal_att','=',$tahun)
                  ->where('pegawai_id','=',$pegawais->id)
                  ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(akumulasi_sehari))) as total'))
                  ->first();
                  $total=$totalakumulasi->total;

                  $kehadiran=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                  ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
                  ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
                  ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
                  ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
                  ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                  ->where('pegawais.nip','=',$id)
                  ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
                      'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
                  ->orderBy('atts.tanggal_att','desc')
                  ->paginate(30);
                  // dd($totalakumulasi);
            
                  return view('dashboard.pegawaidetail',['kehadirans'=>$kehadiran,'statuscari'=>null,'nip'=>$nip,'persentaseapel'=>$apel,'tahun'=>$tahun2,'nama'=>$nama,'persentasehadir'=>$tidakhadir,'totalakumulasi'=>$total]);
              }
              else {
                return view('dashboard.pegawaidetail',['statuscari'=>'Data pegawai tidak ditemukan.','tahun'=>$tahun2]);
              }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datakosong (){
      $tahun=date("Y");

      $persentasetidakhadir=array();
      $persentaseapel=array();
      $seluruh=array();
      for ($a = 1; $a <= 2; $a++) {
          if ($a=="1")
          {
              for ($i = 1; $i <= 12; $i++) {
                    array_push($persentasetidakhadir,'0');
              }
          }
          elseif ($a=="2")
          {
              for ($i = 1; $i <= 12; $i++) {

                  array_push($persentaseapel,'0');

              }
          }
      }
      $seluruh['Absen']=$persentasetidakhadir;
      $seluruh['Apel']=$persentaseapel;
      return response()->json($seluruh);
    }

    public function datagrafiksekda(Request $request){
      $tahun=$request->periodetahun;

      $persentasetidakhadir=array();
      $persentaseapel=array();
      $seluruh=array();
      for ($a = 1; $a <= 2; $a++) {
          if ($a=="1")
          {
              for ($i = 1; $i <= 12; $i++) {
                  $tidakhadir = masterbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
                      ->where('pegawais.instansi_id', '=', $request->instansitahun)
                      ->whereMonth('masterbulanans.periode', '=', $i)
                      ->whereYear('masterbulanans.periode', '=', $tahun)
                      ->avg('masterbulanans.persentase_tidakhadir');
                  $patokan = masterbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
                      ->where('pegawais.instansi_id', '=', $request->instansitahun)
                      ->whereMonth('masterbulanans.periode', '=', $i)
                      ->whereYear('masterbulanans.periode', '=', $tahun)
                      ->count();
                  if ($tidakhadir!=null)
                  {
                      $total=$tidakhadir;
                      array_push($persentasetidakhadir,$total);
                  }
                  else
                  {
                      array_push($persentasetidakhadir,'0');
                  }
              }
          }
          elseif ($a=="2")
          {
              for ($i = 1; $i <= 12; $i++) {
                  $absen = masterbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
                      ->where('pegawais.instansi_id', '=', $request->instansitahun)
                      ->whereMonth('masterbulanans.periode', '=', $i)
                      ->whereYear('masterbulanans.periode', '=', $tahun)
                      ->avg('masterbulanans.persentase_apel');
                  $patokan = masterbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
                  ->where('pegawais.instansi_id', '=', $request->instansitahun)
                      ->whereMonth('periode', '=', $i)
                      ->whereYear('periode', '=', $tahun)
                      ->count();

                  if ($absen!=null)
                  {

                      $totalabsen=$absen;
                      array_push($persentaseapel,$totalabsen);
                  }
                  else
                  {
                      array_push($persentaseapel,'0');
                  }
              }
          }
      }
      $seluruh['Absen']=$persentasetidakhadir;
      $seluruh['Apel']=$persentaseapel;
      return response()->json($seluruh);
    }

    public function datatahun(Request $request){

        $tanggal=date("Y-m-01");
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
                            ->where('atts.jenisabsen_id','!=','13')
                            ->where('atts.jenisabsen_id','!=','11')
                            ->where('atts.jenisabsen_id','!=','9')
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

    public function datadetailrekappegawai($id){
        $tahun=date("Y");
  
        $persentasetidakhadir=array();
        $persentaseapel=array();
        $seluruh=array();
        $nip=$id;
        $pegawais=pegawai::where('nip','=',$nip)
          ->first();
  
          // dd($pegawais->id);
  
        for ($a = 1; $a <= 2; $a++) {
            if ($a=="1")
            {
                for ($i = 1; $i <= 12; $i++) {
                    $tidakhadir = finalrekapbulanan::whereMonth('periode', '=', $i)
                        ->whereYear('periode', '=', $tahun)
                        ->where('pegawai_id','=',$pegawais->id)
                        ->count('persentase_tidakhadir');
                    if ($tidakhadir!=null)
                    {
                        $total=$tidakhadir;
                        array_push($persentasetidakhadir,$total);
                    }
                    else
                    {
                        array_push($persentasetidakhadir,'0');
                    }
                }
            }
            elseif ($a=="2")
            {
                for ($i = 1; $i <= 12; $i++) {
                    $absen = finalrekapbulanan::whereMonth('periode', '=', $i)
                        ->whereYear('periode', '=', $tahun)
                        ->where('pegawai_id','=',$pegawais->id)
                        ->count('persentase_apel');
                    if ($absen!=null)
                    {
  
                        $totalabsen=$absen;
                        array_push($persentaseapel,$totalabsen);
                    }
                    else
                    {
                        array_push($persentaseapel,'0');
                    }
                }
            }
        }
        $seluruh['Absen']=$persentasetidakhadir;
        $seluruh['Apel']=$persentaseapel;
        return response()->json($seluruh);
      }

    public function datapegawai(Request $request){
        $nip=$request->nip;

        $pegawai=pegawai::where('nip','=',$nip)->first();

        // dd($pegawai->id);

        $tanggal=$request->tanggal."-01";
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
                        ->where('pegawais.id','=',$pegawai->id)
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

            // $tanpakabar= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
            //                 ->whereMonth('atts.tanggal_att','=',$bulan)
            //                 ->whereYear('atts.tanggal_att','=',$tahun)
            //                 ->where('atts.pegawai_id','=',$pegawai->id)
            //                 ->where('atts.jenisabsen_id','!=','13')
            //                 ->where('atts.jenisabsen_id','!=','11')
            //                 ->where('atts.jenisabsen_id','!=','9')
            //                 ->count();
            $tanpakabar= att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                        // ->select('atts.id')
                        ->whereMonth('atts.tanggal_att','=',$bulan)
                        ->whereYear('atts.tanggal_att','=',$tahun)
                        ->where('pegawais.id','=',$pegawai->id)
                        ->count();

            // dd($tanpakabar);
            //$subdata['tanpakabar']=$tanpakabar;
            array_push($data['harikerja'],$tanpakabar);
        }
        // dd($data);

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
                            ->where('pegawais.id','=',$pegawai->id)
 
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
                            ->where('pegawais.id','=',$pegawai->id)
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
                            ->where('pegawais.id','=',$pegawai->id) 
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
                            ->where('pegawais.id','=',$pegawai->id) 
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
                            ->where('pegawais.id','=',$pegawai->id)
 
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
                           ->where('pegawais.id','=',$pegawai->id)

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
                            ->where('pegawais.id','=',$pegawai->id)
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
                            ->where('pegawais.id','=',$pegawai->id)

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
                                    ->where('pegawais.id','=',$pegawai->id)
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
                        ->where('pegawais.id','=',$pegawai->id)
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
        // $data['pegawai']=pegawai::where('instansi_id','=',$instansi)->count();
        return $data;
    }

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

    public function indexkadis(Request $request){

      $instansi2=instansi::all();
      $bulan=date("m");
      $tahun=date("Y");
      $tahun2=date("Y");

      $tanggalsekarang=date("Y-m-d");

      $tahun=date("Y");

      $bulan=date("m");

      $tidakhadirbulan = att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->whereMonth('atts.tanggal_att', '=', $bulan)
          ->whereYear('atts.tanggal_att', '=', $tahun)
          ->where('atts.jenisabsen_id','=','2')
          ->count();
      $sakitbulan= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->whereMonth('atts.tanggal_att', '=', $bulan)
          ->whereYear('atts.tanggal_att', '=', $tahun)
          ->where('atts.jenisabsen_id','=','5')
          ->count();
      $ijinbulan= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->whereMonth('atts.tanggal_att', '=', $bulan)
          ->whereYear('atts.tanggal_att', '=', $tahun)
          ->where('atts.jenisabsen_id','=','3')
          ->count();
      $cutibulan= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->whereMonth('atts.tanggal_att', '=', $bulan)
          ->whereYear('atts.tanggal_att', '=', $tahun)
          ->where('atts.jenisabsen_id','=','4')
          ->count();
      $tlbulan= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->whereMonth('atts.tanggal_att', '=', $bulan)
          ->whereYear('atts.tanggal_att', '=', $tahun)
          ->where('atts.jenisabsen_id','=','7')
          ->count();
      $tbbulan= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->whereMonth('atts.tanggal_att', '=', $bulan)
          ->whereYear('atts.tanggal_att', '=', $tahun)
          ->where('atts.jenisabsen_id','=','6')
          ->count();
      $terlambatbulan= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->whereMonth('atts.tanggal_att', '=', $bulan)
          ->whereYear('atts.tanggal_att', '=', $tahun)
          ->where('atts.terlambat','!=','00:00:00')
          ->count();
      $eventbulan= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->whereMonth('atts.tanggal_att', '=', $bulan)
          ->whereYear('atts.tanggal_att', '=', $tahun)
          ->where('atts.jenisabsen_id','=','8')
          ->count();
      // $tidakhadirbulan = masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
      //     ->whereMonth('periode','=',$bulan)
      //     ->whereYear('periode', '=', $tahun)
      //     ->sum('tanpa_kabar');
      // $sakitbulan= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
      //     ->whereMonth('periode','=',$bulan)
      //     ->whereYear('periode', '=', $tahun)
      //     ->sum('sakit');
      // $ijinbulan= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
      //     ->whereMonth('periode','=',$bulan)
      //     ->whereYear('periode', '=', $tahun)
      //     ->sum('ijin');
      // $cutibulan= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
      //     ->whereMonth('periode','=',$bulan)
      //     ->whereYear('periode', '=', $tahun)
      //     ->sum('cuti');
      // $tlbulan= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
      //     ->whereMonth('periode','=',$bulan)
      //     ->whereYear('periode', '=', $tahun)
      //     ->sum('tugas_luar');
      // $tbbulan= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
      //     ->whereMonth('periode','=',$bulan)
      //     ->whereYear('periode', '=', $tahun)
      //     ->sum('tugas_belajar');
      // $terlambatbulan= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
      //     ->whereMonth('periode','=',$bulan)
      //     ->whereYear('periode', '=', $tahun)
      //     ->sum('terlambat');
      // $eventbulan= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
      //     ->whereMonth('periode','=',$bulan)
      //     ->whereYear('periode', '=', $tahun)
      //     ->sum('rapatundangan');

      $tidakhadirtahun = att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->whereYear('atts.tanggal_att', '=', $tahun)
          ->where('atts.jenisabsen_id','=','2')
          ->count();
      $sakittahun= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->whereYear('atts.tanggal_att', '=', $tahun)
          ->where('atts.jenisabsen_id','=','5')
          ->count();
      $ijintahun= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->whereYear('atts.tanggal_att', '=', $tahun)
          ->where('atts.jenisabsen_id','=','3')
          ->count();
      $cutitahun= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->whereYear('atts.tanggal_att', '=', $tahun)
          ->where('atts.jenisabsen_id','=','4')
          ->count();
      $tltahun= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->whereYear('atts.tanggal_att', '=', $tahun)
          ->where('atts.jenisabsen_id','=','7')
          ->count();
      $tbtahun= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->whereYear('atts.tanggal_att', '=', $tahun)
          ->where('atts.jenisabsen_id','=','6')
          ->count();
      $terlambattahun= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->whereYear('atts.tanggal_att', '=', $tahun)
          ->where('atts.terlambat','!=','00:00:00')
          ->count();
      $eventtahun= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->whereYear('atts.tanggal_att', '=', $tahun)
          ->where('atts.jenisabsen_id','=','8')
          ->count();

      // $tidakhadirtahun = masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
      //     ->whereYear('periode', '=', $tahun)
      //     ->sum('tanpa_kabar');
      // $sakittahun= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
      //     ->whereYear('periode', '=', $tahun)
      //     ->sum('sakit');
      // $ijintahun= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
      //     ->whereYear('periode', '=', $tahun)
      //     ->sum('ijin');
      // $cutitahun= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
      //     ->whereYear('periode', '=', $tahun)
      //     ->sum('cuti');
      // $tltahun= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
      //     ->whereYear('periode', '=', $tahun)
      //     ->sum('tugas_luar');
      // $tbtahun= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
      //     ->whereYear('periode', '=', $tahun)
      //     ->sum('tugas_belajar');
      // $terlambattahun= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
      //     ->whereYear('periode', '=', $tahun)
      //     ->sum('terlambat');
      // $eventtahun= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
      //     ->whereYear('periode', '=', $tahun)
      //     ->sum('rapatundangan');

      $tanggalsekarang=date("Y-m-d");

      $tidakhadir = att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->where('atts.tanggal_att', '=', $tanggalsekarang)
          ->where('atts.jenisabsen_id','=','2')
          ->count();
      $sakit= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->where('atts.tanggal_att', '=', $tanggalsekarang)
          ->where('atts.jenisabsen_id','=','5')
          ->count();
      $ijin= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->where('atts.tanggal_att', '=', $tanggalsekarang)
          ->where('atts.jenisabsen_id','=','3')
          ->count();
      $cuti= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->where('atts.tanggal_att', '=', $tanggalsekarang)
          ->where('atts.jenisabsen_id','=','4')
          ->count();
      $tl= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->where('atts.tanggal_att', '=', $tanggalsekarang)
          ->where('atts.jenisabsen_id','=','7')
          ->count();
      $tb= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->where('atts.tanggal_att', '=', $tanggalsekarang)
          ->where('atts.jenisabsen_id','=','6')
          ->count();
      $terlambat= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->where('atts.tanggal_att', '=', $tanggalsekarang)
          ->where('atts.terlambat','!=','00:00:00')
          ->count();
      $event= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
          ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
          ->where('atts.tanggal_att', '=', $tanggalsekarang)
          ->where('atts.jenisabsen_id','=','8')
          ->count();


    //   $pegawaitahun = DB::select('SELECT nip,instansi_id,nama,@pegawai:=id,
    //   (SELECT AVG(persentase_tidakhadir)
    //   FROM masterbulanans
    //   WHERE pegawai_id=@pegawai AND YEAR(periode)="'.$tahun.'" ) AS persentasehadir,
    //   (SELECT AVG(persentase_apel)
    //   FROM masterbulanans
    //   WHERE pegawai_id=@pegawai AND YEAR(periode)="'.$tahun.'" ) AS persentaseapel,
    //   (SELECT SEC_TO_TIME( SUM(time_to_sec(total_akumulasi))) as total
    //   FROM masterbulanans
    //   WHERE pegawai_id=@pegawai AND YEAR(periode)="'.$tahun.'" ) AS total
    //   FROM pegawais where instansi_id="'.Auth::user()->instansi_id.'" ORDER BY total ASC');
    $totalnonapel=DB::raw("(SELECT pegawai_id,periode,SUM(persentase_apel) as apel from masterbulanans GROUP BY pegawai_id) as masterbulanans");
    // dd($totalnonapel);
    // $pegawaitahun = pegawai::leftJoin($totalnonapel,'pegawais.id','=','masterbulanans.pegawai_id')
    //                 ->whereMonth('masterbulanans.periode','=',$bulan)
    //                 ->whereYear('masterbulanans.periode','=',$tahun)
    //                 ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
    //                 ->get();

    $pegawaitahun = att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
                    ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                    // ->whereMonth('atts.tanggal_att', '=', $bulan)
                    // ->whereYear('atts.tanggal_att', '=', $tahun)
                    ->where('atts.tanggal_att','=',$tanggalsekarang)
                    ->where('atts.terlambat','!=','00:00:00')
                    ->paginate(30);
    // dd($pegawaitahun);

      $attstrans=atts_tran::join('pegawais','atts_trans.pegawai_id','=','pegawais.id')
          ->join('instansis','atts_trans.lokasi_alat', '=', 'instansis.id')
          ->join('instansis as pegawaiinstansis','pegawais.instansi_id', '=', 'pegawaiinstansis.id')
          ->orderBy('atts_trans.id','atts_trans.tanggal', 'desc')
          ->orderBy('atts_trans.jam','atts_trans.tanggal', 'desc')
          ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
          ->paginate(7, array('pegawaiinstansis.namaInstansi as instansiPegawai','pegawais.nama','atts_trans.jam','atts_trans.tanggal','atts_trans.status_kedatangan','instansis.namaInstansi'));

          $now=date("Y-m-d");
          $kehadiran=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
          ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
          ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
          ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
          ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
          ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
          ->where('atts.tanggal_att','=',$now)
          ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
              'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
          ->orderBy('pegawais.nama','desc')
          ->paginate(30);


      if ($request->ajax()) {
          $view = view('timeline.datatimeline',compact('attstrans'))->render();
          return response()->json(['html'=>$view]);
      }


      if (isset($id))
      {


            $pegawai=pegawai::where('status_aktif','=','1')
              ->where('nip','=',$id)
              ->count();
            if ($pegawai>0){

              $pegawais=pegawai::where('status_aktif','=','1')
                ->where('nip','=',$id)
                ->first();
                $nip=$pegawais->nip;
                $nama=$pegawais->nama;

                $tidakhadir = masterbulanan::whereMonth('periode', '=', $bulan)
                    ->whereYear('periode', '=', $tahun)
                    ->where('pegawai_id','=',$pegawais->id)
                    ->avg('persentase_tidakhadir');

                    $apel = masterbulanan::whereMonth('periode', '=', $bulan)
                        ->whereYear('periode', '=', $tahun)
                        ->where('pegawai_id','=',$pegawais->id)
                        ->avg('persentase_apel');
                $totalakumulasi = masterbulanan::
                whereMonth('periode','=',$bulan)
                ->whereYear('periode','=',$tahun)
                ->where('pegawai_id','=',$pegawais->id)
                ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(total_akumulasi))) as total'))
                ->first();
                $total=$totalakumulasi->total;
                return view('dashboard.dashboardkadis',['event'=>$event,'tahun'=>$tahun,'tidakhadir'=>$tidakhadir,
                    'sakit'=>$sakit,'ijin'=>$ijin,'cuti'=>$cuti,'tb'=>$tb,'tl'=>$tl,'terlambat'=>$terlambat,
                  'eventbulan'=>$eventbulan,'bulan'=>$bulan,'tidakhadirbulan'=>$tidakhadirbulan,
                      'sakitbulan'=>$sakit,'ijinbulan'=>$ijinbulan,'cutibulan'=>$cutibulan,'tbbulan'=>$tbbulan,'tlbulan'=>$tlbulan,'terlambatbulan'=>$terlambatbulan,
                      'eventtahun'=>$eventtahun,'tahun'=>$tahun,'tidakhadirtahun'=>$tidakhadirtahun,
                          'sakittahun'=>$sakittahun,'ijintahun'=>$ijintahun,'cutitahun'=>$cutitahun,'tbtahun'=>$tbtahun,'tltahun'=>$tltahun,'terlambattahun'=>$terlambattahun,
                    'kehadirans'=>$kehadiran,'instansis'=>$instansi2,'pegawaitahun'=>$pegawaitahun,'statuscari'=>null,'nip'=>$nip,'persentaseapel'=>round($apel,2),'tahun'=>$tahun2,'nama'=>$nama,'persentasehadir'=>round($tidakhadir,2),'totalakumulasi'=>$total],compact('attstrans'));
            }
            else {
              return view('dashboard.dashboardkadis',[
                'event'=>$event,'tahun'=>$tahun,'tidakhadir'=>$tidakhadir,
                    'sakit'=>$sakit,'ijin'=>$ijin,'cuti'=>$cuti,'tb'=>$tb,'tl'=>$tl,'terlambat'=>$terlambat,
                  'eventbulan'=>$eventbulan,'bulan'=>$bulan,'tidakhadirbulan'=>$tidakhadirbulan,
                      'sakitbulan'=>$sakit,'ijinbulan'=>$ijinbulan,'cutibulan'=>$cutibulan,'tbbulan'=>$tbbulan,'tlbulan'=>$tlbulan,'terlambatbulan'=>$terlambatbulan,
                      'eventtahun'=>$eventtahun,'tahun'=>$tahun,'tidakhadirtahun'=>$tidakhadirtahun,
                          'sakittahun'=>$sakittahun,'ijintahun'=>$ijintahun,'cutitahun'=>$cutitahun,'tbtahun'=>$tbtahun,'tltahun'=>$tltahun,'terlambattahun'=>$terlambattahun,
                'kehadirans'=>$kehadiran,'instansis'=>$instansi2,'pegawaitahun'=>$pegawaitahun,'statuscari'=>'Data pegawai tidak ditemukan.','tahun'=>$tahun2],compact('attstrans'));
            }
 
      }
      else
      {
        return view('dashboard.dashboardkadis',['event'=>$event,'tahun'=>$tahun,'tidakhadir'=>$tidakhadir,
            'sakit'=>$sakit,'ijin'=>$ijin,'cuti'=>$cuti,'tb'=>$tb,'tl'=>$tl,'terlambat'=>$terlambat,
          'eventbulan'=>$eventbulan,'bulan'=>$bulan,'tidakhadirbulan'=>$tidakhadirbulan,
              'sakitbulan'=>$sakit,'ijinbulan'=>$ijinbulan,'cutibulan'=>$cutibulan,'tbbulan'=>$tbbulan,'tlbulan'=>$tlbulan,'terlambatbulan'=>$terlambatbulan,
              'eventtahun'=>$eventtahun,'tahun'=>$tahun,'tidakhadirtahun'=>$tidakhadirtahun,
                  'sakittahun'=>$sakittahun,'ijintahun'=>$ijintahun,'cutitahun'=>$cutitahun,'tbtahun'=>$tbtahun,'tltahun'=>$tltahun,'terlambattahun'=>$terlambattahun,
                  'kehadirans'=>$kehadiran,'instansis'=>$instansi2,'pegawaitahun'=>$pegawaitahun,'statuscari'=>'','tahun'=>$tahun2],compact('attstrans'));
      }

    }



    public function indexsekda(){

      $instansi2=instansi::all();
      $bulan=date("m");
      $tahun=date("Y");
      $tahun2=date("Y");

      $pegawaibulan = DB::select('SELECT nip,nama,@pegawai:=id,
      (SELECT AVG(persentase_tidakhadir)
      FROM masterbulanans
      WHERE pegawai_id=@pegawai AND MONTH(periode)="'.$bulan.'" AND YEAR(periode)="'.$tahun.'" ) AS persentasehadir,
      (SELECT AVG(persentase_apel)
      FROM masterbulanans
      WHERE pegawai_id=@pegawai AND MONTH(periode)="'.$bulan.'" AND YEAR(periode)="'.$tahun.'" ) AS persentaseapel,
      (SELECT SEC_TO_TIME( SUM(time_to_sec(total_akumulasi))) as total
      FROM masterbulanans
      WHERE pegawai_id=@pegawai AND MONTH(periode)="'.$bulan.'" AND YEAR(periode)="'.$tahun.'" ) AS total
      FROM pegawais ORDER BY persentaseapel DESC,total DESC LIMIT 20');

      $pegawaitahun = DB::select('SELECT nip,nama,@pegawai:=id,
      (SELECT AVG(persentase_tidakhadir)
      FROM masterbulanans
      WHERE pegawai_id=@pegawai AND YEAR(periode)="'.$tahun.'" ) AS persentasehadir,
      (SELECT AVG(persentase_apel)
      FROM masterbulanans
      WHERE pegawai_id=@pegawai AND YEAR(periode)="'.$tahun.'" ) AS persentaseapel,
      (SELECT SEC_TO_TIME( SUM(time_to_sec(total_akumulasi))) as total
      FROM masterbulanans
      WHERE pegawai_id=@pegawai AND YEAR(periode)="'.$tahun.'" ) AS total
      FROM pegawais ORDER BY persentaseapel DESC,total DESC LIMIT 20');

        $instansipertahun=DB::select('SELECT namaInstansi,@instansi:=id AS id,
            (SELECT AVG(persentase_tidakhadir)
            FROM masterbulanans
            WHERE instansi_id=@instansi AND YEAR(periode)="'.$tahun.'" ) AS persentasehadir,
            (SELECT AVG(persentase_apel)
            FROM masterbulanans
            WHERE instansi_id=@instansi AND YEAR(periode)="'.$tahun.'" ) AS persentaseapel,
            (SELECT SEC_TO_TIME( SUM(time_to_sec(total_akumulasi))) as total
            FROM masterbulanans
            WHERE instansi_id=@instansi AND YEAR(periode)="'.$tahun.'" ) AS totall
            FROM instansis ORDER BY persentaseapel,totall DESC,persentasehadir ASC LIMIT 20');


      if (isset($id))
      {
            $pegawai=pegawai::where('status_aktif','=','1')
              ->where('nip','=',$id)
              ->count();
            if ($pegawai>0){

              $pegawais=pegawai::where('status_aktif','=','1')
                ->where('nip','=',$id)
                ->first();
                $nip=$pegawais->nip;
                $nama=$pegawais->nama;

                $tidakhadir = masterbulanan::whereMonth('periode', '=', $bulan)
                    ->whereYear('periode', '=', $tahun)
                    ->where('pegawai_id','=',$pegawais->id)
                    ->avg('persentase_tidakhadir');

                    $apel = masterbulanan::whereMonth('periode', '=', $bulan)
                        ->whereYear('periode', '=', $tahun)
                        ->where('pegawai_id','=',$pegawais->id)
                        ->avg('persentase_apel');
                $totalakumulasi = masterbulanan::
                whereMonth('periode','=',$bulan)
                ->whereYear('periode','=',$tahun)
                ->where('pegawai_id','=',$pegawais->id)
                ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(total_akumulasi))) as total'))
                ->first();
                $total=$totalakumulasi->total;
                return view('dashboard.dashboardsekda',['instansis'=>$instansi2,'pegawaibulan'=>$pegawaibulan,'pegawaitahun'=>$pegawaitahun,'instansitahun'=>$instansipertahun,'statuscari'=>null,'nip'=>$nip,'persentaseapel'=>round($apel,2),'tahun'=>$tahun2,'nama'=>$nama,'persentasehadir'=>round($tidakhadir,2),'totalakumulasi'=>$total]);
            }
            else {
              return view('dashboard.dashboardsekda',['instansis'=>$instansi2,'pegawaibulan'=>$pegawaibulan,'pegawaitahun'=>$pegawaitahun,'instansitahun'=>$instansipertahun,'statuscari'=>'Data pegawai tidak ditemukan.','tahun'=>$tahun2]);
            }

      }
      else
      {
        return view('dashboard.dashboardsekda',['instansis'=>$instansi2,'pegawaibulan'=>$pegawaibulan,'pegawaitahun'=>$pegawaitahun,'instansitahun'=>$instansipertahun,'statuscari'=>'','tahun'=>$tahun2]);
      }

    }

    public function dashboardgubernur(Request $request){
        return view('dashboard.dashboardgubernur');
    }

    public function datapegawaigub(){
        $users=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
        ->select('pegawais.nip','pegawais.nama')
        ->get();
        return Datatables::of($users)
                ->addColumn('action', function ($users) {
                    return '<button type="button" class="modal_data btn btn-success btn-sm" data-toggle="modal" data-nip="'.$users->nip.'" data-target="#modal_data"><i class="fa fa-user"></i> Lihat Data</button>';
                })
            ->make(true);
    }

    public function datapegawaigubdetail($id){
        $url="https://simpeg.kalselprov.go.id/api/identitas?nip=".$id;
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);
        // Closing
        curl_close($ch);
        // dd($result);
  
        //Will dump a beauty json :3
        $jsons=(json_decode($result, true));

        return $jsons;
    }
}
