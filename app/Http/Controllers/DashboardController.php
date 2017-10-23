<?php

namespace App\Http\Controllers;
use App\pegawai;
use App\masterbulanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\instansi;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $instansi2=instansi::all();
        $bulan=date("m");
        $tahun=date("Y");
        $tahun2=date("Y");

        $pegawaibulan = DB::select('SELECT nip,nama,@pegawai:=id,
(SELECT (((SUM(hadir))/(SUM(hari_kerja)))*100)
FROM masterbulanans
WHERE pegawai_id=@pegawai AND MONTH(periode)="'.$bulan.'" AND YEAR(periode)="'.$tahun.'" ) AS persentasehadir,
(SELECT AVG(persentase_apel)
FROM masterbulanans
WHERE pegawai_id=@pegawai AND MONTH(periode)="'.$bulan.'" AND YEAR(periode)="'.$tahun.'" ) AS persentaseapel,
(SELECT SEC_TO_TIME( SUM(time_to_sec(total_akumulasi))) as total
FROM masterbulanans
WHERE pegawai_id=@pegawai AND MONTH(periode)="'.$bulan.'" AND YEAR(periode)="'.$tahun.'" ) AS total
FROM pegawais ORDER BY persentasehadir DESC,persentaseapel DESC,total DESC LIMIT 20');
        // dd($pegawaitahun);

        $pegawaitahun = DB::select('SELECT nip,nama,@pegawai:=id,
(SELECT (((SUM(hadir))/(SUM(hari_kerja)))*100)
FROM masterbulanans
WHERE pegawai_id=@pegawai AND YEAR(periode)="'.$tahun.'" ) AS persentasehadir,
(SELECT AVG(persentase_apel)
FROM masterbulanans
WHERE pegawai_id=@pegawai AND YEAR(periode)="'.$tahun.'" ) AS persentaseapel,
(SELECT SEC_TO_TIME( SUM(time_to_sec(total_akumulasi))) as total
FROM masterbulanans
WHERE pegawai_id=@pegawai AND YEAR(periode)="'.$tahun.'" ) AS total
FROM pegawais ORDER BY persentasehadir DESC,persentaseapel DESC,total DESC LIMIT 20');

    $instansipertahun=DB::select('SELECT namaInstansi,@instansi:=id AS id,
(SELECT (((SUM(hadir))/(SUM(hari_kerja)))*100)
FROM masterbulanans
WHERE instansi_id=@instansi AND YEAR(periode)="'.$tahun.'" ) AS persentasehadir,
(SELECT AVG(persentase_apel)
FROM masterbulanans
WHERE instansi_id=@instansi AND YEAR(periode)="'.$tahun.'" ) AS persentaseapel,
(SELECT SEC_TO_TIME( SUM(time_to_sec(total_akumulasi))) as total
FROM masterbulanans
WHERE instansi_id=@instansi AND YEAR(periode)="'.$tahun.'" ) AS totall
FROM instansis ORDER BY persentasehadir DESC,persentaseapel DESC,totall DESC LIMIT 20');


        if (isset($request->search))
        {
              $pegawai=pegawai::where('status_aktif','=','1')
                ->where('nip','=',$request->search)
                ->count();
                // dd($pegawai);
              if ($pegawai>0){

                $pegawais=pegawai::where('status_aktif','=','1')
                  ->where('nip','=',$request->search)
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
                  // dd($totalakumulasi);
                  return view('dashboard.dashboard',['instansis'=>$instansi2,'pegawaibulan'=>$pegawaibulan,'pegawaitahun'=>$pegawaitahun,'instansitahun'=>$instansipertahun,'statuscari'=>null,'nip'=>$nip,'persentaseapel'=>round($apel,2),'tahun'=>$tahun2,'nama'=>$nama,'persentasehadir'=>round($tidakhadir,2),'totalakumulasi'=>$total]);
              }
              else {
                return view('dashboard.dashboard',['instansis'=>$instansi2,'pegawaibulan'=>$pegawaibulan,'pegawaitahun'=>$pegawaitahun,'instansitahun'=>$instansipertahun,'statuscari'=>'Data pegawai tidak ditemukan.','tahun'=>$tahun2]);
              }

        }
        else
        {
          return view('dashboard.dashboard',['instansis'=>$instansi2,'pegawaibulan'=>$pegawaibulan,'pegawaitahun'=>$pegawaitahun,'instansitahun'=>$instansipertahun,'statuscari'=>'','tahun'=>$tahun2]);
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

    public function datatahun(Request $request){
      $tahun=$request->periodetahun;

      $persentasetidakhadir=array();
      $persentaseapel=array();
      $seluruh=array();
      for ($a = 1; $a <= 2; $a++) {
          if ($a=="1")
          {
              for ($i = 1; $i <= 12; $i++) {
                  $tidakhadir = masterbulanan::where('instansi_id', '=', $request->instansitahun)
                      ->whereMonth('periode', '=', $i)
                      ->whereYear('periode', '=', $tahun)
                      ->avg('persentase_tidakhadir');
                  $patokan = masterbulanan::where('instansi_id', '=', $request->instansitahun)
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
                  $absen = masterbulanan::where('instansi_id', '=', $request->instansitahun)
                      ->whereMonth('periode', '=', $i)
                      ->whereYear('periode', '=', $tahun)
                      ->avg('persentase_apel');
                  $patokan = masterbulanan::where('instansi_id', '=', $request->instansitahun)
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
                      ->avg('persentase_tidakhadir');
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
                      ->avg('persentase_apel');
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
}
