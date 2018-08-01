<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\att;
use App\finalrekapbulanan;
use App\masterbulanan;
use Illuminate\Support\Facades\DB;
use App\pegawai;
use Illuminate\Support\Facades\Auth;
use PDF;
use Excel;

class PDFController extends Controller
{
    //
    public function index(Request $request){

      if (isset($request->nip) && isset($request->tanggal))
      {
        // $tanggal=explode('-',$request->tanggal);
        $tanggal=$request->tanggal;
        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        // ->whereMonth('atts.tanggal_att','=',$tanggal[0])
        // ->whereYear('atts.tanggal_att','=',$tanggal[1])
        ->where('atts.tanggal_att','=',$tanggal)
        ->where('pegawais.nip','=',$request->nip)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('atts.*','jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->paginate(30);

        return view('laporan.laporanharian',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);

      }
      elseif (!isset($request->nip) && isset($request->tanggal))
      {
        // $tanggal=explode('-',$request->tanggal);
        $tanggal=$request->tanggal;        
        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->where('atts.tanggal_att','=',$tanggal)
        // ->whereMonth('atts.tanggal_att','=',$tanggal[0])
        // ->whereYear('atts.tanggal_att','=',$tanggal[1])
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('atts.*','jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->paginate(30);

        return view('laporan.laporanharian',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (isset($request->nip) && !isset($request->tanggal))
      {
        // $tanggal=explode('-',$request->tanggal);
        $tanggal=$request->tanggal;        
        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->where('pegawais.nip','=',$request->nip)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('atts.*','jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->paginate(30);
        $request->tanggal=null;
        return view('laporan.laporanharian',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (!isset($request->nip) && !isset($request->tanggal))
      {
        
        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('atts.*','jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->paginate(30);
        $nip=null;
        $tanggal=null;
        // dd("as");
        return view('laporan.laporanharian',['atts'=>$atts,'nip'=>$nip,'tanggal'=>$tanggal]);

      }

    }

    public function pdfharian(){

        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('atts.tanggal_att','pegawais.nip','pegawais.nama','atts.jam_masuk','atts.terlambat','instansismasuk.namaInstansi as namainstansimasuk',
                    'atts.jam_keluar','instansiskeluar.namaInstansi as namainstansikeluar','atts.akumulasi_sehari',
                    'jenisabsens.jenis_absen','jadwalkerjas.jenis_jadwal')
        ->orderBy('atts.tanggal_att','desc')
        ->limit(5000)
        ->get();

        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $pdf=PDF::loadView('pdf.pdfharian',['atts'=>$atts,'instansi'=>$instansi]);
        return Excel::create('laporanharian',function($excel) use ($atts){
                $excel->sheet('laporan',function($sheet) use ($atts){
                       
                        $sheet->protect('b1k1n4pl1k451');

                        
                        
                        $sheet->fromArray($atts);
                        $sheet->cell('A1',function ($cell){$cell->setValue('Tanggal'); });
                        $sheet->cell('B1',function ($cell){$cell->setValue('NIP'); });
                        $sheet->cell('C1',function ($cell){$cell->setValue('Nama'); });
                        $sheet->cell('D1',function ($cell){$cell->setValue('Jam Masuk'); });
                        $sheet->cell('E1',function ($cell){$cell->setValue('Terlambat'); });
                        $sheet->cell('F1',function ($cell){$cell->setValue('Masuk Instansi'); });
                        $sheet->cell('G1',function ($cell){$cell->setValue('Jam Keluar'); });
                        $sheet->cell('H1',function ($cell){$cell->setValue('Keluar Instansi'); });
                        $sheet->cell('I1',function ($cell){$cell->setValue('Akumulasi'); });
                        $sheet->cell('J1',function ($cell){$cell->setValue('Jenis Absen'); });
                        $sheet->cell('K1',function ($cell){$cell->setValue('Jenis Jadwal'); });
                    });
            })->download('xls');
        // return $pdf->setPaper('F4', 'landscape')->download('laporanharian.pdf');
        // return $pdf->setPaper(array(0, 0, 595.35, 935.55), 'landscape')->stream();
    }

    public function pdfharianfull($id,$id2){

        $id=decrypt($id);
        $id2=decrypt($id2);

        $tanggal=explode('-',$id);

        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        // ->whereMonth('atts.tanggal_att','=',$tanggal[0])
        // ->whereYear('atts.tanggal_att','=',$tanggal[1])
        ->where('atts.tanggal_att','=',$id)        
        ->where('pegawais.nip','=',$id2)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('atts.tanggal_att','pegawais.nip','pegawais.nama','atts.jam_masuk','atts.terlambat','instansismasuk.namaInstansi as namainstansimasuk',
                    'atts.jam_keluar','instansiskeluar.namaInstansi as namainstansikeluar','atts.akumulasi_sehari',
                    'jenisabsens.jenis_absen','jadwalkerjas.jenis_jadwal')
        ->orderBy('atts.tanggal_att','desc')
        ->limit(5000)
        ->get();

        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $pdf=PDF::loadView('pdf.pdfharian',['atts'=>$atts,'instansi'=>$instansi]);
        return Excel::create('laporanharian',function($excel) use ($atts){
                $excel->sheet('laporan',function($sheet) use ($atts){
                       
                        $sheet->protect('b1k1n4pl1k451');

                        
                        
                        $sheet->fromArray($atts);
                        $sheet->cell('A1',function ($cell){$cell->setValue('Tanggal'); });
                        $sheet->cell('B1',function ($cell){$cell->setValue('NIP'); });
                        $sheet->cell('C1',function ($cell){$cell->setValue('Nama'); });
                        $sheet->cell('D1',function ($cell){$cell->setValue('Jam Masuk'); });
                        $sheet->cell('E1',function ($cell){$cell->setValue('Terlambat'); });
                        $sheet->cell('F1',function ($cell){$cell->setValue('Masuk Instansi'); });
                        $sheet->cell('G1',function ($cell){$cell->setValue('Jam Keluar'); });
                        $sheet->cell('H1',function ($cell){$cell->setValue('Keluar Instansi'); });
                        $sheet->cell('I1',function ($cell){$cell->setValue('Akumulasi'); });
                        $sheet->cell('J1',function ($cell){$cell->setValue('Jenis Absen'); });
                        $sheet->cell('K1',function ($cell){$cell->setValue('Jenis Jadwal'); });
                    });
            })->download('xls');
    }

    public function pdfhariantanggal($id){
        $id=decrypt($id);
        $tanggal=explode('-',$id);

        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        // ->whereMonth('atts.tanggal_att','=',$tanggal[0])
        // ->whereYear('atts.tanggal_att','=',$tanggal[1])
        ->where('atts.tanggal_att','=',$id)        
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('atts.tanggal_att','pegawais.nip','pegawais.nama','atts.jam_masuk','atts.terlambat','instansismasuk.namaInstansi as namainstansimasuk',
                    'atts.jam_keluar','instansiskeluar.namaInstansi as namainstansikeluar','atts.akumulasi_sehari',
                    'jenisabsens.jenis_absen','jadwalkerjas.jenis_jadwal')
        ->orderBy('atts.tanggal_att','desc')
        ->limit(5000)
        ->get();

        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        //$pdf=PDF::loadView('pdf.pdfharian',['atts'=>$atts,'instansi'=>$instansi]);
        return Excel::create('laporanharian',function($excel) use ($atts){
                $excel->sheet('laporan',function($sheet) use ($atts){
                       
                        $sheet->protect('b1k1n4pl1k451');

                        
                        
                        $sheet->fromArray($atts);
                        $sheet->cell('A1',function ($cell){$cell->setValue('Tanggal'); });
                        $sheet->cell('B1',function ($cell){$cell->setValue('NIP'); });
                        $sheet->cell('C1',function ($cell){$cell->setValue('Nama'); });
                        $sheet->cell('D1',function ($cell){$cell->setValue('Jam Masuk'); });
                        $sheet->cell('E1',function ($cell){$cell->setValue('Terlambat'); });
                        $sheet->cell('F1',function ($cell){$cell->setValue('Masuk Instansi'); });
                        $sheet->cell('G1',function ($cell){$cell->setValue('Jam Keluar'); });
                        $sheet->cell('H1',function ($cell){$cell->setValue('Keluar Instansi'); });
                        $sheet->cell('I1',function ($cell){$cell->setValue('Akumulasi'); });
                        $sheet->cell('J1',function ($cell){$cell->setValue('Jenis Absen'); });
                        $sheet->cell('K1',function ($cell){$cell->setValue('Jenis Jadwal'); });
                    });
            })->download('xls');
    }

    public function pdfhariannip($id){

        $id=decrypt($id);

        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->where('pegawais.nip','=',$id)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->get();


        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $pdf=PDF::loadView('pdf.pdfharian',['atts'=>$atts,'instansi'=>$instansi]);
        // return $pdf->setPaper('a4', 'landscape')->download('laporanharian.pdf');
        // return $pdf->setPaper('F4', 'landscape')->stream();
        return $pdf->setPaper(array(0, 0, 595.35, 935.55), 'landscape')->stream();
        
    }


    public function pdfbulanindex(Request $request){

      if (isset($request->nip) && isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);

                $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                        ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                        ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                        ->select(
                                'pegawais.id',
                                'pegawais.nip',
                                'pegawais.nama',
                                DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                                DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),                        
                                DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                                DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                                DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                                DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                                DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                                DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                                DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                                DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                                DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                                DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                                DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                                DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                                DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                                DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                                DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                                'instansis.namaInstansi',
                                'pegawais.instansi_id'
                        )
                        ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                        ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                
                        ->whereMonth('atts.tanggal_att','=',$tanggal[0])
                        ->where('pegawais.nip','=',$request->nip)
                        ->whereYear('atts.tanggal_att','=',$tanggal[1])
                        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                        ->paginate(50);

        

        return view('laporan.laporanbulan',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);

      }
      elseif (!isset($request->nip) && isset($request->tanggal))
      {
                $tanggal=explode('-',$request->tanggal);

                $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        'pegawais.id',
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),                        DB::raw('count(*) hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                        'instansis.namaInstansi',
                        'pegawais.instansi_id'
                )
                ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                            
                ->whereMonth('atts.tanggal_att','=',$tanggal[0])
                ->whereYear('atts.tanggal_att','=',$tanggal[1])
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->paginate(50);
                
                // $atts=finalrekapbulanan::leftJoin('pegawais','finalrekapbulanans.pegawai_id','=','pegawais.id')
                // ->leftJoin('instansis','instansis.id','=','finalrekapbulanans.pegawai_id')
                // ->whereMonth('finalrekapbulanans.periode','=',$tanggal[0])
                // ->whereYear('finalrekapbulanans.periode','=',$tanggal[1])
                // ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                // ->select('finalrekapbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
                // ->orderBy('finalrekapbulanans.periode','desc')
                // ->paginate(30);
        $request->nip=null;
        return view('laporan.laporanbulan',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (isset($request->nip) && !isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);
        // dd($request->nip);
        if ($request->tanggal=="")
        {
                $tanggal=date('Y-m');
                $tanggal=explode('-',$tanggal);
                $tanggal=null;
        }

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        'pegawais.id',
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                        'instansis.namaInstansi',
                        'pegawais.instansi_id'
                )
                ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                
                ->where('pegawais.nip','=',$request->nip)
                // ->whereMonth('atts.tanggal_att','=',$tanggal[0])
                // ->whereYear('atts.tanggal_att','=',$tanggal[1])
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->paginate(50);
        
        // dd($atts);
        
        return view('laporan.laporanbulan',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (!isset($request->nip) && !isset($request->tanggal))
      {
        $tanggal=date('Y-m');
        $tanggal=explode('-',$tanggal);
        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')
                )
                ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                
                ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                // ->whereMonth('atts.tanggal_att','=',$tanggal[0])
                // ->whereYear('atts.tanggal_att','=',$tanggal[1])
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->paginate(50);

        $nip=null;
        $tanggal=null;
        return view('laporan.laporanbulan',['atts'=>$atts,'nip'=>$nip,'tanggal'=>$tanggal]);

      }
    }

    public function pdfbulan(){

     
        $tanggal=date('Y-m');
        $tanggal=explode('-',$tanggal);
        
        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')
                )
                ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                
                ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                // ->whereMonth('atts.tanggal_att','=',$tanggal[0])
                // ->whereYear('atts.tanggal_att','=',$tanggal[1])
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->get();



        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        return Excel::create('laporanbulanan',function($excel) use ($atts){
                $excel->sheet('laporan',function($sheet) use ($atts){
                       
                        $sheet->protect('b1k1n4pl1k451');
                        
                        $sheet->fromArray($atts);
                        $sheet->cell('A1',function ($cell){$cell->setValue('NIP'); });
                        $sheet->cell('B1',function ($cell){$cell->setValue('Nama'); });
                        $sheet->cell('C1',function ($cell){$cell->setValue('Periode'); });
                        $sheet->cell('D1',function ($cell){$cell->setValue('Hari Kerja'); });
                        $sheet->cell('E1',function ($cell){$cell->setValue('Hadir'); });
                        $sheet->cell('F1',function ($cell){$cell->setValue('Apel'); });
                        $sheet->cell('G1',function ($cell){$cell->setValue('Terlambat'); });
                        $sheet->cell('H1',function ($cell){$cell->setValue('Tanpa Kabar'); });
                        $sheet->cell('I1',function ($cell){$cell->setValue('Izin'); });
                        $sheet->cell('J1',function ($cell){$cell->setValue('Izin Terlambat'); });
                        $sheet->cell('K1',function ($cell){$cell->setValue('Tidak Apel'); });
                        $sheet->cell('L1',function ($cell){$cell->setValue('Sakit'); });
                        $sheet->cell('M1',function ($cell){$cell->setValue('Cuti'); });
                        $sheet->cell('N1',function ($cell){$cell->setValue('Tugas Luar'); });
                        $sheet->cell('O1',function ($cell){$cell->setValue('Tugas Belajar'); });
                        $sheet->cell('P1',function ($cell){$cell->setValue('Ijin Kepentingan Lain'); });
                        $sheet->cell('Q1',function ($cell){$cell->setValue('Pulang Cepat'); });
                        $sheet->cell('R1',function ($cell){$cell->setValue('Ijin Pulang Cepat'); });
                        $sheet->cell('S1',function ($cell){$cell->setValue('Akumulasi Terlambat'); });
                        $sheet->cell('T1',function ($cell){$cell->setValue('Akumulasi Kerja'); });


                    });
            })->download('xls');
    }

    public function pdfbulanfull($id,$id2){

        $id=decrypt($id);
        $id2=decrypt($id2);

        $tanggal=explode('-',$id);

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),                        
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apel_bulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulangcepat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')
                )
                ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                               
                ->whereMonth('atts.tanggal_att','=',$tanggal[0])
                ->whereYear('atts.tanggal_att','=',$tanggal[1])
                ->where('pegawais.nip','=',$id2)
                // ->whereYear('atts.tanggal_att','=',$tanggal[1])
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->get();



        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        return Excel::create('laporanbulanan',function($excel) use ($atts){
                $excel->sheet('laporan',function($sheet) use ($atts){
                       
                        $sheet->protect('b1k1n4pl1k451');
                        
                        $sheet->fromArray($atts);
                        $sheet->cell('A1',function ($cell){$cell->setValue('NIP'); });
                        $sheet->cell('B1',function ($cell){$cell->setValue('Nama'); });
                        $sheet->cell('C1',function ($cell){$cell->setValue('Periode'); });
                        $sheet->cell('D1',function ($cell){$cell->setValue('Hari Kerja'); });
                        $sheet->cell('E1',function ($cell){$cell->setValue('Hadir'); });
                        $sheet->cell('F1',function ($cell){$cell->setValue('Apel'); });
                        $sheet->cell('G1',function ($cell){$cell->setValue('Terlambat'); });
                        $sheet->cell('H1',function ($cell){$cell->setValue('Tanpa Kabar'); });
                        $sheet->cell('I1',function ($cell){$cell->setValue('Izin'); });
                        $sheet->cell('J1',function ($cell){$cell->setValue('Izin Terlambat'); });
                        $sheet->cell('K1',function ($cell){$cell->setValue('Tidak Apel'); });
                        $sheet->cell('L1',function ($cell){$cell->setValue('Sakit'); });
                        $sheet->cell('M1',function ($cell){$cell->setValue('Cuti'); });
                        $sheet->cell('N1',function ($cell){$cell->setValue('Tugas Luar'); });
                        $sheet->cell('O1',function ($cell){$cell->setValue('Tugas Belajar'); });
                        $sheet->cell('P1',function ($cell){$cell->setValue('Ijin Kepentingan Lain'); });
                        $sheet->cell('Q1',function ($cell){$cell->setValue('Pulang Cepat'); });
                        $sheet->cell('R1',function ($cell){$cell->setValue('Ijin Pulang Cepat'); });
                        $sheet->cell('S1',function ($cell){$cell->setValue('Akumulasi Terlambat'); });
                        $sheet->cell('T1',function ($cell){$cell->setValue('Akumulasi Kerja'); });


                    });
            })->download('xls');
    }


    public function pdfbulantanggal($id){
        $id=decrypt($id);
        $tanggal=explode('-',$id);

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),                        DB::raw('count(*) hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1" ,1,null)) as apel_bulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')

                )
                ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                            
                ->whereMonth('atts.tanggal_att','=',$tanggal[0])
                ->whereYear('atts.tanggal_att','=',$tanggal[1])
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->get();



        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        return Excel::create('laporanbulanan',function($excel) use ($atts){
                $excel->sheet('laporan',function($sheet) use ($atts){
                       
                        $sheet->protect('b1k1n4pl1k451');
                        
                        $sheet->fromArray($atts);
                        $sheet->cell('A1',function ($cell){$cell->setValue('NIP'); });
                        $sheet->cell('B1',function ($cell){$cell->setValue('Nama'); });
                        $sheet->cell('C1',function ($cell){$cell->setValue('Periode'); });
                        $sheet->cell('D1',function ($cell){$cell->setValue('Hari Kerja'); });
                        $sheet->cell('E1',function ($cell){$cell->setValue('Hadir'); });
                        $sheet->cell('F1',function ($cell){$cell->setValue('Apel'); });
                        $sheet->cell('G1',function ($cell){$cell->setValue('Terlambat'); });
                        $sheet->cell('H1',function ($cell){$cell->setValue('Tanpa Kabar'); });
                        $sheet->cell('I1',function ($cell){$cell->setValue('Izin'); });
                        $sheet->cell('J1',function ($cell){$cell->setValue('Izin Terlambat'); });
                        $sheet->cell('K1',function ($cell){$cell->setValue('Tidak Apel'); });
                        $sheet->cell('L1',function ($cell){$cell->setValue('Sakit'); });
                        $sheet->cell('M1',function ($cell){$cell->setValue('Cuti'); });
                        $sheet->cell('N1',function ($cell){$cell->setValue('Tugas Luar'); });
                        $sheet->cell('O1',function ($cell){$cell->setValue('Tugas Belajar'); });
                        $sheet->cell('P1',function ($cell){$cell->setValue('Ijin Kepentingan Lain'); });
                        $sheet->cell('Q1',function ($cell){$cell->setValue('Pulang Cepat'); });
                        $sheet->cell('R1',function ($cell){$cell->setValue('Ijin Pulang Cepat'); });
                        $sheet->cell('S1',function ($cell){$cell->setValue('Akumulasi Terlambat'); });
                        $sheet->cell('T1',function ($cell){$cell->setValue('Akumulasi Kerja'); });


                    });
            })->download('xls');
    }

    public function pdfbulannip($id){

        $id=decrypt($id);

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apel_bulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')
                )
                ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))                
                ->where('pegawais.nip','=',$id)
                // ->whereMonth('atts.tanggal_att','=',$tanggal[0])
                // ->whereYear('atts.tanggal_att','=',$tanggal[1])
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->get();



        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        return Excel::create('laporanbulanan',function($excel) use ($atts){
                $excel->sheet('laporan',function($sheet) use ($atts){
                       
                        $sheet->protect('b1k1n4pl1k451');
                        
                        $sheet->fromArray($atts);
                        $sheet->cell('A1',function ($cell){$cell->setValue('NIP'); });
                        $sheet->cell('B1',function ($cell){$cell->setValue('Nama'); });
                        $sheet->cell('C1',function ($cell){$cell->setValue('Periode'); });
                        $sheet->cell('D1',function ($cell){$cell->setValue('Hari Kerja'); });
                        $sheet->cell('E1',function ($cell){$cell->setValue('Hadir'); });
                        $sheet->cell('F1',function ($cell){$cell->setValue('Apel'); });
                        $sheet->cell('G1',function ($cell){$cell->setValue('Terlambat'); });
                        $sheet->cell('H1',function ($cell){$cell->setValue('Tanpa Kabar'); });
                        $sheet->cell('I1',function ($cell){$cell->setValue('Izin'); });
                        $sheet->cell('J1',function ($cell){$cell->setValue('Izin Terlambat'); });
                        $sheet->cell('K1',function ($cell){$cell->setValue('Tidak Apel'); });
                        $sheet->cell('L1',function ($cell){$cell->setValue('Sakit'); });
                        $sheet->cell('M1',function ($cell){$cell->setValue('Cuti'); });
                        $sheet->cell('N1',function ($cell){$cell->setValue('Tugas Luar'); });
                        $sheet->cell('O1',function ($cell){$cell->setValue('Tugas Belajar'); });
                        $sheet->cell('P1',function ($cell){$cell->setValue('Ijin Kepentingan Lain'); });
                        $sheet->cell('Q1',function ($cell){$cell->setValue('Pulang Cepat'); });
                        $sheet->cell('R1',function ($cell){$cell->setValue('Akumulasi Terlambat'); });
                        $sheet->cell('S1',function ($cell){$cell->setValue('Akumulasi Kerja'); });


                    });
            })->download('xls');
    }

    public function pdfmingguanindex(Request $request){
        // dd("asd");
      if (isset($request->nip) && isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);
        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%d-%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')
                )
                ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))
                ->orderBy('atts.tanggal_att','DESC')
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->where('atts.tanggal_att','=',$request->tanggal)
                ->where('pegawais.nip','=',$request->nip)
                ->paginate(50);

        
        
        // dd("dsa");
        return view('laporan.laporanminggu',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);

      }
      elseif (!isset($request->nip) && isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%d-%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')
                )
                ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))
                ->orderBy('atts.tanggal_att','DESC')
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->where('atts.tanggal_att','=',$request->tanggal)
                ->paginate(50);

        
        // dd("asd");
        $request->nip=null;
        return view('laporan.laporanminggu',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (isset($request->nip) && !isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);
        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%d-%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')
                )
                ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))
                ->orderBy('atts.tanggal_att','DESC')
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->paginate(50);
        
        $tanggal=null;
        // dd("as");
        return view('laporan.laporanminggu',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (!isset($request->nip) && !isset($request->tanggal))
      {
        // dd("asd");
        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%d-%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2"|| (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')
                )
                ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))
                ->orderBy('atts.tanggal_att','DESC')
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->paginate(50);
        $nip=null;
        $tanggal=null;
        // dd("asdvd");
        return view('laporan.laporanminggu',['atts'=>$atts,'nip'=>$nip,'tanggal'=>$tanggal]);
      }
    }

    public function pdfminggu(){

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%d-%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apel_bulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')
                )
                ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))
                ->orderBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))','ASC'))
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->get();



        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        return Excel::create('laporanminggu',function($excel) use ($atts){
                $excel->sheet('laporan',function($sheet) use ($atts){
                       
                        $sheet->protect('b1k1n4pl1k451');
                        
                        $sheet->fromArray($atts);
                        $sheet->cell('A1',function ($cell){$cell->setValue('NIP'); });
                        $sheet->cell('B1',function ($cell){$cell->setValue('Nama'); });
                        $sheet->cell('C1',function ($cell){$cell->setValue('Periode'); });
                        $sheet->cell('D1',function ($cell){$cell->setValue('Hari Kerja'); });
                        $sheet->cell('E1',function ($cell){$cell->setValue('Hadir'); });
                        $sheet->cell('F1',function ($cell){$cell->setValue('Apel'); });
                        $sheet->cell('G1',function ($cell){$cell->setValue('Terlambat'); });
                        $sheet->cell('H1',function ($cell){$cell->setValue('Tanpa Kabar'); });
                        $sheet->cell('I1',function ($cell){$cell->setValue('Izin'); });
                        $sheet->cell('J1',function ($cell){$cell->setValue('Izin Terlambat'); });
                        $sheet->cell('K1',function ($cell){$cell->setValue('Tidak Apel'); });
                        $sheet->cell('L1',function ($cell){$cell->setValue('Sakit'); });
                        $sheet->cell('M1',function ($cell){$cell->setValue('Cuti'); });
                        $sheet->cell('N1',function ($cell){$cell->setValue('Tugas Luar'); });
                        $sheet->cell('O1',function ($cell){$cell->setValue('Tugas Belajar'); });
                        $sheet->cell('P1',function ($cell){$cell->setValue('Ijin Kepentingan Lain'); });
                        $sheet->cell('Q1',function ($cell){$cell->setValue('Pulang Cepat'); });
                        $sheet->cell('R1',function ($cell){$cell->setValue('Akumulasi Terlambat'); });
                        $sheet->cell('S1',function ($cell){$cell->setValue('Akumulasi Kerja'); });


                    });
            })->download('xls');
    }

    public function pdfminggufull($id,$id2){

        $id=decrypt($id);
        $id2=decrypt($id2);

        $tanggal=explode('-',$id);

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%d-%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apel_bulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')
                )
                ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))
                ->orderBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))','ASC'))
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->where('atts.tanggal_att','=',$id)
                ->where('pegawais.nip','=',$id2)
                ->get();

        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        return Excel::create('laporanminggu',function($excel) use ($atts){
                $excel->sheet('laporan',function($sheet) use ($atts){
                       
                        $sheet->protect('b1k1n4pl1k451');
                        
                        $sheet->fromArray($atts);
                        $sheet->cell('A1',function ($cell){$cell->setValue('NIP'); });
                        $sheet->cell('B1',function ($cell){$cell->setValue('Nama'); });
                        $sheet->cell('C1',function ($cell){$cell->setValue('Periode'); });
                        $sheet->cell('D1',function ($cell){$cell->setValue('Hari Kerja'); });
                        $sheet->cell('E1',function ($cell){$cell->setValue('Hadir'); });
                        $sheet->cell('F1',function ($cell){$cell->setValue('Apel'); });
                        $sheet->cell('G1',function ($cell){$cell->setValue('Terlambat'); });
                        $sheet->cell('H1',function ($cell){$cell->setValue('Tanpa Kabar'); });
                        $sheet->cell('I1',function ($cell){$cell->setValue('Izin'); });
                        $sheet->cell('J1',function ($cell){$cell->setValue('Izin Terlambat'); });
                        $sheet->cell('K1',function ($cell){$cell->setValue('Tidak Apel'); });
                        $sheet->cell('L1',function ($cell){$cell->setValue('Sakit'); });
                        $sheet->cell('M1',function ($cell){$cell->setValue('Cuti'); });
                        $sheet->cell('N1',function ($cell){$cell->setValue('Tugas Luar'); });
                        $sheet->cell('O1',function ($cell){$cell->setValue('Tugas Belajar'); });
                        $sheet->cell('P1',function ($cell){$cell->setValue('Ijin Kepentingan Lain'); });
                        $sheet->cell('Q1',function ($cell){$cell->setValue('Pulang Cepat'); });
                        $sheet->cell('R1',function ($cell){$cell->setValue('Akumulasi Terlambat'); });
                        $sheet->cell('S1',function ($cell){$cell->setValue('Akumulasi Kerja'); });


                    });
            })->download('xls');
    }


    public function pdfminggutanggal($id){
        $id=decrypt($id);
        $tanggal=explode('-',$id);

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%d-%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apel_bulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')
                )
                ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))
                ->orderBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))','ASC'))
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->where('atts.tanggal_att','=',$id)
                ->get();

        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        return Excel::create('laporanminggu',function($excel) use ($atts){
                $excel->sheet('laporan',function($sheet) use ($atts){
                       
                        $sheet->protect('b1k1n4pl1k451');
                        
                        $sheet->fromArray($atts);
                        $sheet->cell('A1',function ($cell){$cell->setValue('NIP'); });
                        $sheet->cell('B1',function ($cell){$cell->setValue('Nama'); });
                        $sheet->cell('C1',function ($cell){$cell->setValue('Periode'); });
                        $sheet->cell('D1',function ($cell){$cell->setValue('Hari Kerja'); });
                        $sheet->cell('E1',function ($cell){$cell->setValue('Hadir'); });
                        $sheet->cell('F1',function ($cell){$cell->setValue('Apel'); });
                        $sheet->cell('G1',function ($cell){$cell->setValue('Terlambat'); });
                        $sheet->cell('H1',function ($cell){$cell->setValue('Tanpa Kabar'); });
                        $sheet->cell('I1',function ($cell){$cell->setValue('Izin'); });
                        $sheet->cell('J1',function ($cell){$cell->setValue('Izin Terlambat'); });
                        $sheet->cell('K1',function ($cell){$cell->setValue('Tidak Apel'); });
                        $sheet->cell('L1',function ($cell){$cell->setValue('Sakit'); });
                        $sheet->cell('M1',function ($cell){$cell->setValue('Cuti'); });
                        $sheet->cell('N1',function ($cell){$cell->setValue('Tugas Luar'); });
                        $sheet->cell('O1',function ($cell){$cell->setValue('Tugas Belajar'); });
                        $sheet->cell('P1',function ($cell){$cell->setValue('Ijin Kepentingan Lain'); });
                        $sheet->cell('Q1',function ($cell){$cell->setValue('Pulang Cepat'); });
                        $sheet->cell('R1',function ($cell){$cell->setValue('Akumulasi Terlambat'); });
                        $sheet->cell('S1',function ($cell){$cell->setValue('Akumulasi Kerja'); });


                    });
            })->download('xls');
    }

    public function pdfminggunip($id){

        $id=decrypt($id);

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%d-%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apel_bulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jam_keluar = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')
                )
                ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))
                ->orderBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))','ASC'))
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$id)
                ->get();

        



        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        return Excel::create('laporanminggu',function($excel) use ($atts){
                $excel->sheet('laporan',function($sheet) use ($atts){
                       
                        $sheet->protect('b1k1n4pl1k451');
                        
                        $sheet->fromArray($atts);
                        $sheet->cell('A1',function ($cell){$cell->setValue('NIP'); });
                        $sheet->cell('B1',function ($cell){$cell->setValue('Nama'); });
                        $sheet->cell('C1',function ($cell){$cell->setValue('Periode'); });
                        $sheet->cell('D1',function ($cell){$cell->setValue('Hari Kerja'); });
                        $sheet->cell('E1',function ($cell){$cell->setValue('Hadir'); });
                        $sheet->cell('F1',function ($cell){$cell->setValue('Apel'); });
                        $sheet->cell('G1',function ($cell){$cell->setValue('Terlambat'); });
                        $sheet->cell('H1',function ($cell){$cell->setValue('Tanpa Kabar'); });
                        $sheet->cell('I1',function ($cell){$cell->setValue('Izin'); });
                        $sheet->cell('J1',function ($cell){$cell->setValue('Izin Terlambat'); });
                        $sheet->cell('K1',function ($cell){$cell->setValue('Tidak Apel'); });
                        $sheet->cell('L1',function ($cell){$cell->setValue('Sakit'); });
                        $sheet->cell('M1',function ($cell){$cell->setValue('Cuti'); });
                        $sheet->cell('N1',function ($cell){$cell->setValue('Tugas Luar'); });
                        $sheet->cell('O1',function ($cell){$cell->setValue('Tugas Belajar'); });
                        $sheet->cell('P1',function ($cell){$cell->setValue('Ijin Kepentingan Lain'); });
                        $sheet->cell('Q1',function ($cell){$cell->setValue('Pulang Cepat'); });
                        $sheet->cell('R1',function ($cell){$cell->setValue('Akumulasi Terlambat'); });
                        $sheet->cell('S1',function ($cell){$cell->setValue('Akumulasi Kerja'); });


                    });
            })->download('xls');
    }
    
}
