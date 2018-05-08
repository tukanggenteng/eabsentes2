<?php

namespace App\Http\Controllers;

use App\att;
use App\ruanganuser;
use App\jadwalkerja;
use App\pegawai;
use App\rulejadwalpegawai;
use App\perawatruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $rulejadwalpeg=rulejadwalpegawai::where('id','=',$id)
                        ->first();
        if ($rulejadwalpeg->delete())
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
                // dd($id);
                // dd($request->awalrule);
                $tanggalhariini=date("Y-m-d");
                
                if (($tanggalhariini == $request->awalrule)) {
                    $cek = att::where('tanggal_att', '=', $tanggalhariini)
                        ->where('pegawai_id','=',$id)
                        ->where('jadwalkerja_id', '=', decrypt($request->jadwalid))
                        ->count();
                    if ($cek == 0) {
                        $table = new att();
                        $table->pegawai_id = $id;
                        $table->jadwalkerja_id = decrypt($request->jadwalid);
                        $table->tanggal_att = $tanggalhariini;
                        $table->terlambat="00:00:00";
                        $table->akumulasi_sehari="00:00:00";
                        $table->jenisabsen_id = '2';
                        $table->save();
                    }
                }

                $table = new rulejadwalpegawai();
                $table->pegawai_id = $id;
                $table->tanggal_awalrule = $request->awalrule;
                $table->tanggal_akhirrule = $request->awalrule;
                $table->jadwalkerja_id = decrypt($request->jadwalid);
                // $table->save();

                if ($table->save())
                {
                    return response()->json("success");
                }
                else
                {
                    return response()->json("failed");
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
        //
        $jadwals=jadwalkerja::where('instansi_id','=',Auth::user()->instansi_id)
                ->where('sifat','!=','FD')
                ->orWhere('id','=','1')
                // ->whereNotIn('sifat',['FD'])
                ->get();
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
