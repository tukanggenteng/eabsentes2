<?php

namespace App\Http\Controllers;
use App\fingerpegawai;
use App\pegawai;
use App\dokter;
use App\perawatruangan;
use App\ruanganuser;
use App\ruangan;
use App\instansi;
use App\atts_tran;
use App\att;
use App\hapusfingerpegawai;
use App\adminpegawai;
use App\rulejadwalpegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChartKhususController extends Controller
{
    

    public function index(Request $request){
        // dd($this->notification(Auth::user()->instansi_id));

        date_default_timezone_set('Asia/Makassar');

        $tahun=date("Y");

        $bulan=date("m");

        $userruangan=ruanganuser::where('user_id','=',Auth::user()->id)
                    ->first();
        // dd($userruangan);
        $perawat=perawatruangan::where('ruangan_id','=',$userruangan->ruangan_id)->pluck('pegawai_id');
        // dd($perawat);
        $tidakhadirbulan = att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->whereMonth('atts.tanggal_att', '=', $bulan)
            ->whereYear('atts.tanggal_att', '=', $tahun)
            ->where('atts.jenisabsen_id','=','2')
            ->whereIn('atts.pegawai_id',$perawat)
            ->count();
        $sakitbulan= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->whereMonth('atts.tanggal_att', '=', $bulan)
            ->whereYear('atts.tanggal_att', '=', $tahun)
            ->where('atts.jenisabsen_id','=','5')
            ->whereIn('atts.pegawai_id',$perawat)
            ->count();
        $ijinbulan= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->whereMonth('atts.tanggal_att', '=', $bulan)
            ->whereYear('atts.tanggal_att', '=', $tahun)
            ->where('atts.jenisabsen_id','=','3')
            ->whereIn('atts.pegawai_id',$perawat)            
            ->count();
        $cutibulan= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->whereMonth('atts.tanggal_att', '=', $bulan)
            ->whereYear('atts.tanggal_att', '=', $tahun)
            ->whereIn('atts.pegawai_id',$perawat)
            ->where('atts.jenisabsen_id','=','4')
            ->count();
        $tlbulan= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->whereMonth('atts.tanggal_att', '=', $bulan)
            ->whereYear('atts.tanggal_att', '=', $tahun)
            ->whereIn('atts.pegawai_id',$perawat)
            ->where('atts.jenisabsen_id','=','7')
            ->count();
        $tbbulan= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->whereMonth('atts.tanggal_att', '=', $bulan)
            ->whereYear('atts.tanggal_att', '=', $tahun)
            ->whereIn('atts.pegawai_id',$perawat)            
            ->where('atts.jenisabsen_id','=','6')
            ->count();
        $terlambatbulan= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->whereMonth('atts.tanggal_att', '=', $bulan)
            ->whereYear('atts.tanggal_att', '=', $tahun)
            ->whereIn('atts.pegawai_id',$perawat)            
            ->where('atts.terlambat','!=','00:00:00')
            ->count();
        $eventbulan= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->whereMonth('atts.tanggal_att', '=', $bulan)
            ->whereYear('atts.tanggal_att', '=', $tahun)
            ->whereIn('atts.pegawai_id',$perawat)            
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
            ->whereIn('atts.pegawai_id',$perawat)            
            ->where('atts.jenisabsen_id','=','2')
            ->count();
        $sakittahun= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->whereYear('atts.tanggal_att', '=', $tahun)
            ->whereIn('atts.pegawai_id',$perawat)            
            ->where('atts.jenisabsen_id','=','5')
            ->count();
        $ijintahun= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->whereYear('atts.tanggal_att', '=', $tahun)
            ->whereIn('atts.pegawai_id',$perawat)            
            ->where('atts.jenisabsen_id','=','3')
            ->count();
        $cutitahun= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->whereIn('atts.pegawai_id',$perawat)            
            ->whereYear('atts.tanggal_att', '=', $tahun)
            ->where('atts.jenisabsen_id','=','4')
            ->count();
        $tltahun= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->whereYear('atts.tanggal_att', '=', $tahun)
            ->where('atts.jenisabsen_id','=','7')
            ->whereIn('atts.pegawai_id',$perawat)            
            ->count();
        $tbtahun= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->whereYear('atts.tanggal_att', '=', $tahun)
            ->where('atts.jenisabsen_id','=','6')
            ->whereIn('atts.pegawai_id',$perawat)            
            ->count();
        $terlambattahun= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->whereYear('atts.tanggal_att', '=', $tahun)
            ->where('atts.terlambat','!=','00:00:00')
            ->whereIn('atts.pegawai_id',$perawat)            
            ->count();
        $eventtahun= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->whereYear('atts.tanggal_att', '=', $tahun)
            ->where('atts.jenisabsen_id','=','8')
            ->whereIn('atts.pegawai_id',$perawat)            
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
            ->whereIn('atts.pegawai_id',$perawat)  
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->where('atts.tanggal_att', '=', $tanggalsekarang)
            ->where('atts.jenisabsen_id','=','2')          
            ->count();
        $sakit= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->where('atts.tanggal_att', '=', $tanggalsekarang)
            ->where('atts.jenisabsen_id','=','5')
            ->whereIn('atts.pegawai_id',$perawat)            
            ->count();
        $ijin= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->where('atts.tanggal_att', '=', $tanggalsekarang)
            ->where('atts.jenisabsen_id','=','3')
            ->whereIn('atts.pegawai_id',$perawat)            
            ->count();
        $cuti= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->where('atts.tanggal_att', '=', $tanggalsekarang)
            ->where('atts.jenisabsen_id','=','4')
            ->whereIn('atts.pegawai_id',$perawat)            
            ->count();
        $tl= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->where('atts.tanggal_att', '=', $tanggalsekarang)
            ->where('atts.jenisabsen_id','=','7')
            ->whereIn('atts.pegawai_id',$perawat)            
            ->count();
        $tb= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->where('atts.tanggal_att', '=', $tanggalsekarang)
            ->where('atts.jenisabsen_id','=','6')
            ->whereIn('atts.pegawai_id',$perawat)            
            ->count();
        $terlambat= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->where('atts.tanggal_att', '=', $tanggalsekarang)
            ->where('atts.terlambat','!=','00:00:00')
            ->whereIn('atts.pegawai_id',$perawat)            
            ->count();
        $event= att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
            ->where('pegawais.instansi_id', '=', Auth::user()->instansi_id)
            ->where('atts.tanggal_att', '=', $tanggalsekarang)
            ->where('atts.jenisabsen_id','=','8')
            ->whereIn('atts.pegawai_id',$perawat)            
            ->count();

        

        $tanggal=date("d");
        $bulan=date("m");
        $tahun=date("Y");
        $now=date("Y-m-d");
        $kehadiran=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->where('atts.tanggal_att','=',$now)
        ->whereIn('atts.pegawai_id',$perawat)                    
        // ->whereMonth('atts.tanggal_att','=',$bulan)
        // ->whereDay('atts.tanggal_att','=',$tanggal)
        // ->whereYear('atts.tanggal_att','=',$tahun)
        ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('pegawais.nama','desc')
        ->paginate(30);

        if (isset($request->periodeabsen)){
            $kehadiranlalu=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
            ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
            ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
            ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
            ->leftJoin('jenisabsens as keteranganmasuk', 'keteranganmasuk.id','=','atts.keteranganmasuk_id')
            ->leftJoin('jenisabsens as keterangankeluar', 'keterangankeluar.id','=','atts.keterangankeluar_id')
            ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
            ->where('atts.tanggal_att','=',$request->periodeabsen)
            // ->whereMonth('atts.tanggal_att','=',$bulan)
            // ->whereDay('atts.tanggal_att','=',$tanggal)
            // ->whereYear('atts.tanggal_att','=',$tahun)
            ->select('atts.*','jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama','keteranganmasuk.jenis_absen as keteranganmasuk_id',
            'keterangankeluar.jenis_absen as keterangankeluar_id')
            ->orderBy('atts.tanggal_att','desc')
            ->paginate(30);
        }
        else
        {
            $kehadiranlalu=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
            ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
            ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
            ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
            ->leftJoin('jenisabsens as keteranganmasuk', 'keteranganmasuk.id','=','atts.keteranganmasuk_id')
            ->leftJoin('jenisabsens as keterangankeluar', 'keterangankeluar.id','=','atts.keterangankeluar_id')
            ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
            // ->where('atts.tanggal_att','=',$now)
            // ->whereMonth('atts.tanggal_att','=',$bulan)
            // ->whereDay('atts.tanggal_att','=',$tanggal)
            // ->whereYear('atts.tanggal_att','=',$tahun)
            ->select('atts.*','jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama','keteranganmasuk.jenis_absen as keteranganmasuk_id',
            'keterangankeluar.jenis_absen as keterangankeluar_id')
            ->orderBy('atts.tanggal_att','desc')
            ->paginate(30);
        }

        

        // dd($tidakhadirbulan);

        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }

        $rulejadwal2=rulejadwalpegawai::join('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
        ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalsekarang)
        ->whereIn('rulejadwalpegawais.pegawai_id',$perawat)    
        ->select('rulejadwalpegawais.id','pegawais.nip','pegawais.nama','jadwalkerjas.jenis_jadwal','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule')
        ->orderBy('rulejadwalpegawais.tanggal_akhirrule','ASC')
        ->get();

        return view('chart.khusus.chartprivatekhusus',
            ['event'=>$event,'rulejadwals2'=>$rulejadwal2,'tahun'=>$tahun,'tidakhadir'=>$tidakhadir,
                'sakit'=>$sakit,'ijin'=>$ijin,'cuti'=>$cuti,'tb'=>$tb,'tl'=>$tl,'terlambat'=>$terlambat,
              'eventbulan'=>$eventbulan,'bulan'=>$bulan,'tidakhadirbulan'=>$tidakhadirbulan,
                  'sakitbulan'=>$sakit,'ijinbulan'=>$ijinbulan,'cutibulan'=>$cutibulan,'tbbulan'=>$tbbulan,'tlbulan'=>$tlbulan,'terlambatbulan'=>$terlambatbulan,
                  'eventtahun'=>$eventtahun,'tahun'=>$tahun,'tidakhadirtahun'=>$tidakhadirtahun,
                      'sakittahun'=>$sakittahun,'ijintahun'=>$ijintahun,'cutitahun'=>$cutitahun,'tbtahun'=>$tbtahun,'tltahun'=>$tltahun,'terlambattahun'=>$terlambattahun,
                'kehadirans'=>$kehadiran,'kehadiranlalus'=>$kehadiranlalu,'inforekap'=>$inforekap,'periodeabsen'=>$request->periodeabsen
              ]);
    }


    

    
}
