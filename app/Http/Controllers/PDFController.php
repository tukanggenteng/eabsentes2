<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\att;
use App\finalrekapbulanan;
use Illuminate\Support\Facades\Auth;
use PDF;

class PDFController extends Controller
{
    //
    public function index(Request $request){

      if (isset($request->nip) && isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);

        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->whereMonth('atts.tanggal_att','=',$tanggal[0])
        ->whereYear('atts.tanggal_att','=',$tanggal[1])
        ->where('pegawais.nip','=',$request->nip)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->paginate(30);

        return view('laporan.laporanharian',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);

      }
      elseif (!isset($request->nip) && isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);

        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->whereMonth('atts.tanggal_att','=',$tanggal[0])
        ->whereYear('atts.tanggal_att','=',$tanggal[1])
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->paginate(30);

        return view('laporan.laporanharian',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (isset($request->nip) && !isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);

        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->where('pegawais.nip','=',$request->nip)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->paginate(30);

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
        ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->paginate(30);
        $nip=null;
        $tanggal=null;
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
        
        ->get();

        $instansi=Auth::user()->instansi->namaInstansi;
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $pdf=PDF::loadView('pdf.pdfharian',['atts'=>$atts,'instansi'=>$instansi]);
        // return $pdf->setPaper('F4', 'landscape')->download('laporanharian.pdf');
        return $pdf->setPaper('F4', 'landscape')->stream();
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
        ->whereMonth('atts.tanggal_att','=',$tanggal[0])
        ->whereYear('atts.tanggal_att','=',$tanggal[1])
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
        return $pdf->setPaper('F4', 'landscape')->stream();
    }

    public function pdfhariantanggal($id){
        $id=decrypt($id);
        $tanggal=explode('-',$id);

        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->whereMonth('atts.tanggal_att','=',$tanggal[0])
        ->whereYear('atts.tanggal_att','=',$tanggal[1])
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
        return $pdf->setPaper('F4', 'landscape')->stream();
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
        return $pdf->setPaper('F4', 'landscape')->stream();
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

}
