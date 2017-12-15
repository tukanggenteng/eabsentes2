<?php

namespace App\Http\Controllers;

use App\chat;
use App\att;
use App\jenisabsen;
use App\User;
use App\masterbulanan;
use App\rulejadwalpegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Validator;

use App\Events\ChatEvent;

class ChartController extends Controller
{


    public function store(Request $request){

        date_default_timezone_set('Asia/Makassar');
        $tanggal=date("Y-m-d H:i:s");


        $rules=array(
            'user_id'=>'required',
            'text'=>'required',
            'name'=>'required'
        );


        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else {
            $table= new chat();
            $table->user_id=$request->user_id;
            $table->text=$request->text;
            $table->save();
            $user_id=$request->user_id;
            $name=$request->name;
            $text=$request->text;
            event(new ChatEvent($user_id,$name,$request->text,$tanggal));
            return "Succes";
        }


    }

    public function index(Request $request){
        date_default_timezone_set('Asia/Makassar');

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

        $chats=chat::join('users','chats.user_id','=','users.id')
            ->orderBy('chats.created_at', 'desc')
            ->paginate(5, array('chats.user_id','chats.text','users.name','chats.created_at'));

        if ($request->ajax()) {
            $view = view('chart.datachat',compact('chats'))->render();
            return response()->json(['html'=>$view]);
        }

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
        // ->whereMonth('atts.tanggal_att','=',$bulan)
        // ->whereDay('atts.tanggal_att','=',$tanggal)
        // ->whereYear('atts.tanggal_att','=',$tahun)
        ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('pegawais.nama','desc')
        ->paginate(30);

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
        // ->where('tanggal_awalrule','<=',$tanggalsekarang)
        ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalsekarang)
        ->select('rulejadwalpegawais.id','pegawais.nip','pegawais.nama','jadwalkerjas.jenis_jadwal','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule')
        ->orderBy('rulejadwalpegawais.tanggal_akhirrule','ASC')
        ->get();

        return view('chart.chartprivate',
            ['event'=>$event,'rulejadwals2'=>$rulejadwal2,'tahun'=>$tahun,'tidakhadir'=>$tidakhadir,
                'sakit'=>$sakit,'ijin'=>$ijin,'cuti'=>$cuti,'tb'=>$tb,'tl'=>$tl,'terlambat'=>$terlambat,
              'eventbulan'=>$eventbulan,'bulan'=>$bulan,'tidakhadirbulan'=>$tidakhadirbulan,
                  'sakitbulan'=>$sakit,'ijinbulan'=>$ijinbulan,'cutibulan'=>$cutibulan,'tbbulan'=>$tbbulan,'tlbulan'=>$tlbulan,'terlambatbulan'=>$terlambatbulan,
                  'eventtahun'=>$eventtahun,'tahun'=>$tahun,'tidakhadirtahun'=>$tidakhadirtahun,
                      'sakittahun'=>$sakittahun,'ijintahun'=>$ijintahun,'cutitahun'=>$cutitahun,'tbtahun'=>$tbtahun,'tltahun'=>$tltahun,'terlambattahun'=>$terlambattahun,
                'kehadirans'=>$kehadiran,'inforekap'=>$inforekap
              ],
            compact('chats'));
    }

    public function data(){
        $tahun=date("Y");

        $persentasetidakhadir=array();
        $persentaseapel=array();
        $seluruh=array();
        for ($a = 1; $a <= 2; $a++) {
            if ($a=="1")
            {
                for ($i = 1; $i <= 12; $i++) {
                    $tidakhadir = masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
                        ->whereMonth('periode', '=', $i)
                        ->whereYear('periode', '=', $tahun)
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
                    $absen = masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
                        ->whereMonth('periode', '=', $i)
                        ->whereYear('periode', '=', $tahun)
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

    public function storechat(Request $request){

        $table= new chat();
        $table->user_id=$request->user_id;
        $table->text=$request->text;
        $table->save();
        $pegawai=User::where('id','=',$request->user_id)->first();
        $jamsekarang=date("Y-m-d H:i:s");

        event(new ChatEvent($request->user_id,$pegawai->nama,$request->text, $jamsekarang));
        return $request->user_id.$pegawai->nama.$request->text. $jamsekarang;
    }


    public function datacari(Request $request){
        $tahun=date("Y");

        $persentasetidakhadir=array();
        $persentaseapel=array();
        $seluruh=array();
        for ($a = 1; $a <= 2; $a++) {
            if ($a=="1")
            {
                for ($i = 1; $i <= 12; $i++) {
                    $tidakhadir = masterbulanan::where('instansi_id', '=',Auth::user()->instansi_id)
                        ->whereMonth('periode', '=', $i)
                        ->whereYear('periode', '=', $request->tahun)
                        ->sum('persentase_tidakhadir');
                    if ($tidakhadir!=null)
                    {
                        array_push($persentasetidakhadir,$tidakhadir);
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
                    $absen = masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
                        ->whereMonth('periode', '=', $i)
                        ->whereYear('periode', '=', $request->tahun)
                        ->sum('persentase_apel');
                    if ($absen!=null)
                    {
                        array_push($persentaseapel,$absen);
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
}
