<?php

namespace App\Http\Controllers;
use App\att;
// use App\harikerja;
use App\jadwalkerja;
// use App\pegawai;
// use App\dokter;
use App\perawatruangan;
// use App\rulejadwalpegawai;
use App\rulejammasuk;
// use App\jadwalminggu;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Jobs\GenerateAttendance;


use App\keterangan_absen;



use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;




use App\Role_Hari_Libur;
use App\Pegawai_Hari_Libur;
use App\attendancecheck;
use App\rekapbulancheck;
use App\rekapminggucheck;
use App\harikerja;
use App\instansi;
use App\jadwalminggu;
use App\pegawai;
use App\dokter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\finalrekapbulanan;
use App\rekapbulanan;
use App\masterbulanan;
use App\rulejadwalpegawai;
use App\jenisabsen;

class JadwalKerjaPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        // dd($request->table_search);

        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }
        // dd($inforekap);
        $tanggalsekarang=date("Y-m-d");
        
            $rulejadwal2=rulejadwalpegawai::leftJoin('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                // ->where('tanggal_awalrule','<=',$tanggalsekarang)
                ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalsekarang)
                ->select('rulejadwalpegawais.id','pegawais.nip','pegawais.nama','jadwalkerjas.jenis_jadwal','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule')
                ->orderBy('rulejadwalpegawais.tanggal_akhirrule','ASC')
                ->paginate(30);

            $rulejadwal=pegawai::where('instansi_id',Auth::user()->instansi_id)->paginate(30);
            // $jadwalkerja=jadwalkerja::where('instansi_id','=',Auth::user()->instansi_id)
            //             ->orWhere('instansi_id','=','1')
            //             ->get();

            if (Auth::user()->role->namaRole=="rs")
            {
                $jadwalkerja=rulejammasuk::leftJoin('jadwalkerjas','rulejammasuks.jadwalkerja_id','=','jadwalkerjas.id')
                ->where('jadwalkerjas.instansi_id','=',Auth::user()->instansi_id)
                ->select('jadwalkerjas.*','rulejammasuks.jamsebelum_masukkerja','rulejammasuks.jamsebelum_pulangkerja')
                ->get();
            }
            else
            {
                $jadwalkerja=rulejammasuk::leftJoin('jadwalkerjas','rulejammasuks.jadwalkerja_id','=','jadwalkerjas.id')
                ->where('jadwalkerjas.instansi_id','=',Auth::user()->instansi_id)
                ->orWhere('jadwalkerjas.instansi_id','=','1')
                ->where('jadwalkerjas.sifat','!=','FD')
                ->select('jadwalkerjas.*','rulejammasuks.jamsebelum_masukkerja','rulejammasuks.jamsebelum_pulangkerja')
                ->get();
            }
            

            return view('jadwalkerjapegawai.jadwalkerjapegawai',['inforekap'=>$inforekap,'jadwalkerjas'=>$jadwalkerja,'rulejadwals2'=>$rulejadwal2,'rulejadwals'=>$rulejadwal]);
    }

    public function datapegawai(){
                
                $tanggalsekarang=date("Y-m-d");
                
                $datadokter=dokter::pluck('pegawai_id')->all();
                $dataperawat=perawatruangan::pluck('pegawai_id')->all();
                
                $rulejadwal=pegawai::where('instansi_id',Auth::user()->instansi_id)
                            ->whereNotIn('pegawais.id',$datadokter)
                            ->whereNotIn('pegawais.id',$dataperawat)
                            ->get();
                return Datatables::of($rulejadwal)
                    ->editColumn('action', function ($rulejadwal) {
                        return '<input type="checkbox" name="checkbox[]" value="'.encrypt($rulejadwal->id).'" class="flat-red checkbox">';
                    })
                    ->addColumn('keterangan',function($rulejadwal){
                        $tanggalsekarang=date("Y-m-d");

                        $datarules=rulejadwalpegawai::join('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                            ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                            // ->where('tanggal_awalrule','<=',$tanggalsekarang)
                            ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalsekarang)
                            ->where('rulejadwalpegawais.pegawai_id','=',$rulejadwal->id)
                            ->select('rulejadwalpegawais.id','pegawais.nip','pegawais.nama','jadwalkerjas.jenis_jadwal','jadwalkerjas.classdata','jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule')
                            ->get();
                        $hasil="";
                        foreach ($datarules as $key=>$datarule){
                            $hasil=$hasil."<span class='badge ".$datarule->classdata."'>".$datarule->jenis_jadwal."</span>";
                        }
                        return $hasil;
                    })
                    ->rawColumns(['action','keterangan'])
                    ->make(true);
    }

    public function datarulejadwalpegawai(){
        $tanggalsekarang=date("Y-m-d");
                $rulejadwal=rulejadwalpegawai::join('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                // ->where('tanggal_awalrule','<=',$tanggalsekarang)
                ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalsekarang)
                ->select('rulejadwalpegawais.id','pegawais.nip','pegawais.nama','jadwalkerjas.jenis_jadwal','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule')
                ->orderBy('rulejadwalpegawais.tanggal_akhirrule','ASC')
                ->get(30);
                return Datatables::of($rulejadwal)
                    ->addColumn('action', function ($rulejadwal) {
                        return '<input type="checkbox" name="checkbox2[]" value="'.encrypt($rulejadwal->id).'" class="flat-red cekbox2">';
                    })
                    ->editColumn('tanggal_akhirrule', function ($rulejadwal) {

                        $tanggalsekarang=date("Y-m-d");
                        $minimal=date("Y-m-d",strtotime("-4 day",strtotime($rulejadwal->tanggal_akhirrule)));
                        $minimal=strtotime($minimal);
                        $sekarangi=date("Y-m-d",strtotime("-4 day",strtotime($rulejadwal->tanggal_akhirrule)));

                        if (($minimal >= strtotime($tanggalsekarang)) && (strtotime($tanggalsekarang) <= strtotime($rulejadwal->tanggal_akhirrule))){
                            return $rulejadwal->tanggal_akhirrule;
                        }
                        else
                        {
                            return '<span class="badge bg-red">'.$rulejadwal->tanggal_akhirrule.'</span>';
                        }

                        // return '<input type="checkbox" name="checkbox[]" value="'.encrypt($rulejadwal->id).'" class="flat-red checkbox">';
                    })
                    ->addColumn('aksi', function ($rulejadwal) {
                        return '
                        <a class="btn-sm btn-danger" data-method="delete"
                        data-token="{{csrf_token()}}" href="/jadwalkerjapegawai/'. encrypt($rulejadwal->id) .'/hapus">Hapus</a>';
                    })
                    ->rawColumns(['action','tanggal_akhirrule','aksi'])
                    ->make(true);
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
        //dd($request);
        $this->validate($request, [
            'checkbox'=>'required',
            'jadwalkerjamasuk'=>'required'
        ]);
        
        $status=false;

        foreach ($request->checkbox as $data){
            $data=decrypt($data);
            $hapusspasi=str_replace(" ","",$request->daterange);
            $tanggal=explode("-",$hapusspasi);

            $tanggalhariini=date("Y-m-d");


            $tanggalawal=date("Y-m-d",strtotime($tanggal[0]));
            $tanggalakhir=date("Y-m-d",strtotime($tanggal[1]));


            $verifikasi=rulejadwalpegawai::
                where('pegawai_id','=',$data)
                ->where('jadwalkerja_id','=',$request->jadwalkerjamasuk)
                ->where('tanggal_akhirrule','>=',$tanggalawal)
                ->count();
           // dd($verifikasi);


            if ($verifikasi>0) {
                return redirect('/jadwalkerjapegawai')->with('err','Jadwal pegawai tidak berlaku, karena tanggal awal pada jenis jadwal kerja yang dipilih tidak lebih dari tanggal akhir pada jadwal kerja sebelum nya.');
            }
            else
            {
                $comparejadwalkerja=jadwalkerja::where('id','=',$request->jadwalkerjamasuk)->first();
                // dd($comparejadwalkerja);
                $datarules=rulejadwalpegawai::leftJoin('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                            ->leftJoin('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                            ->where('rulejadwalpegawais.tanggal_awalrule','>=',$tanggalawal)
                            ->where('rulejadwalpegawais.tanggal_akhirrule','<=',$tanggalakhir)
                            ->where('rulejadwalpegawais.pegawai_id','=',$data)
                            ->select('rulejadwalpegawais.id','pegawais.nip','pegawais.nama','jadwalkerjas.jenis_jadwal','jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal','rulejadwalpegawais.jadwalkerja_id','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule')
                            ->get();
                //dd($datarules);
                if ($datarules->count()==0)
                { 
                                $table = new rulejadwalpegawai();
                                $table->pegawai_id = $data;
                                $table->tanggal_awalrule = $tanggal[0];
                                $table->tanggal_akhirrule = $tanggal[1];
                                $table->jadwalkerja_id = $request->jadwalkerjamasuk;
                                $table->save();
                                
                                // ####################
                                $tanggalsekarang=date('Y-m-d');

                                $tanggalawal=$tanggal[0];
                                $tanggalakhir=$tanggal[1];
                                $pegawai_id=$data;
                                $jadwalkerja_id=$request->jadwalkerjamasuk;
                                $instansi_id=Auth::user()->instansi_id;
                                
                                $month=Carbon::now()->month;
                                $year = Carbon::now()->year;
                                $date = Carbon::createFromDate($year,$month);
                                $numberOfWeeks = floor($date->daysInMonth / Carbon::DAYS_PER_WEEK);
                                $start = [];
                                $end = [];
                                $j=1;
                                for ($i=1; $i <= $date->daysInMonth ; $i++) {
                                    Carbon::createFromDate($year,$month,$i); 
                                    $start['Week: '.$j.' Start Date']= (array)Carbon::createFromDate($year,$month,$i)->startOfWeek()->toDateString();
                                    $end['Minggu '.$j]= (array)Carbon::createFromDate($year,$month,$i)->endOfweek()->toDateString();
                                    $i+=7;
                                    $j++; 
                                }
                                // $result = array_merge($start,$end);
                                // $result['numberOfWeeks'] = ["$numberOfWeeks"];


                                

                                $diffdate=Controller::difftanggal2($tanggalawal,$tanggalakhir);
                                // dd($diffdate);
                                for ($k=0 ; $k <= $diffdate ; $k++) {
                                    // dd($k);
                                    $tanggalproses=date('Y-m-d',strtotime('+'.$k.' days',strtotime($tanggalawal)));
                                    // echo $tanggalproses;
                                    if ($tanggalproses < $tanggalsekarang)
                                    {

                                    }
                                    else
                                    {
                                        $date=date('N',strtotime($tanggalproses));

                                        if ($date==1){
                                            $hari='Senin';
                                        }
                                        elseif ($date==2){
                                            $hari='Selasa';
                                        }
                                        elseif ($date==3){
                                            $hari='Rabu';
                                        }
                                        elseif ($date==4){
                                            $hari='Kamis';
                                        }
                                        elseif ($date==5){
                                            $hari='Jumat';
                                        }
                                        elseif ($date==6){
                                            $hari='Sabtu';
                                        }
                                        elseif ($date==7){
                                            $hari='Minggu';
                                        }

                                        if ($tanggalproses <= $end['Minggu 1'][0]){
                                            $minggu=1;
                                        }
                                        elseif ($tanggalproses <= $end['Minggu 2'][0]){
                                            $minggu=2;
                                        }
                                        elseif ($tanggalproses <= $end['Minggu 3'][0]){
                                            $minggu=3;
                                        }
                                        elseif ($tanggalproses <= $end['Minggu 4'][0]){
                                            $minggu=4;
                                        }
                                        elseif ($tanggalproses >= $end['Minggu 4'][0]){
                                            $minggu=1;
                                        }
                                    

                                        $minggujadwals=jadwalminggu::where('minggu','=',$minggu)->where('jadwalkerja_id','=',$jadwalkerja_id)->where('instansi_id','=',$instansi_id)->get();
                                        // dd($minggujadwals);
                                        foreach ($minggujadwals as $key => $minggujadwal){
                                            $harikerjas=harikerja::leftJoin('jadwalkerjas','harikerjas.jadwalkerja_id','=','jadwalkerjas.id')
                                                        ->where('harikerjas.hari','=',$hari)
                                                        ->where('harikerjas.instansi_id','=',$instansi_id)
                                                        ->where('harikerjas.jadwalkerja_id','=',$minggujadwal->jadwalkerja_id)
                                                        ->distinct()
                                                        ->get(['jadwalkerja_id','hari']);

                                            // dd($harikerjas);
                                            foreach ($harikerjas as $key =>$jadwalkerja){
                                                    
                                                    $hitung=rulejadwalpegawai::leftJoin('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                                                    ->leftJoin('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                                                    ->leftJoin('harikerjas','harikerjas.jadwalkerja_id','=','jadwalkerjas.id')
                                                    ->where('harikerjas.instansi_id','=',$instansi_id)
                                                    ->where('harikerjas.hari','=',$hari)
                                                    ->where('pegawais.instansi_id','=',$instansi_id)
                                                    ->where('rulejadwalpegawais.jadwalkerja_id','=',$jadwalkerja_id)
                                                    ->where('pegawais.id','=',$pegawai_id)
                                                    ->where('rulejadwalpegawais.tanggal_awalrule','<=',$tanggalproses)
                                                    ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalproses)
                                                    ->count();

                                                    $pegawaiterkecuali=dokter::pluck('pegawai_id')->all();

                                                    $jadwalpegawais=rulejadwalpegawai::leftJoin('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                                                                ->where('pegawais.instansi_id','=',$instansi_id)
                                                                ->where('rulejadwalpegawais.jadwalkerja_id','=',$jadwalkerja_id)
                                                                ->where('rulejadwalpegawais.tanggal_awalrule','<=',$tanggalproses)
                                                                ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalproses)
                                                                ->whereNotIn('pegawais.id',$pegawaiterkecuali)
                                                                ->where('pegawais.id','=',$pegawai_id)
                                                                ->get();
                                                    // dd($jadwalpegawais);
                                                    foreach ($jadwalpegawais as $jadwalpegawai)
                                                    {
                                                        $cek=att::where('tanggal_att','=',$tanggalproses)
                                                            ->where('pegawai_id','=',$jadwalpegawai->pegawai_id)
                                                            ->where('jadwalkerja_id','=',$jadwalkerja->jadwalkerja_id)
                                                            ->count();
                                                        if ($cek > 0){

                                                        }
                                                        else{
                                                            $roleharilibur=Role_Hari_Libur::where('tanggalberlakuharilibur','=',$tanggalproses)->first();
                                                            $pegawaiharilibur=Pegawai_Hari_Libur::where('pegawai_id','',$jadwalpegawai->pegawai_id)->first();
                                                            
                                                            if (($roleharilibur!=null)&&($pegawaiharilibur!=null))
                                                            {

                                                            }
                                                            else
                                                            {   
                                                                    $details['tanggalproses']=$tanggalproses;
                                                                    $details['pegawai_id']=$data;
                                                                    $details['jadwalkerja_id']=$request->jadwalkerjamasuk;
                                                                    $details['sifat']=$jadwalkerja->sifat;
                                                                    // $details['instansi_id']=Auth::user()->instansi_id;
                                                                    dispatch(new GenerateAttendance($details));
                                                           
                                                            }
                                                        }

                                                    }
                                                
                                            
                                            }
                                        }
                                        
                                        
                                        
                                    }
                                    
                                }

                                $status=true;


                }
                else
                {

                    foreach ($datarules as $key => $value)
                    {
                        // dd("asd");
                        $statushari=true;
                        // dd($statushari);

                        //hari kerja
                        $harikerjas=harikerja::where('jadwalkerja_id','=',$request->jadwalkerjamasuk)
                                    ->where('instansi_id','=',Auth::user()->instansi_id)
                                    ->get();

                        $harikerjabase=harikerja::where('jadwalkerja_id','=',$value->jadwalkerja_id)
                                    ->where('instansi_id','=',Auth::user()->instansi_id)
                                    ->get(['hari']);
                        $arrayharikerja=[];
                        foreach ($harikerjabase as $datahari)
                        {
                            array_push($arrayharikerja,$datahari->hari);
                        }
                        // dd($harikerjabase);
                        foreach ($harikerjas as $key2 => $harikerja)
                        {
                                $harikerjabase=$arrayharikerja; 
                                if (array_search($harikerja->hari,$harikerjabase))
                                {
                                    $statushari=false;
                                    // dd($statushari);
                                    // dd (array_search($harikerja->hari,$harikerjabase));

                                    // break;
                                    // dd($statushari);
                                }
                                else
                                {
                                    // $statushari=true;
                                }
                        }
                        
                        //jammasukerjabase

                        
                        $statusjammasuk=(($comparejadwalkerja->jam_masukjadwal <= $value->jam_masukjadwal) && ($comparejadwalkerja->jam_keluarjadwal <= $value->jam_masukjadwal));
                        $statusjamkeluar=(($comparejadwalkerja->jam_masukjadwal >= $value->jam_keluarjadwal) && ($comparejadwalkerja->jam_keluarjadwal >= $value->jam_keluarjadwal));
                        //dd("masuk=".$statusjammasuk." keluar=".$statusjamkeluar." harikerja=".$statushari);
                        if ((($statushari)) || (($statusjammasuk)) || (($statusjamkeluar)))
                        {
                                $table = new rulejadwalpegawai();
                                $table->pegawai_id = $data;
                                $table->tanggal_awalrule = $tanggal[0];
                                $table->tanggal_akhirrule = $tanggal[1];
                                $table->jadwalkerja_id = $request->jadwalkerjamasuk;
                                // $table->save();
                                
                                // ####################
                                $tanggalsekarang=date('Y-m-d');

                                $tanggalawal=$tanggal[0];
                                $tanggalakhir=$tanggal[1];
                                $pegawai_id=$data;
                                $jadwalkerja_id=$request->jadwalkerjamasuk;
                                $instansi_id=Auth::user()->instansi_id;
                                
                                $month=Carbon::now()->month;
                                $year = Carbon::now()->year;
                                $date = Carbon::createFromDate($year,$month);
                                $numberOfWeeks = floor($date->daysInMonth / Carbon::DAYS_PER_WEEK);
                                $start = [];
                                $end = [];
                                $j=1;
                                for ($i=1; $i <= $date->daysInMonth ; $i++) {
                                    Carbon::createFromDate($year,$month,$i); 
                                    $start['Week: '.$j.' Start Date']= (array)Carbon::createFromDate($year,$month,$i)->startOfWeek()->toDateString();
                                    $end['Minggu '.$j]= (array)Carbon::createFromDate($year,$month,$i)->endOfweek()->toDateString();
                                    $i+=7;
                                    $j++; 
                                }
                                // $result = array_merge($start,$end);
                                // $result['numberOfWeeks'] = ["$numberOfWeeks"];


                                

                                $diffdate=Controller::difftanggal2($tanggalawal,$tanggalakhir);
                                // dd($diffdate);
                                for ($k=0 ; $k <= $diffdate ; $k++) {
                                    // dd($k);
                                    $tanggalproses=date('Y-m-d',strtotime('+'.$k.' days',strtotime($tanggalawal)));
                                    // echo $tanggalproses;
                                    if ($tanggalproses < $tanggalsekarang)
                                    {

                                    }
                                    else
                                    {
                                        $date=date('N',strtotime($tanggalproses));

                                        if ($date==1){
                                            $hari='Senin';
                                        }
                                        elseif ($date==2){
                                            $hari='Selasa';
                                        }
                                        elseif ($date==3){
                                            $hari='Rabu';
                                        }
                                        elseif ($date==4){
                                            $hari='Kamis';
                                        }
                                        elseif ($date==5){
                                            $hari='Jumat';
                                        }
                                        elseif ($date==6){
                                            $hari='Sabtu';
                                        }
                                        elseif ($date==7){
                                            $hari='Minggu';
                                        }

                                        if ($tanggalproses <= $end['Minggu 1'][0]){
                                            $minggu=1;
                                        }
                                        elseif ($tanggalproses <= $end['Minggu 2'][0]){
                                            $minggu=2;
                                        }
                                        elseif ($tanggalproses <= $end['Minggu 3'][0]){
                                            $minggu=3;
                                        }
                                        elseif ($tanggalproses <= $end['Minggu 4'][0]){
                                            $minggu=4;
                                        }
                                        elseif ($tanggalproses >= $end['Minggu 4'][0]){
                                            $minggu=1;
                                        }
                                    

                                        $minggujadwals=jadwalminggu::where('minggu','=',$minggu)->where('jadwalkerja_id','=',$jadwalkerja_id)->where('instansi_id','=',$instansi_id)->get();
                                        // dd($minggujadwals);
                                        foreach ($minggujadwals as $key => $minggujadwal){
                                            $harikerjas=harikerja::leftJoin('jadwalkerjas','harikerjas.jadwalkerja_id','=','jadwalkerjas.id')
                                                        ->where('harikerjas.hari','=',$hari)
                                                        ->where('harikerjas.instansi_id','=',$instansi_id)
                                                        ->where('harikerjas.jadwalkerja_id','=',$minggujadwal->jadwalkerja_id)
                                                        ->distinct()
                                                        ->get(['jadwalkerja_id','hari']);

                                            // dd($harikerjas);
                                            foreach ($harikerjas as $key =>$jadwalkerja){
                                                    
                                                    $hitung=rulejadwalpegawai::leftJoin('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                                                    ->leftJoin('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                                                    ->leftJoin('harikerjas','harikerjas.jadwalkerja_id','=','jadwalkerjas.id')
                                                    ->where('harikerjas.instansi_id','=',$instansi_id)
                                                    ->where('harikerjas.hari','=',$hari)
                                                    ->where('pegawais.instansi_id','=',$instansi_id)
                                                    ->where('rulejadwalpegawais.jadwalkerja_id','=',$jadwalkerja_id)
                                                    ->where('pegawais.id','=',$pegawai_id)
                                                    ->where('rulejadwalpegawais.tanggal_awalrule','<=',$tanggalproses)
                                                    ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalproses)
                                                    ->count();

                                                    $pegawaiterkecuali=dokter::pluck('pegawai_id')->all();

                                                    $jadwalpegawais=rulejadwalpegawai::leftJoin('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                                                                ->where('pegawais.instansi_id','=',$instansi_id)
                                                                ->where('rulejadwalpegawais.jadwalkerja_id','=',$jadwalkerja_id)
                                                                ->where('rulejadwalpegawais.tanggal_awalrule','<=',$tanggalproses)
                                                                ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalproses)
                                                                ->whereNotIn('pegawais.id',$pegawaiterkecuali)
                                                                ->where('pegawais.id','=',$pegawai_id)
                                                                ->get();
                                                    // dd($jadwalpegawais);
                                                    foreach ($jadwalpegawais as $jadwalpegawai)
                                                    {
                                                        $cek=att::where('tanggal_att','=',$tanggalproses)
                                                            ->where('pegawai_id','=',$jadwalpegawai->pegawai_id)
                                                            ->where('jadwalkerja_id','=',$jadwalkerja->jadwalkerja_id)
                                                            ->count();
                                                        if ($cek > 0){

                                                        }
                                                        else{
                                                            $roleharilibur=Role_Hari_Libur::where('tanggalberlakuharilibur','=',$tanggalproses)->first();
                                                            $pegawaiharilibur=Pegawai_Hari_Libur::where('pegawai_id','',$jadwalpegawai->pegawai_id)->first();
                                                            
                                                            if (($roleharilibur!=null)&&($pegawaiharilibur!=null))
                                                            {

                                                            }
                                                            else
                                                            {   
                                                                    $details['tanggalproses']=$tanggalproses;
                                                                    $details['pegawai_id']=$data;
                                                                    $details['jadwalkerja_id']=$request->jadwalkerjamasuk;
                                                                    $details['sifat']=$jadwalkerja->sifat;
                                                                    // $details['instansi_id']=Auth::user()->instansi_id;
                                                                    dispatch(new GenerateAttendance($details));
                                                           
                                                            }
                                                        }

                                                    }
                                                
                                            
                                            }
                                        }
                                        
                                        
                                        
                                    }
                                    
                                }
                            
                            if ($table->save())
                            {
                                $details['tanggalawal']=$tanggal[0];
                                $details['tanggalakhir']=$tanggal[1];
                                $details['pegawai_id']=$data;
                                $details['jadwalkerja_id']=$request->jadwalkerjamasuk;
                                $details['instansi_id']=Auth::user()->instansi_id;
                                dispatch(new GenerateAttendance($details));
                                $status=true;
                                break;
                            }
                            else
                            {
                                $status=false;
                                return redirect('/jadwalkerjapegawai')->with('err','Gagal menyimpan data !');
                            }
                            //dd("nambah statusjammasuk=".$statusjammasuk." statusjamkeluar=".$statusjamkeluar." harikerja=".$statushari);
                        }
                        else
                        {
                            $datapegawai=pegawai::where('id','=',$data)->first();
                            return redirect('/jadwalkerjapegawai')->with('err','Jadwal kerja pegawai  atas nama '.$datapegawai->nip.' '.$datapegawai->nama.' bermasalah dengan jadwal '.$value->jenis_jadwal.' !');
                            //dd("gagal nambahstatusjammasuk=".$statushari." statusjamkeluar=".$statusjamkeluar);
                        }

                        
                    }
                }    
            }
        }
        if ($status)
        {
            return redirect('/jadwalkerjapegawai')->with('success','Berhasil menyimpan jadwal pegawai !');
        }
        else
        {
            return redirect('/jadwalkerjapegawai')->with('err','Cek pengisian data !');
        }
        
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
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }

        $id=decrypt($id);
        $rulejadwal2=rulejadwalpegawai::join('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
            ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
            ->select('rulejadwalpegawais.id','rulejadwalpegawais.jadwalkerja_id','pegawais.nip','pegawais.nama','jadwalkerjas.jenis_jadwal','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule')
            ->where('rulejadwalpegawais.id','=',$id)
            ->get();


        $rulejadwal=pegawai::where('instansi_id',Auth::user()->instansi_id)->paginate(30);
        // $jadwalkerja=jadwalkerja::where('instansi_id','=',Auth::user()->instansi_id)
        //             ->orWhere('instansi_id','=','1')
        //             ->get();

        if (Auth::user()->role->namaRole=="rs")
        {
            $jadwalkerja=rulejammasuk::leftJoin('jadwalkerjas','rulejammasuks.jadwalkerja_id','=','jadwalkerjas.id')
            ->where('jadwalkerjas.instansi_id','=',Auth::user()->instansi_id)
            ->get();
        }
        else
        {
            $jadwalkerja=rulejammasuk::leftJoin('jadwalkerjas','rulejammasuks.jadwalkerja_id','=','jadwalkerjas.id')
            ->where('jadwalkerjas.instansi_id','=',Auth::user()->instansi_id)
            ->orWhere('jadwalkerjas.instansi_id','=','1')
            ->get();
        }
        return view('jadwalkerjapegawai.editjadwalkerjapegawai',['inforekap'=>$inforekap,'rulejadwals'=>$rulejadwal2,'jadwalkerjas'=>$jadwalkerja]);
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
    public function update(Request $request)
    {
        //
//        dd($id);
        $this->validate($request, [
            'jadwalkerjamasuk'=>'required'
        ]);
        $hapusspasi=str_replace(" ","",$request->daterange);
        $tanggal=explode("-",$hapusspasi);

        $table=rulejadwalpegawai::where('id','=',$request->id)->first();
        $table->jadwalkerja_id=$request->jadwalkerjamasuk;
        $table->tanggal_awalrule=$tanggal[0];
        $table->tanggal_akhirrule=$tanggal[1];
        $table->save();

        return redirect('/jadwalkerjapegawai');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id=decrypt($id);
        $table=rulejadwalpegawai::where('id','=',$id)->first();

        $tanggalhari=date('Y-m-d');
        $atts=att::where('jadwalkerja_id','=',$table->jadwalkerja_id)
                        ->where('tanggal_att','=',$tanggalhari)
                        ->where('pegawai_id','=',$table->pegawai_id)
                        ->whereNull('jam_masuk');
                        
        if ($atts->count() > 0){
            $attsdelete=att::where('jadwalkerja_id','=',$table->jadwalkerja_id)
                            ->where('tanggal_att','=',$tanggalhari)
                            ->where('pegawai_id','=',$table->pegawai_id)
                            ->whereNull('jam_masuk')
                            ->first();
            $attsdelete->delete();
        }

        


        $deleteatts=att::where('jadwalkerja_id','=',$table->jadwalkerja_id)
                    ->where('tanggal_att','>',$tanggalhari)
                    ->where('pegawai_id','=',$table->pegawai_id)->delete();
        

        $keteranganabsendelete=keterangan_absen::where('jadwalkerja_id','=',$table->jadwalkerja_id)
                                ->where('pegawai_id','=',$table->pegawai_id)->delete();

        $table->delete();
        return redirect('/jadwalkerjapegawai');
    }


    public function destroyall(Request $request)
    {
        $this->validate($request, [
            'checkbox2'=>'required',
        ]);

        foreach ($request->checkbox2 as $data){
            $data=decrypt($data);
            $table=rulejadwalpegawai::find($data);
            $tanggalhari=date('Y-m-d');
            $atts=att::where('jadwalkerja_id','=',$table->jadwalkerja_id)
                            ->where('tanggal_att','=',$tanggalhari)
                            ->where('pegawai_id','=',$table->pegawai_id)
                            ->whereNull('jam_masuk');
                            
            if ($atts->count() > 0){
                $attsdelete=att::where('jadwalkerja_id','=',$table->jadwalkerja_id)
                                ->where('tanggal_att','=',$tanggalhari)
                                ->where('pegawai_id','=',$table->pegawai_id)
                                ->whereNull('jam_masuk')
                                ->first();
                $attsdelete->delete();
            }
            
            $deleteatts=att::where('jadwalkerja_id','=',$table->jadwalkerja_id)
                    ->where('tanggal_att','>',$tanggalhari)
                    ->where('pegawai_id','=',$table->pegawai_id)->delete();
                    
            $keteranganabsendelete=keterangan_absen::where('jadwalkerja_id','=',$table->jadwalkerja_id)
                                ->where('pegawai_id','=',$table->pegawai_id)->delete();

            $table->delete();
        }
        

        
        return redirect('/jadwalkerjapegawai');
    }

}
