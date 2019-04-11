<?php

namespace App\Http\Controllers;

use App\att;
// use App\harikerja;
use App\jadwalkerja;
// use App\pegawai;
// use App\dokter;
use App\perawatruangan;
use App\ruanganuser;
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

use Illuminate\Pagination\LengthAwarePaginator;

class JadwalKerjaPegawaiHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $userruangan=ruanganuser::where('user_id','=',Auth::user()->id)->count();
        if ($userruangan>0){
            $userruangan2=ruanganuser::where('user_id','=',Auth::user()->id)->first();
            // $datadokter=dokter::pluck('pegawai_id')->all();
            // dd($userruangan->ruangan_id);
            $dataperawat=perawatruangan::where('ruangan_id',$userruangan2->ruangan_id)->pluck('pegawai_id');

            $pegawais=pegawai::where('instansi_id','=',Auth::user()->instansi_id)
                            ->whereIn('id',$dataperawat)
                            ->get();
            // dd($dataperawat);
        }
        else
        {
                $pegawais=pegawai::where('instansi_id','=',Auth::user()->instansi_id)
                            ->get();
        }

        $data= array();
        $subdata=array();


        foreach ($pegawais as $pegawai)
        {
            $bulan=date('m');
            $tahun=date('Y');

            $subdata['id']=$pegawai->id;
            $subdata['nip']=$pegawai->nip;
            $subdata['nama']=$pegawai->nama;
            $subdata['periode']=[];

            if ($request->table_search==""){
                $jadwals=rulejadwalpegawai::leftJoin('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                    // ->leftJoin('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                    ->select('rulejadwalpegawais.*','jadwalkerjas.jenis_jadwal','jadwalkerjas.singkatan','jadwalkerjas.classdata')
                    ->where('rulejadwalpegawais.pegawai_id','=',$pegawai->id)
                    // ->where('rulejadwalpegawais.pegawai_id','=','830')
                    ->whereMonth('rulejadwalpegawais.tanggal_awalrule','<=',$bulan)
                    ->whereMonth('rulejadwalpegawais.tanggal_akhirrule','>=',$bulan)
                    ->whereYear('rulejadwalpegawais.tanggal_awalrule','<=',$tahun)
                    ->whereYear('rulejadwalpegawais.tanggal_akhirrule','>=',$tahun)
                    ->get();
                    $request->table_search=date('m-Y');
            }
            else
            {
                $date=explode("-",$request->table_search);


                $jadwals=rulejadwalpegawai::leftJoin('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                    ->select('rulejadwalpegawais.*','jadwalkerjas.jenis_jadwal','jadwalkerjas.singkatan','jadwalkerjas.classdata')
                    ->where('rulejadwalpegawais.pegawai_id','=',$pegawai->id)
                    // ->where('rulejadwalpegawais.pegawai_id','=','830')
                    ->whereMonth('rulejadwalpegawais.tanggal_awalrule','<=',$date[0])
                    ->whereMonth('rulejadwalpegawais.tanggal_akhirrule','>=',$date[0])
                    ->whereYear('rulejadwalpegawais.tanggal_awalrule','<=',$date[1])
                    ->whereYear('rulejadwalpegawais.tanggal_akhirrule','>=',$date[1])
                    ->get();
            }
            
            $i=0;

            $subdata2=array();

            foreach ($jadwals as $jadwal){
                $subdata2['jenis_jadwal']=$jadwal->jenis_jadwal;
                $subdata2['singkatan']=$jadwal->singkatan;
                $subdata2['classdata']=$jadwal->classdata;
                $subdata2['tanggal_awalrule']=$jadwal->tanggal_awalrule;
                $subdata2['tanggal_akhirrule']=$jadwal->tanggal_akhirrule;
                array_push($subdata['periode'],$subdata2);
            }

            array_push($data,$subdata);
        }

        $article=collect($data);

        // pagination
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 30;
        $currentResults = $article->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $results = new LengthAwarePaginator($currentResults, $article->count(), $perPage);

        // dd($collect->count());

        // $tables=pegawai::leftJoin('rulejadwalpegawais','pegawais.id','=','rulejadwalpegawais.pegawai_id')
        //     ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        //     ->where('pegawais.id','=','830')
        //     ->select('pegawais.*','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule','rulejadwalpegawais.jadwalkerja_id')
        //     ->paginate(50);
        // dd($results);
        return view('jadwalkerjapegawai.jadwalkerjapegawaiharian',['results'=>$results,'table_search'=>$request->table_search])->with('function', new Controller);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function eventcalendartoday(Request $request)
    {
        $nip=decrypt($request->idemploye);
        $awal=date('Y-m-d',strtotime($request->start));
        $akhir=date('Y-m-d',strtotime($request->end));
        $bulan=date('m',strtotime($awal));
        $tahun=date('Y',strtotime($awal));
        // dd($bulan);
        $event=array();

        $datas=rulejadwalpegawai::leftJoin('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
               ->leftJoin('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
               ->where('pegawais.id','=',$nip)
            //    ->whereMonth('rulejadwalpegawais.tanggal_awalrule','<=',$bulan)
            //    ->whereMonth('rulejadwalpegawais.tanggal_akhirrule','>=',$bulan)
            //    ->whereYear('rulejadwalpegawais.tanggal_awalrule','=',$tahun)
            //    ->whereYear('rulejadwalpegawais.tanggal_akhirrule','=',$tahun)
            // //    ->where('rulejadwalpegawais.jadwalkerja_id','=','1')
            // //    ->whereYear('rulejadwalpegawais.tanggal_awalrule','=',$tahun)
            // //    ->whereYear('rulejadwalpegawais.tanggal_akhirrule','<=',$tahun)
            // //    ->where('rulejadwalpegawais.tanggal_awalrule','<=',$awal)
            // //    ->where('rulejadwalpegawais.tanggal_awalrule','>=',$awal)
            // //    ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$akhir)
            // //    ->whereDate('rulejadwalpegawais.tanggal_awalrule','>=',$awal)
            // //    ->whereDate('rulejadwalpegawais.tanggal_akhirrule','<=',$akhir)
            // //    ->whereDate('rulejadwalpegawais.tanggal_awalrule','<=',$akhir)
            // //    ->whereDate('rulejadwalpegawais.tanggal_akhirrule','>=',$awal)
               ->select('rulejadwalpegawais.*','pegawais.nip','jadwalkerjas.jenis_jadwal',
                    'jadwalkerjas.jam_masukjadwal','jadwalkerjas.color','jadwalkerjas.singkatan','jadwalkerjas.jam_keluarjadwal')
               ->get();
        // return $datas;

        foreach ($datas as $data){
            $e=array();
            $banyak=$this->difftanggal($data->tanggal_awalrule,$data->tanggal_akhirrule);
            // $banyak=$this->difftanggal($awal,$akhir);
            // dd($banyak);
            for ($x = 0; $x < $banyak+1; $x++)
            {
                if (($awal <= date('Y-m-d', strtotime($data->tanggal_awalrule.' +'.$x.' day '))) && ($akhir>=date('Y-m-d', strtotime($data->tanggal_awalrule.' +'.$x.' day ')))) {
                    $e['id']=encrypt($data->id);
                    $e['title']=$data->singkatan;
                    // $tanggalawal=date('Y-m-d',strtotime($data->tanggal_awalrule.' '.$x.' day'));
                    $e['start']=date('Y-m-d H:i:s', strtotime($data->tanggal_awalrule.' +'.$x.' day '.$data->jam_masukjadwal));
                    $e['end']=date('Y-m-d H:i:s', strtotime($data->tanggal_awalrule.' +'.$x.' day '.$data->jam_keluarjadwal));

                    // $e['start']=date('Y-m-d H:i:s', strtotime($awal.' '.$x.' day '.$data->jam_masukjadwal));
                    // $e['end']=date('Y-m-d H:i:s', strtotime($awal.' '.$x.' day '.$data->jam_keluarjadwal));
                    $e['allDay']=false;

                    $warna=rtrim($data->color,")");
                    $warna2=ltrim($warna,"rgb(");
                    $warna3=explode(", ",$warna2);

                    $warna4=$this->fromRGB($warna3[0],$warna3[1],$warna3[2]);
                    $e['backgroundColor']=$warna4;
                    $e['borderColor']=$warna4;
                    array_push($event,$e);
                }
            }
        }
        // dd($event);
        return $event;
    }

    public function eventcalendardelete(Request $request)
    {
        $id=decrypt($request->id);

        $table=rulejadwalpegawai::find($id);

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
                            ->delete();
            $keteranganabsendelete=keterangan_absen::where('jadwalkerja_id','=',$table->jadwalkerja_id)
                            ->where('pegawai_id','=',$table->pegawai_id)->delete();
        }
        if ($table->delete())
        {
            return response()->json("success");
        }
        else
        {
            return response()->json("failed");
        }
        // return response()->json("success");;
    }

    

    public function eventcalendarstore(Request $request)
    {
            $id=decrypt($request->idemploye);

            function isBetween($xs, $xe ,$ns,$ne)
            {
                
                return ($xs >= $ns && $xs <= $ne) || ($xe >= $ns && $xe <= $ne);

                
            }

        

            $tanggalhariini=date("Y-m-d");

            $tanggalawal=$request->awalrule;
            
            $jadwalkerjaid=decrypt($request->jadwalid);
            
            $verifikasi=rulejadwalpegawai::
                where('pegawai_id','=',$id)
                ->where('jadwalkerja_id','=',$jadwalkerjaid)
                ->where('tanggal_akhirrule','=',$tanggalawal)
                ->where('tanggal_awalrule','=',$tanggalawal)
                ->count();



            if ($verifikasi>0) {
                // dd("sd");
                return response()->json("failed");
                // return redirect('/jadwalkerjapegawai')->with('err','Jadwal pegawai tidak berlaku, karena tanggal awal pada jenis jadwal kerja yang dipilih tidak lebih dari tanggal akhir pada jadwal kerja sebelum nya.');
            }
            else
            {
                $comparejadwalkerja=jadwalkerja::where('id','=',$jadwalkerjaid)->first();
                // dd($comparejadwalkerja);
                $datarules=rulejadwalpegawai::leftJoin('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                            ->leftJoin('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                            ->where('rulejadwalpegawais.tanggal_awalrule','>=',$tanggalawal)
                            ->where('rulejadwalpegawais.tanggal_akhirrule','<=',$tanggalawal)
                            ->where('rulejadwalpegawais.pegawai_id','=',$id)
                            ->select('rulejadwalpegawais.id','pegawais.nip','pegawais.nama','jadwalkerjas.jenis_jadwal','jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal','rulejadwalpegawais.jadwalkerja_id','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule')
                            ->get();
                // dd($datarules);
                if ($datarules->count()==0)
                {
                    
                    $table = new rulejadwalpegawai();
                    $table->pegawai_id = $id;
                    $table->tanggal_awalrule = $tanggalawal;
                    $table->tanggal_akhirrule = $tanggalawal;
                    $table->jadwalkerja_id = $jadwalkerjaid;
                    if ($table->save())
                    {
                        // ####################
                        $tanggalsekarang=date('Y-m-d');

                        $tanggalawal=$tanggalawal;
                        $tanggalakhir=$tanggalawal;
                        $pegawai_id=$id;
                        $jadwalkerja_id=$jadwalkerjaid;
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
                                                            $sifatjadwalkerja=jadwalkerja::where('id','=',$jadwalkerjaid)->first();
                                                            $details['tanggalproses']=$tanggalproses;
                                                            $details['pegawai_id']=$id;
                                                            $details['jadwalkerja_id']=$jadwalkerjaid;
                                                            $details['sifat']=$sifatjadwalkerja->sifat;

                                                            // $details['instansi_id']=Auth::user()->instansi_id;
                                                            dispatch(new GenerateAttendance($details));
                                                        
                                                        // $user = new att();
                                                        // $user->pegawai_id = $pegawai_id;
                                                        // $user->tanggal_att=$tanggalproses;
                                                        // $user->terlambat='00:00:00';
                                                        // $user->apel='0';
                                                        // $user->jadwalkerja_id=$jadwalkerja_id;
                                                        // if ($jadwalkerja->sifat=="FD")
                                                        // {
                                                        //     $user->jenisabsen_id = '13';

                                                        // }
                                                        // else
                                                        // {
                                                        //     $user->jenisabsen_id = '2';

                                                        // }
                                                        // $user->akumulasi_sehari='00:00:00';
                                                        // $user->save();
                                                    }
                                                }

                                            }
                                        
                                    
                                    }
                                }
                                
                                
                                
                            }
                            
                        }
                    }
                    return response()->json("success");

                }
                else
                {
                    
                    foreach ($datarules as $key => $value){
                        // dd("asd");
                        $statushari=true;


                        //hari kerja
                        $harikerjas=harikerja::where('jadwalkerja_id','=',$jadwalkerjaid)
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
                         
                        foreach ($harikerjas as $key2 => $harikerja)
                        {
                                $harikerjabase=$arrayharikerja; 
                                if (array_search($harikerja->hari,$harikerjabase))
                                {
                                    $statushari=false;
                                    break;
                                    //dd($statushari);
                                }
                                else
                                {
                                    // $statushari=false;
                                }
                        }
                        
                        // return response()->json($statushari);
                        
                        //jammasukerjabase
                        // $value -> rulejadwalkerjapegawai
                        // $comparejadwalkerja-> jadwalkerja baru yang ingin diinput

                        

                        if (($comparejadwalkerja->lewathari==true))
                        {
                            $status=isBetween(
                                    Carbon::createFromFormat('Y-m-d H:i:s', $tanggalawal." ".$value->jam_masukjadwal)->subSecond(),
                                    Carbon::createFromFormat('Y-m-d H:i:s', $tanggalawal." ".$value->jam_keluarjadwal)->subSecond(),
                                    Carbon::createFromFormat('Y-m-d H:i:s', $tanggalawal." ".$comparejadwalkerja->jam_masukjadwal),
                                    Carbon::createFromFormat('Y-m-d H:i:s', $tanggalawal." ".$comparejadwalkerja->jam_keluarjadwal)->addDay()
                                );

                           
                            // $statusjamkeluar=(($comparejadwalkerja->jam_masukjadwal <= $value->jam_keluarjadwal) && 
                            //                     ($comparejadwalkerja->jam_keluarjadwal <= $value->jam_keluarjadwal));
                        }
                        else
                        {
                            $status=isBetween(
                                Carbon::createFromFormat('Y-m-d H:i:s', $tanggalawal." ".$value->jam_masukjadwal)->subSecond(),
                                Carbon::createFromFormat('Y-m-d H:i:s', $tanggalawal." ".$value->jam_keluarjadwal)->subSecond(),
                                Carbon::createFromFormat('Y-m-d H:i:s', $tanggalawal." ".$comparejadwalkerja->jam_masukjadwal),
                                Carbon::createFromFormat('Y-m-d H:i:s', $tanggalawal." ".$comparejadwalkerja->jam_keluarjadwal) 
                            );
                            // dd($status);
                        }
                        

                        if ($statushari)
                        {
                            $masuk="true";
                        }
                        else
                        {
                            $masuk="false";
                        }

                        // return response()->json(($masuk));

                        // dd($statushari);
                        if ($statushari)
                        {
                            // dd($status."asd");

                            return response()->json("failed"); 

                        }
                        else
                        {
                            // dd($status."asd");
                            if (($status))
                            {
                                return response()->json("failed");

                            }
                            else
                            {
                                
                                    if (($tanggalhariini == $tanggalawal)) {
                                        $cek = att::where('tanggal_att', '=', $tanggalhariini)
                                            ->where('pegawai_id','=',$id)
                                            ->where('jadwalkerja_id', '=', $jadwalkerjaid)
                                            ->count();
                                        
                                    }
                                        $table = new rulejadwalpegawai();
                                        $table->pegawai_id = $id;
                                        $table->tanggal_awalrule = $tanggalawal;
                                        $table->tanggal_akhirrule = $tanggalawal;
                                        $table->jadwalkerja_id = $jadwalkerjaid;
                                        if ($table->save())
                                        {
                                            $sifatjadwalkerja=jadwalkerja::where('id','=',$jadwalkerjaid)->first();

                                            $details['tanggalawal']=$tanggalawal;
                                            $details['tanggalakhir']=$tanggalakhir;
                                            $details['pegawai_id']=$id;
                                            $details['jadwalkerja_id']=$jadwalkerjaid;
                                            $details['instansi_id']=Auth::user()->instansi_id;
                                            dispatch(new GenerateAttendance($details));
                                        }
                            }
                                
                            
                        }
                        return response()->json("success");                        
                    }
                }    
                
            }
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
        
        $jadwals=rulejammasuk::leftJoin('jadwalkerjas','rulejammasuks.jadwalkerja_id','=','jadwalkerjas.id')
        ->where('jadwalkerjas.instansi_id','=',Auth::user()->instansi_id)
        ->where('jadwalkerjas.sifat','!=','FD')
        ->where('jadwalkerjas.instansi_id','!=','1')
        ->select('jadwalkerjas.*','rulejammasuks.jamsebelum_masukkerja','rulejammasuks.jamsebelum_pulangkerja')
        ->paginate(3);
        // dd($jadwals);

        $idpeg=decrypt($id);
        $pegawai=pegawai::where('id','=',$idpeg)
                ->first();
    
        // dd($pegawai);

        return view('jadwalkerjapegawai.rulejadwalkerjapegawaiharian',['jadwals'=>$jadwals,'idemploye'=>$id,'pegawai'=>$pegawai]);
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
