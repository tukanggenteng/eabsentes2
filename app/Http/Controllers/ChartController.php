<?php

namespace App\Http\Controllers;

use App\chat;
use App\jenisabsen;
use App\masterbulanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Validator;

use App\Events\ChatEvent;

class ChartController extends Controller
{
    //

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
        }


    }

    public function index(Request $request){
        date_default_timezone_set('Asia/Makassar');
        $tahun=date("Y");

        $tidakhadir = masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
            ->whereYear('periode', '=', $tahun)
            ->sum('tanpa_kabar');
        $sakit= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
            ->whereYear('periode', '=', $tahun)
            ->sum('sakit');
        $ijin= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
            ->whereYear('periode', '=', $tahun)
            ->sum('ijin');
        $cuti= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
            ->whereYear('periode', '=', $tahun)
            ->sum('cuti');
        $tl= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
            ->whereYear('periode', '=', $tahun)
            ->sum('tugas_luar');
        $tb= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
            ->whereYear('periode', '=', $tahun)
            ->sum('tugas_belajar');
        $terlambat= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
            ->whereYear('periode', '=', $tahun)
            ->sum('terlambat');
        $event= masterbulanan::where('instansi_id', '=', Auth::user()->instansi_id)
            ->whereYear('periode', '=', $tahun)
            ->sum('rapatundangan');

        $chats=chat::join('users','chats.user_id','=','users.id')
            ->orderBy('chats.id', 'desc')
            ->paginate(5, array('chats.user_id','chats.text','users.name','chats.created_at'));

        if ($request->ajax()) {
            $view = view('chart.datachat',compact('chats'))->render();
            return response()->json(['html'=>$view]);
        }

        return view('chart.chartprivate',
            ['event'=>$event,'tahun'=>$tahun,'tidakhadir'=>$tidakhadir,
                'sakit'=>$sakit,'ijin'=>$ijin,'cuti'=>$cuti,'tb'=>$tb,'tl'=>$tl,'terlambat'=>$terlambat],
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
