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
        if (isset($id))
        {

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

                  $tidakhadir = masterbulanan::whereMonth('periode', '=', $bulan)
                      ->whereYear('periode', '=', $tahun)
                      ->where('pegawai_id','=',$pegawais->id)
                      ->avg('persentase_tidakhadir');

                      $apel = masterbulanan::whereMonth('periode', '=', $bulan)
                          ->whereYear('periode', '=', $tahun)
                          ->where('pegawai_id','=',$pegawais->id)
                          ->avg('persentase_apel');

                          // dd($tidakhadir);
                  $totalakumulasi = masterbulanan::
                  whereMonth('periode','=',$bulan)
                  ->whereYear('periode','=',$tahun)
                  ->where('pegawai_id','=',$pegawais->id)
                  ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(total_akumulasi))) as total'))
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
                  return view('dashboard.pegawaidetail',['kehadirans'=>$kehadiran,'statuscari'=>null,'nip'=>$nip,'persentaseapel'=>round($apel,2),'tahun'=>$tahun2,'nama'=>$nama,'persentasehadir'=>round($tidakhadir,2),'totalakumulasi'=>$total]);
              }
              else {
                return view('dashboard.pegawaidetail',['statuscari'=>'Data pegawai tidak ditemukan.','tahun'=>$tahun2]);
              }

        }
        else
        {
          return view('dashboard.pegawaidetail',['statuscari'=>'','tahun'=>$tahun2]);
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
      $tahun=date("Y");

      $persentasetidakhadir=array();
      $persentaseapel=array();
      $seluruh=array();
      for ($a = 1; $a <= 2; $a++) {
          if ($a=="1")
          {
              for ($i = 1; $i <= 12; $i++) {
                  $tidakhadir = finalrekapbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
                      ->whereMonth('periode', '=', $i)
                      ->whereYear('periode', '=', $tahun)
                      ->sum('persentase_tidakhadir');
                  $patokan = finalrekapbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
                      ->whereMonth('periode', '=', $i)
                      ->whereYear('periode', '=', $tahun)
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
                  $absen = finalrekapbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
                      ->whereMonth('periode', '=', $i)
                      ->whereYear('periode', '=', $tahun)
                      ->sum('persentase_apel');
                  $patokan = finalrekapbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
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

    public function datapegawai(Request $request){
      $tahun=date("Y");

      $persentasetidakhadir=array();
      $persentaseapel=array();
      $seluruh=array();
      $nip=$request->nip;
      $pegawais=pegawai::where('status_aktif','=','1')
        ->where('nip','=',$request->nip)
        ->first();

        // dd($pegawais->id);

      for ($a = 1; $a <= 2; $a++) {
          if ($a=="1")
          {
              for ($i = 1; $i <= 12; $i++) {
                  $tidakhadir = masterbulanan::whereMonth('periode', '=', $i)
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
                  $absen = masterbulanan::whereMonth('periode', '=', $i)
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
