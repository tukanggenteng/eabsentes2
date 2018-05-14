<?php

namespace App\Http\Controllers;

use App\pegawai;
use App\instansi;
use App\masterbulanan;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    //
    public function monitoringinstansiminggu(Request $request)
    {
        $instansi=instansi::leftJoin('masterbulanans','masterbulanans.instansi_id','=','instansis.id')
                            ;
        $pegawaitahun = DB::select('SELECT nip,instansi_id,nama,@pegawai:=id,
                                        (SELECT AVG(persentase_tidakhadir)
                                            FROM masterbulanans
                                            WHERE pegawai_id=@pegawai AND YEAR(periode)="'.$tahun.'" ) AS persentasehadir,
                                        (SELECT AVG(persentase_apel)
                                            FROM masterbulanans
                                            WHERE pegawai_id=@pegawai AND YEAR(periode)="'.$tahun.'" ) AS persentaseapel,
                                        (SELECT SEC_TO_TIME( SUM(time_to_sec(total_akumulasi))) as total
                                            FROM masterbulanans
                                            WHERE pegawai_id=@pegawai AND YEAR(periode)="'.$tahun.'" ) AS total
                                    FROM pegawais where instansi_id="'.Auth::user()->instansi_id.'" ORDER BY total ASC');

        $instansi= DB::select('SELECT namaInstansi,@instansi:=id,
                                     (SELECT COUNT(hari))
                                FROM instansis 
                                WHERE id="'.$request->instansi_id.'" ORDER BY '.$request->jenisabsen.' '.$request->metode);

        return view('monitoring.rekapmingguinstansi');
    }
}
