<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\att;
use App\finalrekapbulanan;
use App\masterbulanan;
use Illuminate\Support\Facades\Auth;
use PDF;

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
        ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->limit(100)
        ->get();

        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $pdf=PDF::loadView('pdf.pdfharian',['atts'=>$atts,'instansi'=>$instansi]);
        // return $pdf->setPaper('F4', 'landscape')->download('laporanharian.pdf');
        return $pdf->setPaper(array(0, 0, 595.35, 935.55), 'landscape')->stream();
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
        ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->get();

        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $pdf=PDF::loadView('pdf.pdfharian',['atts'=>$atts,'instansi'=>$instansi]);
        // return $pdf->setPaper('F4', 'landscape')->download('laporanharian.pdf');
        // return $pdf->setPaper('F4', 'landscape')->stream();
        return $pdf->setPaper(array(0, 0, 595.35, 935.55), 'landscape')->stream();
        
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
        // return $pdf->setPaper('F4', 'landscape')->download('laporanharian.pdf');
        // return $pdf->setPaper('F4', 'landscape')->stream();
        return $pdf->setPaper(array(0, 0, 595.35, 935.55), 'landscape')->stream();
        
    }


    public function pdfbulanindex(Request $request){

      if (isset($request->nip) && isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);

        $atts=finalrekapbulanan::leftJoin('pegawais','finalrekapbulanans.pegawai_id','=','pegawais.id')
        ->leftJoin('instansis','instansis.id','=','finalrekapbulanans.pegawai_id')
        ->whereMonth('finalrekapbulanans.periode','=',$tanggal[0])
        ->whereYear('finalrekapbulanans.periode','=',$tanggal[1])
        ->where('pegawais.nip','=',$request->nip)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('finalrekapbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
        ->orderBy('finalrekapbulanans.periode','desc')
        ->paginate(30);

        return view('laporan.laporanbulan',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);

      }
      elseif (!isset($request->nip) && isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);

        $atts=finalrekapbulanan::leftJoin('pegawais','finalrekapbulanans.pegawai_id','=','pegawais.id')
        ->leftJoin('instansis','instansis.id','=','finalrekapbulanans.pegawai_id')
        ->whereMonth('finalrekapbulanans.periode','=',$tanggal[0])
        ->whereYear('finalrekapbulanans.periode','=',$tanggal[1])
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('finalrekapbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
        ->orderBy('finalrekapbulanans.periode','desc')
        ->paginate(30);
        $request->nip=null;
        return view('laporan.laporanbulan',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (isset($request->nip) && !isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);

        $atts=finalrekapbulanan::leftJoin('pegawais','finalrekapbulanans.pegawai_id','=','pegawais.id')
        ->leftJoin('instansis','instansis.id','=','finalrekapbulanans.pegawai_id')
        ->where('pegawais.nip','=',$request->nip)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('finalrekapbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
        ->orderBy('finalrekapbulanans.periode','desc')
        ->paginate(30);
        $tanggal=null;
        return view('laporan.laporanbulan',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (!isset($request->nip) && !isset($request->tanggal))
      {

        $atts=finalrekapbulanan::leftJoin('pegawais','finalrekapbulanans.pegawai_id','=','pegawais.id')
        ->leftJoin('instansis','instansis.id','=','finalrekapbulanans.pegawai_id')
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('finalrekapbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
        ->orderBy('finalrekapbulanans.periode','desc')
        ->paginate(30);
        $nip=null;
        $tanggal=null;
        return view('laporan.laporanbulan',['atts'=>$atts,'nip'=>$nip,'tanggal'=>$tanggal]);

      }
    }

    public function pdfbulan(){

      $atts=finalrekapbulanan::leftJoin('pegawais','finalrekapbulanans.pegawai_id','=','pegawais.id')
      ->leftJoin('instansis','instansis.id','=','finalrekapbulanans.pegawai_id')
      ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
      ->select('finalrekapbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
      ->orderBy('finalrekapbulanans.periode','desc')
      ->get();



        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $pdf=PDF::loadView('pdf.pdfbulan',['atts'=>$atts,'instansi'=>$instansi]);
        // return $pdf->setPaper('F4', 'landscape')->download('laporanbulanan.pdf');
        return $pdf->setPaper('F4', 'landscape')->stream();
    }

    public function pdfbulanfull($id,$id2){

        $id=decrypt($id);
        $id2=decrypt($id2);

        $tanggal=explode('-',$id);

        $atts=finalrekapbulanan::leftJoin('pegawais','finalrekapbulanans.pegawai_id','=','pegawais.id')
        ->leftJoin('instansis','instansis.id','=','finalrekapbulanans.pegawai_id')
        ->whereMonth('finalrekapbulanans.periode','=',$tanggal[0])
        ->whereYear('finalrekapbulanans.periode','=',$tanggal[1])
        ->where('pegawais.nip','=',$id2)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('finalrekapbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
        ->orderBy('finalrekapbulanans.periode','desc')
        ->paginate(30);


        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $pdf=PDF::loadView('pdf.pdfbulan',['atts'=>$atts,'instansi'=>$instansi]);
        // return $pdf->setPaper('F4', 'landscape')->download('laporanbulanan.pdf');
        return $pdf->setPaper('F4', 'landscape')->stream();
    }


    public function pdfbulantanggal($id){
        $id=decrypt($id);
        $tanggal=explode('-',$id);

        $atts=finalrekapbulanan::leftJoin('pegawais','finalrekapbulanans.pegawai_id','=','pegawais.id')
        ->leftJoin('instansis','instansis.id','=','finalrekapbulanans.pegawai_id')
        ->whereMonth('finalrekapbulanans.periode','=',$tanggal[0])
        ->whereYear('finalrekapbulanans.periode','=',$tanggal[1])
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('finalrekapbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
        ->orderBy('finalrekapbulanans.periode','desc')
        ->paginate(30);


        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $pdf=PDF::loadView('pdf.pdfbulan',['atts'=>$atts,'instansi'=>$instansi]);
        // return $pdf->setPaper('F4', 'landscape')->download('laporanbulanan.pdf');
        return $pdf->setPaper('F4', 'landscape')->stream();
    }

    public function pdfbulanannip($id){

        $id=decrypt($id);

        $atts=finalrekapbulanan::leftJoin('pegawais','finalrekapbulanans.pegawai_id','=','pegawais.id')
        ->leftJoin('instansis','instansis.id','=','finalrekapbulanans.pegawai_id')
        ->where('pegawais.nip','=',$id)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('finalrekapbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
        ->orderBy('finalrekapbulanans.periode','desc')
        ->paginate(30);


        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $pdf=PDF::loadView('pdf.pdfbulan',['atts'=>$atts,'instansi'=>$instansi]);
        // return $pdf->setPaper('F4', 'landscape')->download('laporanbulanan.pdf');
        return $pdf->setPaper('F4', 'landscape')->stream();
    }

    public function pdfmingguanindex(Request $request){
        // dd("asd");
      if (isset($request->nip) && isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);

        $atts=masterbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
        ->leftJoin('instansis','instansis.id','=','masterbulanans.pegawai_id')
        ->where('masterbulanans.periode','=',$request->tanggal)
        // ->whereYear('finalrekapbulanans.periode','=',$tanggal[1])
        ->where('pegawais.nip','=',$request->nip)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('masterbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
        ->orderBy('masterbulanans.periode','desc')
        ->paginate(30);
        // dd("dsa");
        return view('laporan.laporanminggu',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);

      }
      elseif (!isset($request->nip) && isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);

        $atts=masterbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
        ->leftJoin('instansis','instansis.id','=','masterbulanans.pegawai_id')
        // ->whereMonth('finalrekapbulanans.periode','=',$tanggal[0])
        // ->whereYear('finalrekapbulanans.periode','=',$tanggal[1])
        ->where('masterbulanans.periode','=',$request->tanggal)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('masterbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
        ->orderBy('masterbulanans.periode','desc')
        ->paginate(30);
        // dd("asd");
        $request->nip=null;
        return view('laporan.laporanminggu',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (isset($request->nip) && !isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);

        $atts=masterbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
        ->leftJoin('instansis','instansis.id','=','masterbulanans.pegawai_id')
        ->where('pegawais.nip','=',$request->nip)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('masterbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
        ->orderBy('masterbulanans.periode','desc')
        ->paginate(30);
        $tanggal=null;
        // dd("as");
        return view('laporan.laporanminggu',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (!isset($request->nip) && !isset($request->tanggal))
      {
        // dd("asd");
        $atts=masterbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
        ->leftJoin('instansis','instansis.id','=','masterbulanans.pegawai_id')
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('masterbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
        ->orderBy('masterbulanans.periode','desc')
        ->paginate(30);
        $nip=null;
        $tanggal=null;
        // dd("asdvd");
        return view('laporan.laporanminggu',['atts'=>$atts,'nip'=>$nip,'tanggal'=>$tanggal]);
      }
    }

    public function pdfminggu(){

        $atts=masterbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
        ->leftJoin('instansis','instansis.id','=','masterbulanans.pegawai_id')
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('masterbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
        ->orderBy('masterbulanans.periode','desc')
        ->get();


        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $pdf=PDF::loadView('pdf.pdfbulan',['atts'=>$atts,'instansi'=>$instansi]);
        // return $pdf->setPaper('F4', 'landscape')->download('laporanbulanan.pdf');
        return $pdf->setPaper('F4', 'landscape')->stream();
    }

    public function pdfminggufull($id,$id2){

        $id=decrypt($id);
        $id2=decrypt($id2);

        $tanggal=explode('-',$id);

        $atts=finalrekapbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
        ->leftJoin('instansis','instansis.id','=','masterbulanans.pegawai_id')
        // ->whereMonth('masterbulanans.periode','=',$tanggal[0])
        // ->whereYear('masterbulanans.periode','=',$tanggal[1])
        ->where('masterbulanans.periode','=',$id)        
        ->where('pegawais.nip','=',$id2)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('masterbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
        ->orderBy('masterbulanans.periode','desc')
        ->paginate(30);


        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $pdf=PDF::loadView('pdf.pdfbulan',['atts'=>$atts,'instansi'=>$instansi]);
        // return $pdf->setPaper('F4', 'landscape')->download('laporanbulanan.pdf');
        return $pdf->setPaper('F4', 'landscape')->stream();
    }


    public function pdfminggutanggal($id){
        $id=decrypt($id);
        $tanggal=explode('-',$id);

        $atts=masterbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
        ->leftJoin('instansis','instansis.id','=','masterbulanans.pegawai_id')
        // ->whereMonth('masterbulanans.periode','=',$tanggal[0])
        // ->whereYear('masterbulanans.periode','=',$tanggal[1])
        ->where('masterbulanans.periode','=',$id)        
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('masterbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
        ->orderBy('masterbulanans.periode','desc')
        ->paginate(30);


        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $pdf=PDF::loadView('pdf.pdfbulan',['atts'=>$atts,'instansi'=>$instansi]);
        // return $pdf->setPaper('F4', 'landscape')->download('laporanbulanan.pdf');
        return $pdf->setPaper('F4', 'landscape')->stream();
    }

    public function pdfminggunip($id){

        $id=decrypt($id);

        $atts=masterbulanan::leftJoin('pegawais','masterbulanans.pegawai_id','=','pegawais.id')
        ->leftJoin('instansis','instansis.id','=','masterbulanans.pegawai_id')
        ->where('pegawais.nip','=',$id)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('masterbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
        ->orderBy('masterbulanans.periode','desc')
        ->paginate(30);


        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $pdf=PDF::loadView('pdf.pdfbulan',['atts'=>$atts,'instansi'=>$instansi]);
        // return $pdf->setPaper('F4', 'landscape')->download('laporanbulanan.pdf');
        return $pdf->setPaper('F4', 'landscape')->stream();
    }
    
}
