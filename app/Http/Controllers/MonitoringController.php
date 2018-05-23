<?php

namespace App\Http\Controllers;

use App\pegawai;
use App\att;
use App\instansi;
use App\masterbulanan;
use App\jadwalkerja;
use App\jenisabsen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class MonitoringController extends Controller
{
    //
    public function monitoringinstansiminggu(Request $request)
    {
        // dd($request->instansi_id[0]);

        if ($request->tanggal==""){
            $tanggal=date("Y-m");
            $pecah=explode("-",$tanggal);
            $bulan=$pecah[1];
            $tahun=$pecah[0];

            $request->tanggal=$tanggal;
        }
        else
        {
            $tanggal=$request->tanggal;
            $pecah=explode("-",$tanggal);
            $bulan=$pecah[1];
            $tahun=$pecah[0];
        }

        if ($request->jenis_absen==""){
            // dd($request->jenis_absen);
            $request->jenis_absen=1;
        }

        if ($request->jenis_absen==1){
            $order='hadir';
        }
        elseif ($request->jenis_absen==2){
            $order='tanpa_kabar';
        }
        elseif ($request->jenis_absen==3)
        {
            $order='ijin';
        }
        elseif ($request->jenis_absen==4)
        {
            $order='cuti';
        }
        elseif ($request->jenis_absen==5)
        {
            $order="sakit";
        }
        elseif ($request->jenis_absen==6)
        {
            $order='tugas_belajar';
        }
        elseif ($request->jenis_absen==7)
        {
            $order='tugas_luar';
        }
        elseif ($request->jenis_absen==8)
        {
            $order='rapatundangan';
        }
        elseif ($request->jenis_absen==10)
        {
            $order='ijinterlambat';
        }
        elseif ($request->jenis_absen==12)
        {
            $order='ijinpulangcepat';
        }
        elseif ($request->jenis_absen==20)
        {
            $order='apelbulanan';
        }

        if ($request->metode==""){
            $request->metode='desc';
        }
    
        
        if ($request->instansi_id[0]!=""){
            $data=masterbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
                                ->leftJoin('jadwalkerjas','masterbulanans.jadwalkerja_id','=','jadwalkerjas.id')
                                ->leftJoin('instansis','masterbulanans.instansi_id','=','instansis.id')
                                ->select(DB::raw('sum(masterbulanans.hari_kerja) as hari_kerja'),DB::raw('sum(masterbulanans.hadir) as hadir'),DB::raw('sum(masterbulanans.ijin) as ijin'),
                                        DB::raw('sum(masterbulanans.tanpa_kabar) as tanpa_kabar'),
                                        DB::raw('sum(masterbulanans.ijinterlambat) as ijinterlambat'),
                                        DB::raw('sum(masterbulanans.sakit) as sakit'),
                                        DB::raw('sum(masterbulanans.tugas_luar) as tugas_luar'),
                                        DB::raw('sum(masterbulanans.tugas_belajar) as tugas_belajar'),
                                        DB::raw('sum(masterbulanans.terlambat) as terlambat'),
                                        DB::raw('sum(masterbulanans.rapatundangan) as rapatundangan'),
                                        DB::raw('sum(masterbulanans.pulang_cepat) as pulang_cepat'),
                                        DB::raw('sum(masterbulanans.ijinpulangcepat) as ijinpulangcepat'),
                                        DB::raw('sum(masterbulanans.apelbulanan) as apelbulanan')
                                )
                                // ->distinct()
                                ->groupBy('masterbulanans.instansi_id')
                                ->whereMonth('masterbulanans.periode','=',$bulan)
                                ->whereYear('masterbulanans.periode','=',$tahun)
                                ->where('masterbulanans.instansi_id','=',$request->instansi_id[0])
                                ->orderBy($order,$request->metode)
                                ->whereNotNull('masterbulanans.instansi_id')
                                ->paginate(50);
            // $data= DB::select('SELECT namaInstansi,@instansi:=id,id,
            //                         (SELECT SUM(hari_kerja)
            //                             FROM masterbulanans
            //                             WHERE instansi_id=@instansi AND YEAR(periode)='.$tahun.' AND MONTH(periode)='.$bulan.') AS hari_kerja,
            //                         (SELECT SUM(hadir)
            //                             FROM masterbulanans
            //                             WHERE instansi_id=@instansi AND YEAR(periode)='.$tahun.' AND MONTH(periode)='.$bulan.') AS hadir,
            //                         (SELECT SUM(tanpa_kabar)
            //                             FROM masterbulanans
            //                             WHERE instansi_id=@instansi AND YEAR(periode)='.$tahun.' AND MONTH(periode)='.$bulan.') AS tanpa_kabar,
            //                         (SELECT SUM(ijin)
            //                             FROM masterbulanans
            //                             WHERE instansi_id=@instansi AND YEAR(periode)='.$tahun.' AND MONTH(periode)='.$bulan.') AS ijin,
            //                         (SELECT SUM(ijinterlambat)
            //                             FROM masterbulanans
            //                             WHERE instansi_id=@instansi AND YEAR(periode)='.$tahun.' AND MONTH(periode)='.$bulan.') AS ijinterlambat,
            //                         (SELECT SUM(sakit)
            //                             FROM masterbulanans
            //                             WHERE instansi_id=@instansi AND YEAR(periode)='.$tahun.' AND MONTH(periode)='.$bulan.') AS sakit,
            //                         (SELECT SUM(tugas_luar)
            //                             FROM masterbulanans
            //                             WHERE instansi_id=@instansi AND YEAR(periode)='.$tahun.' AND MONTH(periode)='.$bulan.') AS tugas_luar,
            //                         (SELECT SUM(tugas_belajar)
            //                             FROM masterbulanans
            //                             WHERE instansi_id=@instansi AND YEAR(periode)='.$tahun.' AND MONTH(periode)='.$bulan.') AS tugas_belajar,
            //                         (SELECT SUM(terlambat)
            //                             FROM masterbulanans
            //                             WHERE instansi_id=@instansi AND YEAR(periode)='.$tahun.' AND MONTH(periode)='.$bulan.') AS terlambat,
            //                         (SELECT SUM(rapatundangan)
            //                             FROM masterbulanans
            //                             WHERE instansi_id=@instansi AND YEAR(periode)='.$tahun.' AND MONTH(periode)='.$bulan.') AS rapatundangan,
            //                         (SELECT SUM(pulang_cepat)
            //                             FROM masterbulanans
            //                             WHERE instansi_id=@instansi AND YEAR(periode)='.$tahun.' AND MONTH(periode)='.$bulan.') AS pulang_cepat,           
            //                         (SELECT SUM(ijinpulangcepat)
            //                             FROM masterbulanans
            //                             WHERE instansi_id=@instansi AND YEAR(periode)='.$tahun.' AND MONTH(periode)='.$bulan.') AS ijinpulangcepat,
            //                         (SELECT SUM(apelbulanan)
            //                             FROM masterbulanans
            //                             WHERE instansi_id=@instansi AND YEAR(periode)='.$tahun.' AND MONTH(periode)='.$bulan.') AS apelbulanan          
            //                     FROM instansis                                                       
            //                     WHERE id='.$request->instansi_id[0].' '.$order.' '.$request->metode);

            

            $namainstansidata=instansi::where('id','=',$request->instansi_id)
            ->first();
            $namainstansi=$namainstansidata->namaInstansi;
        }
        else
        {
            $data=masterbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
                                ->leftJoin('jadwalkerjas','masterbulanans.jadwalkerja_id','=','jadwalkerjas.id')
                                ->leftJoin('instansis','masterbulanans.instansi_id','=','instansis.id')
                                ->select(DB::raw('sum(masterbulanans.hari_kerja) as hari_kerja'),
                                        DB::raw('sum(masterbulanans.hadir) as hadir'),
                                        DB::raw('sum(masterbulanans.ijin) as ijin'),
                                        DB::raw('sum(masterbulanans.tanpa_kabar) as tanpa_kabar'),
                                        DB::raw('sum(masterbulanans.ijinterlambat) as ijinterlambat'),
                                        DB::raw('sum(masterbulanans.sakit) as sakit'),
                                        DB::raw('sum(masterbulanans.tugas_luar) as tugas_luar'),
                                        DB::raw('sum(masterbulanans.tugas_belajar) as tugas_belajar'),
                                        DB::raw('sum(masterbulanans.terlambat) as terlambat'),
                                        DB::raw('sum(masterbulanans.rapatundangan) as rapatundangan'),
                                        DB::raw('sum(masterbulanans.pulang_cepat) as pulang_cepat'),
                                        DB::raw('sum(masterbulanans.ijinpulangcepat) as ijinpulangcepat'),
                                        DB::raw('sum(masterbulanans.apelbulanan) as apelbulanan'),
                                        'instansis.namaInstansi',
                                        'masterbulanans.instansi_id'
                                )
                                // ->distinct()
                                ->groupBy('masterbulanans.instansi_id')
                                ->whereMonth('masterbulanans.periode','=',$bulan)
                                ->whereYear('masterbulanans.periode','=',$tahun)
                                ->orderBy($order,$request->metode)
                                ->whereNotNull('masterbulanans.instansi_id')
                                ->paginate(50); 

        }                        

        
        // $article=collect($data);
        // // dd($article);
        // // pagination
        // $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // // dd($currentPage);
        // $perPage = 30;
        // $currentResults = $article->slice(($currentPage - 1) * $perPage, $perPage)->all();
        // $results = new LengthAwarePaginator($currentResults, $article->count(), $perPage);
        // // dd($currentResults);
        // $results->withPath('/monitoring/instansi/mingguan');
        $jenisabsens=jenisabsen::where('id','!=','9')
                        ->where('id','!=','11')
                        ->where('id','!=','13')
                        ->get();
        // // dd($request->metode);        
       
        return view('monitoring.mingguan.rekapmingguinstansi',['datas'=>$data,'jenis_absen2'=>($request->jenis_absen),'instansi_id'=>$request->instansi_id[0],'metode'=>$request->metode,'date'=>$request->tanggal,'tanggal'=>$request->tanggal,'jenis_absens'=>$jenisabsens]);
    }

    public function monitoringinstansiminggupersonal(Request $request,$id,$tanggal){
        $id=decrypt($id);
        // dd($id);
        $tanggal=decrypt($tanggal);
        // dd($tanggal);
        // dd($request->tanggal);
        $hitungtanggal=strlen($request->tanggal);
        if ($hitungtanggal > 10)
        {
            $request->tanggal=decrypt($request->tanggal);
        }
        

        if ($request->tanggal==""){
            $tanggal=date("Y-m");
            $pecah=explode("-",$tanggal);
            $bulan=$pecah[1];
            $tahun=$pecah[0];
            $request->tanggal=$tanggal;
        }
        else
        {
            $tanggal=$request->tanggal;
            $pecah=explode("-",$tanggal);
            $bulan=$pecah[1];
            $tahun=$pecah[0];
        }
        
        // dd($id);

        if ($request->jenis_absen==""){
            // dd($request->jenis_absen);
            $request->jenis_absen=1;
        }

        if ($request->jenis_absen==1){
            $order='hadir';
        }
        elseif ($request->jenis_absen==2){
            $order='tanpa_kabar';
        }
        elseif ($request->jenis_absen==3)
        {
            $order='ijin';
        }
        elseif ($request->jenis_absen==4)
        {
            $order='cuti';
        }
        elseif ($request->jenis_absen==5)
        {
            $order="sakit";
        }
        elseif ($request->jenis_absen==6)
        {
            $order='tugas_belajar';
        }
        elseif ($request->jenis_absen==7)
        {
            $order='tugas_luar';
        }
        elseif ($request->jenis_absen==8)
        {
            $order='rapatundangan';
        }
        elseif ($request->jenis_absen==10)
        {
            $order='ijinterlambat';
        }
        elseif ($request->jenis_absen==12)
        {
            $order='ijinpulangcepat';
        }
        elseif ($request->jenis_absen==20)
        {
            $order='apelbulanan';
        }

        if ($request->metode==""){
            $request->metode='desc';
        }

        $data=masterbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
            ->leftJoin('jadwalkerjas','masterbulanans.jadwalkerja_id','=','jadwalkerjas.id')
            ->leftJoin('instansis','masterbulanans.instansi_id','=','instansis.id')
            ->select(DB::raw('sum(masterbulanans.hari_kerja) as hari_kerja'),DB::raw('sum(masterbulanans.hadir) as hadir'),DB::raw('sum(masterbulanans.ijin) as ijin'),
                    DB::raw('sum(masterbulanans.tanpa_kabar) as tanpa_kabar'),
                    DB::raw('sum(masterbulanans.ijinterlambat) as ijinterlambat'),
                    DB::raw('sum(masterbulanans.sakit) as sakit'),
                    DB::raw('sum(masterbulanans.tugas_luar) as tugas_luar'),
                    DB::raw('sum(masterbulanans.tugas_belajar) as tugas_belajar'),
                    DB::raw('sum(masterbulanans.terlambat) as terlambat'),
                    DB::raw('sum(masterbulanans.rapatundangan) as rapatundangan'),
                    DB::raw('sum(masterbulanans.pulang_cepat) as pulang_cepat'),
                    DB::raw('sum(masterbulanans.ijinpulangcepat) as ijinpulangcepat'),
                    DB::raw('sum(masterbulanans.apelbulanan) as apelbulanan'),
                    DB::raw('SEC_TO_TIME( SUM(time_to_sec(total_akumulasi))) as total_akumulasi'),
                    DB::raw('SEC_TO_TIME( SUM(time_to_sec(total_terlambat))) as total_terlambat'),
                    'instansis.namaInstansi',
                    'masterbulanans.instansi_id',
                    'masterbulanans.pegawai_id',
                    'masterbulanans.periode',
                    'pegawais.nip',
                    'pegawais.nama'
            )
            // ->distinct()
            ->groupBy('masterbulanans.pegawai_id')
            ->whereMonth('masterbulanans.periode','=',$bulan)
            ->whereYear('masterbulanans.periode','=',$tahun)
            ->where('masterbulanans.instansi_id','=',$id)
            ->orderBy($order,$request->metode)
            ->whereNotNull('masterbulanans.instansi_id')
            ->paginate(50);

            // dd($id);

        $jenisabsens=jenisabsen::where('id','!=','9')
                    ->where('id','!=','11')
                    ->where('id','!=','13')
                    ->get();
        $instansi=instansi::where('id','=',$id)
                        ->first();
                        
        return view('monitoring.mingguan.rekapmingguinstansidetail',['datas'=>$data,'jenis_absen2'=>($request->jenis_absen),'instansi_id'=>$instansi->id,'namainstansi'=>$instansi->namaInstansi,'metode'=>$request->metode,'date'=>$request->tanggal,'tanggal'=>$request->tanggal,'id'=>$id,'jenis_absens'=>$jenisabsens]);
    }


    public function monitoringinstansiminggupersonaldetail(Request $request,$id,$tanggal,$instansi_id)
    {
        $id=decrypt($id);
        // dd($id);
        $tanggal2=decrypt($tanggal);
        // dd($tanggal2);
        // dd($request->tanggal);
        
        $pecah=explode("-",$tanggal2);
        $bulan=$pecah[1];
        $tahun=$pecah[0];


        
        // dd($id);

        if ($request->jenis_absen==""){
            // dd($request->jenis_absen);
            $request->jenis_absen=1;
        }

        if ($request->jenis_absen==1){
            $order='hadir';
        }
        elseif ($request->jenis_absen==2){
            $order='tanpa_kabar';
        }
        elseif ($request->jenis_absen==3)
        {
            $order='ijin';
        }
        elseif ($request->jenis_absen==4)
        {
            $order='cuti';
        }
        elseif ($request->jenis_absen==5)
        {
            $order="sakit";
        }
        elseif ($request->jenis_absen==6)
        {
            $order='tugas_belajar';
        }
        elseif ($request->jenis_absen==7)
        {
            $order='tugas_luar';
        }
        elseif ($request->jenis_absen==8)
        {
            $order='rapatundangan';
        }
        elseif ($request->jenis_absen==10)
        {
            $order='ijinterlambat';
        }
        elseif ($request->jenis_absen==12)
        {
            $order='ijinpulangcepat';
        }
        elseif ($request->jenis_absen==20)
        {
            $order='apelbulanan';
        }

        if ($request->metode==""){
            $request->metode='desc';
        }

        $data=masterbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
            ->leftJoin('jadwalkerjas','masterbulanans.jadwalkerja_id','=','jadwalkerjas.id')
            ->leftJoin('instansis','masterbulanans.instansi_id','=','instansis.id')
            ->select(DB::raw('sum(masterbulanans.hari_kerja) as hari_kerja'),DB::raw('sum(masterbulanans.hadir) as hadir'),DB::raw('sum(masterbulanans.ijin) as ijin'),
                    DB::raw('sum(masterbulanans.tanpa_kabar) as tanpa_kabar'),
                    DB::raw('sum(masterbulanans.ijinterlambat) as ijinterlambat'),
                    DB::raw('sum(masterbulanans.sakit) as sakit'),
                    DB::raw('sum(masterbulanans.tugas_luar) as tugas_luar'),
                    DB::raw('sum(masterbulanans.tugas_belajar) as tugas_belajar'),
                    DB::raw('sum(masterbulanans.terlambat) as terlambat'),
                    DB::raw('sum(masterbulanans.rapatundangan) as rapatundangan'),
                    DB::raw('sum(masterbulanans.pulang_cepat) as pulang_cepat'),
                    DB::raw('sum(masterbulanans.ijinpulangcepat) as ijinpulangcepat'),
                    DB::raw('sum(masterbulanans.apelbulanan) as apelbulanan'),
                    DB::raw('SEC_TO_TIME( SUM(time_to_sec(total_akumulasi))) as total_akumulasi'),
                    DB::raw('SEC_TO_TIME( SUM(time_to_sec(total_terlambat))) as total_terlambat'),
                    'instansis.namaInstansi',
                    'masterbulanans.instansi_id',
                    'masterbulanans.pegawai_id',
                    'masterbulanans.periode',
                    'pegawais.nip',
                    'pegawais.nama'
            )
            ->whereMonth('masterbulanans.periode','=',$bulan)
            ->whereYear('masterbulanans.periode','=',$tahun)
            ->where('masterbulanans.pegawai_id','=',$id)
            ->orderBy($order,$request->metode)
            ->groupBy('masterbulanans.periode')            
            ->whereNotNull('masterbulanans.instansi_id')
            ->paginate(50);

            // dd($id);

        $jenisabsens=jenisabsen::where('id','!=','9')
                    ->where('id','!=','11')
                    ->where('id','!=','13')
                    ->get();
        $instansi_id=decrypt($instansi_id);
        $instansi=instansi::where('id','=',$instansi_id)
                        ->first();
        $pegawai=pegawai::where('id','=',$id)
                ->first();
        return view('monitoring.mingguan.rekapmingguinstansipersonaldetail',['datas'=>$data,'jenis_absen2'=>($request->jenis_absen),'instansi_id'=>$instansi_id,'namainstansi'=>$instansi->namaInstansi,'idpegawai'=>$pegawai->id,'nip'=>$pegawai->nip,'namapegawai'=>$pegawai->nama,'metode'=>$request->metode,'date'=>$tanggal2,'tanggal'=>$tanggal2,'id'=>$id,'jenis_absens'=>$jenisabsens]); 
    }

    public function monitoringinstansiminggupersonaldetailatt(Request $request,$id,$tanggal,$instansi_id){
        $id=decrypt($id);
        $tanggal=decrypt($tanggal);
        // $instansi_id=decrypt($instansi_id);
        // dd($instansi_id);
        $instansi_id=$instansi_id;
        $tanggalawal=date('Y-m-d',strtotime('-7 days',strtotime($tanggal)));
        $tanggalakhir=date('Y-m-d',strtotime('-1 days',strtotime($tanggal)));
        $data=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->where('atts.tanggal_att','>',$tanggalawal)
        ->where('atts.tanggal_att','<',$tanggalakhir)
        ->where('pegawais.id','=',$id)
        // ->whereMonth('atts.tanggal_att','=',$bulan)
        // ->whereDay('atts.tanggal_att','=',$tanggal)
        // ->whereYear('atts.tanggal_att','=',$tahun)
        ->select('atts.*','jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('pegawais.nama','desc')
        ->paginate(30);
        $jenisabsens=jenisabsen::where('id','!=','9')
                    ->where('id','!=','11')
                    ->where('id','!=','13')
                    ->get();
        $instansi=instansi::where('id','=',$instansi_id)
                        ->first();
        $pegawai=pegawai::where('id','=',$id)
                ->first();
        return view('monitoring.mingguan.rekapmingguinstansipersonaldetailatt   ',['datas'=>$data,'jenis_absen2'=>($request->jenis_absen),'instansi_id'=>$instansi_id,'namainstansi'=>$instansi->namaInstansi,'idpegawai'=>$pegawai->id,'nip'=>$pegawai->nip,'namapegawai'=>$pegawai->nama,'metode'=>$request->metode,'date'=>$tanggal,'tanggal'=>$tanggal,'id'=>$id,'jenis_absens'=>$jenisabsens]); 
    }
}   
