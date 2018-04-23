<?php

namespace App\Http\Controllers;

use App\jadwalkerja;
use App\rulejammasuk;
use App\jadwalminggu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Yajra\Datatables\Facades\Datatables;

class JadwalKerjaController extends Controller
{
    //
    public function index(Request $request){
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }

        $jadwalkerja=array();
        $isi=array();
        $tables=jadwalkerja::leftJoin('instansis','jadwalkerjas.instansi_id','=','instansis.id')
                ->select('jadwalkerjas.*','instansis.namaInstansi')
                ->get();
        // dd($tables);
        // foreach ($tables as $table){
        //     $harikerja=rulejammasuk::where('jadwalkerja_id','=',$table->id)->count();
        //     if ($harikerja == 0) {
        //         $isi['id']=($table->id);
        //         $isi['jenis_jadwal']=($table->jenis_jadwal);
        //         array_push($jadwalkerja,$isi);
        //     }
        // }
        //
        // dd($jadwalkerja);

        if ($request->cari==""){
            $rule=rulejammasuk::leftJoin('jadwalkerjas','rulejammasuks.jadwalkerja_id','=','jadwalkerjas.id')
            ->leftJoin('instansis','jadwalkerjas.instansi_id','=','instansis.id')
            ->select('rulejammasuks.*','jadwalkerjas.jenis_jadwal','instansis.namaInstansi')
            ->paginate(40);
        }
        else
        {
            $rule=rulejammasuk::leftJoin('jadwalkerjas','rulejammasuks.jadwalkerja_id','=','jadwalkerjas.id')
            ->leftJoin('instansis','jadwalkerjas.instansi_id','=','instansis.id')
            ->select('rulejammasuks.*','jadwalkerjas.jenis_jadwal','instansis.namaInstansi')
            ->where('jadwalkerjas.jenis_jadwal','LIKE','%'.$request->cari.'%')
            ->orWhere('instansis.namaInstansi','LIKE','%'.$request->cari.'%')
            ->paginate(40);
        }
       //dd($rule);
        // $jadwalkerja=jadwalkerja::where('instansi_id',Auth::user()->instansi_id)->get();
        return view('jadwalkerja.jadwalkerja',['inforekap'=>$inforekap,'jadwalkerjas'=>$tables,'cari'=>$request->cari,'rules'=>$rule]);
    }

    public function jadwalkerjadatatable(){
        $tables=jadwalkerja::leftJoin('instansis','jadwalkerjas.instansi_id','=','instansis.id')
                ->select('jadwalkerjas.*','instansis.namaInstansi')
                ->get();

        return Datatables::of($tables)
                    ->editColumn('action', function ($tables) {
                        return '<a class="btn-sm btn-success" href="/jadwalkerja/'.encrypt($tables->id).'/edit">Edit</a>
                        <a class="btn-sm btn-danger" data-method="delete"
                           data-token="{{csrf_token()}}" href="/jadwalkerja/'.encrypt($tables->id).'/hapus">Hapus</a>';
                    })
                    ->editColumn('classdata', function ($tables) {
                        return '<a class="'.$tables->classcolor.'" ><i class="fa fa-square"></i></a>';
                    })
                    ->rawColumns(['action','classdata'])
                    ->make(true);
    }

    public function rulejadwalkerjadatatable(){
        $tables=rulejammasuk::leftJoin('jadwalkerjas','rulejammasuks.jadwalkerja_id','=','jadwalkerjas.id')
            ->leftJoin('instansis','jadwalkerjas.instansi_id','=','instansis.id')
            ->select('rulejammasuks.*','jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat','instansis.namaInstansi')
            ->get();

            return Datatables::of($tables)
                    ->editColumn('action', function ($tables) {
                        return '<a class="btn-sm btn-success" href="/rulejadwalkerja/'.encrypt($tables->id).'/edit">Edit</a>
                        <a class="btn-sm btn-danger" data-method="delete"
                           data-token="{{csrf_token()}}" href="/rulejadwalkerja/'.encrypt($tables->id).'/hapus">Hapus</a>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

    public function store(Request $request){
        $this->validate($request, [
            'jenisjadwal'=>'required',
            'awal'=>'required|min:5',
            'pulang'=>'required|min:5',
            'instansi_id'=>'required',
            'sifat'=>'required',
            'color'=>'required',
            'singkatan'=>'required'
        ]);

        $user = new jadwalkerja;
        $user->jenis_jadwal = $request->jenisjadwal;
        $user->jam_masukjadwal = $request->awal;
        $user->jam_keluarjadwal = $request->pulang;
        $user->sifat = decrypt($request->sifat);
        $user->color = ($request->color);
        $user->classcolor = ($request->classcolor);
        $user->classdata = ($request->classdata);
        $user->singkatan = $request->singkatan;
        $user->instansi_id = $request->instansi_id[0];
        $user->save();
        // dd("tes");
        return redirect()->back()->with('status','Jadwal kerja berhasil disimpan.');

    }

    public function editshow($id){
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }
        $id = decrypt($id);
        // dd($id);
        $jadwal=jadwalkerja::where('id','=',$id)->first();

    //    dd($jadwal);
        return view('jadwalkerja.editjadwalkerja',['inforekap'=>$inforekap,'jadwals'=>$jadwal]);
    }

    public function editstore(Request $request){
        $this->validate($request, [
            'jenisjadwal'=>'required|min:5',
            'awal'=>'required|min:5',
            'pulang'=>'required|min:5'
        ]);
        $table=jadwalkerja::where('id','=',$request->id)->first();
        $table->jam_masukjadwal=$request->awal;
        $table->jam_keluarjadwal=$request->pulang;
        $table->jenis_jadwal=$request->jenisjadwal;
        $table->sifat=$request->sifat;
        $table->color=$request->color;
        $table->classcolor=$request->classcolor;
        $table->classdata=$request->classdata;
        $table->singkatan=$request->singkatan;
        $table->save();
        return redirect('/jadwalkerja');
    }

    public function deletestore($id){
        $id = decrypt($id);
        $table=jadwalkerja::find($id);
        $table->delete();
        return redirect('/jadwalkerja');
    }

    public function cari(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return response()->json([]);
        }
        $kecuali=$tanpapegawai=rulejammasuk::pluck('jadwalkerja_id')->all();
        $tags = jadwalkerja::
                leftJoin('instansis','jadwalkerjas.instansi_id','=','instansis.id')
                ->where('jenis_jadwal','LIKE','%'.$term.'%')
                ->whereNotIn('jadwalkerjas.id',$kecuali)
                ->orWhere('namaInstansi','LIKE','%'.$term.'%')
                ->select('jadwalkerjas.*','instansis.namaInstansi')
                ->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->jenis_jadwal."( ".$tag->jam_masukjadwal." - ".$tag->jam_keluarjadwal." )"." - ".$tag->namaInstansi." >> ".$tag->sifat];
        }
        return response()->json($formatted_tags);
    }

    public function minggukerja(Request $request){
        $this->validate($request, [
            'jadwalkerjaminggu'=>'required',
            'checkbox2'=>'required'
        ]);
            // dd($request->checkbox2);

        foreach ($request->checkbox2 as $key=> $data){
        //    dd($data);
            $user = new jadwalminggu();
            $user->minggu = $data;
            $user->jadwalkerja_id = $request->jadwalkerjaminggu;
            $user->instansi_id = Auth::user()->instansi_id;
            $user->save();
        }
        return redirect('/harikerja');

        // $month=Carbon::now()->month;
        // $year = Carbon::now()->year;
        // $date = Carbon::createFromDate($year,$month);
        // $numberOfWeeks = floor($date->daysInMonth / Carbon::DAYS_PER_WEEK);
        // $start = [];
        // $end = [];
        // $j=1;
        // for ($i=1; $i <= $date->daysInMonth ; $i++) {
        //     Carbon::createFromDate($year,$month,$i); 
        //     $start['Week: '.$j.' Start Date']= (array)Carbon::createFromDate($year,$month,$i)->startOfWeek()->toDateString();
        //     $end['Minggu '.$j]= (array)Carbon::createFromDate($year,$month,$i)->endOfweek()->toDateString();
        //     $i+=7;
        //     $j++; 
        // }
        // // $result = array_merge($start,$end);
        // // $result['numberOfWeeks'] = ["$numberOfWeeks"];
        // // $tanggalsekarang=date('Y-m-d');

        // $tanggalsekarang="2018-01-15";
        // if ($tanggalsekarang <= $end['Minggu 1'][0]){
        //     $status=$tanggalsekarang." Minggu Pertama";
        // }
        // elseif ($tanggalsekarang <= $end['Minggu 2'][0]){
        //     $status=$tanggalsekarang." Minggu Kedua";
        // }
        // elseif ($tanggalsekarang <= $end['Minggu 3'][0]){
        //     $status=$tanggalsekarang." Minggu Ketiga";
        // }
        // elseif ($tanggalsekarang <= $end['Minggu 4'][0]){
        //     $status=$tanggalsekarang." Minggu Keempat";
        // }
        // dd($status);
        // return dd($end['Minggu 1'][0]);
        
    }

    public function hapusjadwalminggu($id){
        $id=decrypt($id);
        $table=jadwalminggu::where('jadwalkerja_id',$id)
        ->where('instansi_id','=',Auth::user()->instansi_id)
        ;
        //dd($table);
        $table->delete();
        return redirect('/harikerja');
    }

}
