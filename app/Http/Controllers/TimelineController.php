<?php

namespace App\Http\Controllers;

use App\atts_tran;
use Illuminate\Http\Request;

use App\harikerja;
use App\Events\Timeline;
use App\att;
use App\rulejadwalpegawai;
use Illuminate\Support\Facades\Auth;

class TimelineController extends Controller
{
    //
    public function index(Request $request){
        $attstrans=atts_tran::join('pegawais','atts_trans.pegawai_id','=','pegawais.id')
            ->join('instansis','atts_trans.lokasi_alat', '=', 'instansis.id')
            ->join('instansis as pegawaiinstansis','pegawais.instansi_id', '=', 'pegawaiinstansis.id')
            ->orderBy('atts_trans.id','atts_trans.tanggal', 'desc')
            ->orderBy('atts_trans.jam','atts_trans.tanggal', 'desc')
            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
            ->paginate(30, array('pegawaiinstansis.namaInstansi as instansiPegawai','pegawais.nama','atts_trans.jam','atts_trans.tanggal','atts_trans.status_kedatangan','instansis.namaInstansi'));


        if ($request->ajax()) {
            $view = view('timeline.datatimeline',compact('attstrans'))->render();
            return response()->json(['html'=>$view]);
        }



        return view('timeline.timeline2',compact('attstrans'));
    }
}
