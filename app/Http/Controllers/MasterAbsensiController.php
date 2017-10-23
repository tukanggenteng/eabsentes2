<?php

namespace App\Http\Controllers;

use App\att;
use App\masterbulanan;
use App\pegawai;
use App\rekapbulanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class MasterAbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //


        $sekarang=date('Y-m-d');
        $status=false;
        if ($date=1){
            $hari='Senin';
            $awal=date("Y-m-d",strtotime("-9 month",strtotime($sekarang)));
            $akhir=date("Y-m-d",strtotime("-3 days",strtotime($sekarang)));
            $status=true;
        }
        elseif ($date=2){
            $hari='Selasa';
            $awal=date("Y-m-d",strtotime("-10 days",strtotime($sekarang)));
            $akhir=date("Y-m-d",strtotime("-4 days",strtotime($sekarang)));
            $status=true;
        }

        // $bulan=date("m",strtotime("-1 month",strtotime($bulan)));
        // $tahun=date("Y");
        if ($status==true)
        {
        $pegawais=pegawai::join('rulejadwalpegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
            ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
            ->where('pegawais.status_aktif','=','1')
            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
            ->get();

        foreach ($pegawais as $pegawai) {

            $harikerja = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                // ->whereMonth('atts.tanggal_att', '=', $bulan)re
                // ->whereYear('atts.tanggal_att', '=', $tahun)
                ->where('atts.tanggal','>=',$awal)
                ->where('atts.tanggal','<=',$akhir)
                ->where('atts.jenisabsen_id','!=','9')
                ->where('atts.pegawai_id','=',$pegawai->id)
                ->select('atts.tanggal_att')
                ->count();

              //9 itu libur nasional atau cuti
            $hadir = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                // ->whereMonth('atts.tanggal_att', '=', $bulan)
                // ->whereYear('atts.tanggal_att', '=', $tahun)
                ->where('atts.tanggal','>=',$awal)
                ->where('atts.tanggal','<=',$akhir)
                ->where('jenisabsen_id', '=', '1')
                ->where('atts.pegawai_id','=',$pegawai->id)
                ->select('atts.tanggal_att')
                ->count();

            $ijinterlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                // ->whereMonth('atts.tanggal_att', '=', $bulan)
                // ->whereYear('atts.tanggal_att', '=', $tahun)
                ->where('atts.tanggal','>=',$awal)
                ->where('atts.tanggal','<=',$akhir)
                ->where('jenisabsen_id', '=', '10')
                ->where('atts.pegawai_id','=',$pegawai->id)
                ->count();

            $absen = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                // ->whereMonth('atts.tanggal_att', '=', $bulan)
                // ->whereYear('atts.tanggal_att', '=', $tahun)
                ->where('atts.tanggal','>=',$awal)
                ->where('atts.tanggal','<=',$akhir)
                ->where('jenisabsen_id', '=', '2')
                ->where('atts.pegawai_id','=',$pegawai->id)
                ->count();

            $ijin = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                // ->whereMonth('atts.tanggal_att', '=', $bulan)
                // ->whereYear('atts.tanggal_att', '=', $tahun)
                ->where('atts.tanggal','>=',$awal)
                ->where('atts.tanggal','<=',$akhir)
                ->where('jenisabsen_id', '=', '3')
                ->where('atts.pegawai_id','=',$pegawai->id)
                ->count();

            $sakit = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                // ->whereMonth('atts.tanggal_att', '=', $bulan)
                // ->whereYear('atts.tanggal_att', '=', $tahun)
                ->where('atts.tanggal','>=',$awal)
                ->where('atts.tanggal','<=',$akhir)
                ->where('jenisabsen_id', '=', '5')
                ->where('atts.pegawai_id','=',$pegawai->id)
                ->count();

            $cuti = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                // ->whereMonth('atts.tanggal_att', '=', $bulan)
                // ->whereYear('atts.tanggal_att', '=', $tahun)
                ->where('atts.tanggal','>=',$awal)
                ->where('atts.tanggal','<=',$akhir)
                ->where('jenisabsen_id', '=', '4')
                ->where('atts.pegawai_id','=',$pegawai->id)
                ->count();

            $tugasluar = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                // ->whereMonth('atts.tanggal_att', '=', $bulan)
                // ->whereYear('atts.tanggal_att', '=', $tahun)
                ->where('atts.tanggal','>=',$awal)
                ->where('atts.tanggal','<=',$akhir)
                ->where('jenisabsen_id', '=', '7')
                ->where('atts.pegawai_id','=',$pegawai->id)
                ->count();

            $tugasbelajar = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                // ->whereMonth('atts.tanggal_att', '=', $bulan)
                // ->whereYear('atts.tanggal_att', '=', $tahun)
                ->where('atts.tanggal','>=',$awal)
                ->where('atts.tanggal','<=',$akhir)
                ->where('jenisabsen_id', '=', '6')
                ->where('atts.pegawai_id','=',$pegawai->id)
                ->count();

            $rapatundangan = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                // ->whereMonth('atts.tanggal_att', '=', $bulan)
                // ->whereYear('atts.tanggal_att', '=', $tahun)
                ->where('atts.tanggal','>=',$awal)
                ->where('atts.tanggal','<=',$akhir)
                ->where('jenisabsen_id', '=', '8')
                ->where('atts.pegawai_id','=',$pegawai->id)
                ->count();

            $terlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                ->where('atts.jam_masuk', '>', $pegawai->jam_masukjadwal)
                // ->whereMonth('atts.tanggal_att', '=', $bulan)
                // ->whereYear('atts.tanggal_att', '=', $tahun)
                ->where('atts.tanggal','>=',$awal)
                ->where('atts.tanggal','<=',$akhir)
                ->whereNotNull('atts.jam_masuk')
                ->where('atts.pegawai_id','=',$pegawai->id)
                ->count();

            $tidakterlambat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                ->where('atts.jam_masuk', '<=', $pegawai->jam_masukjadwal)
                // ->whereMonth('atts.tanggal_att', '=', $bulan)
                // ->whereYear('atts.tanggal_att', '=', $tahun)
                ->where('atts.tanggal','>=',$awal)
                ->where('atts.tanggal','<=',$akhir)
                ->whereNotNull('atts.jam_masuk')
                ->where('atts.pegawai_id','=',$pegawai->id)
                ->count();

            $pulangcepat = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                ->where('atts.jam_keluar', '<', $pegawai->jam_keluarjadwal)
                ->where('atts.jam_keluar', '=', null)
                // ->whereMonth('atts.tanggal_att', '=', $bulan)
                // ->whereYear('atts.tanggal_att', '=', $tahun)
                ->where('atts.tanggal','>=',$awal)
                ->where('atts.tanggal','<=',$akhir)
                ->where('atts.jenisabsen_id', '=', '1')
                ->whereNotNull('atts.jam_masuk')
                ->where('atts.pegawai_id','=',$pegawai->id)
                ->count();

            $presentaseapel=$tidakterlambat/$harikerja*100;

            $presentasetidakhadir=$absen/$harikerja*100;

//            $totalakumulasi = DB::select('select jadwalkerjas.id,rulejadwalpegawais.pegawai_id,pegawais.id,atts.pegawai_id,atts.tanggal_att,SEC_TO_TIME( SUM(time_to_sec(atts.akumulasi_sehari))) as total,pegawais.instansi_id from atts left join pegawais ON atts.pegawai_id=pegawais.id left join rulejadwalpegawais on atts.pegawai_id=rulejadwalpegawais.id left join jadwalkerjas on rulejadwalpegawais.jadwalkerja_id=jadwalkerjas.id where pegawais.instansi_id = ? and month(atts.tanggal_att) = ? and year(atts.tanggal_att) = ? and atts.pegawai_id = ?',[Auth::user()->instansi_id,$bulan,$tahun,$pegawai->id]);
                $totalakumulasi = att::join('pegawais', 'atts.pegawai_id', '=', 'pegawais.id')
                ->join('rulejadwalpegawais', 'atts.pegawai_id', '=', 'rulejadwalpegawais.pegawai_id')
                ->join('jadwalkerjas', 'rulejadwalpegawais.jadwalkerja_id', '=', 'jadwalkerjas.id')
                ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
                // ->whereMonth('atts.tanggal_att', '=', $bulan)
                // ->whereYear('atts.tanggal_att', '=', $tahun)
                ->where('atts.tanggal','>=',$awal)
                ->where('atts.tanggal','<=',$akhir)
                ->where('atts.pegawai_id','=',$pegawai->id)
                ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(atts.akumulasi_sehari))) as total'))
                ->get();

//            dd($totalakumulasi[0]['total']);

            $table=new rekapbulanan();
            $table->periode=$awal;
            $table->pegawai_id=$pegawai->id;
            $table->hari_kerja=$harikerja;
            $table->hadir=$hadir;
            $table->tanpa_kabar=$absen;
            $table->ijin=$ijin;
            $table->sakit=$sakit;
            $table->cuti=$cuti;
            $table->tugas_luar=$tugasluar;
            $table->tugas_belajar=$tugasbelajar;
            $table->terlambat=$terlambat;
            $table->rapatundangan=$rapatundangan;
            $table->pulang_cepat=$pulangcepat;
            $table->persentase_apel=$presentaseapel;
            $table->persentase_tidakhadir=$presentasetidakhadir;
            $table->total_akumulasi=$totalakumulasi[0]['total'];
            $table->instansi_id=Auth::user()->instansi_id;
            $table->save();

            $table=new masterbulanan();
            $table->periode=$awal;
            $table->pegawai_id=$pegawai->id;
            $table->hari_kerja=$harikerja;
            $table->hadir=$hadir;
            $table->tanpa_kabar=$absen;
            $table->ijin=$ijin;
            $table->sakit=$sakit;
            $table->cuti=$cuti;
            $table->tugas_luar=$tugasluar;
            $table->tugas_belajar=$tugasbelajar;
            $table->terlambat=$terlambat;
            $table->rapatundangan=$rapatundangan;
            $table->pulang_cepat=$pulangcepat;
            $table->persentase_apel=$presentaseapel;
            $table->persentase_tidakhadir=$presentasetidakhadir;
            $table->total_akumulasi=$totalakumulasi[0]['total'];
            $table->instansi_id=Auth::user()->instansi_id;
            $table->save();
        }
        return redirect('/rekapabsensipegawai')->with('rekap','Rekap bulanan sukses.');
      }
      else {
        return redirect('/rekapabsensipegawai')->with('rekap','Rekap bulanan gagal.');
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
