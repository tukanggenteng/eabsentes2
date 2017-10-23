<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\rekapbulanan;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function encryptOTP($data){
        $hitungchar=strlen($data);
        $pecahstring=str_split($data);
        $kunci="D4v1Nc!j4R4k134rp4K4130ff1c3";
        $pecahkunci=str_split($kunci);
        $hasilhash="";
        foreach ($pecahstring as $key => $value) {
            $hasilstring=ord($value);
            $hasilkunci=ord($pecahkunci[$key]);
            $hasilstringkunci=$hasilkunci+$hasilstring;
            $modhasilstringkunci=fmod($hasilstringkunci, 26);
            $hasilmod=$modhasilstringkunci+31;
            $hurufmod=chr($hasilmod);
            $hasilhash=$hasilhash.$hurufmod;
        }
        return ($hasilhash);
    }


    protected function kurangwaktu($base, $toadd) {
        date_default_timezone_set('Asia/Makassar');
        $start = date_create($toadd);
        $end = date_create($base);
        $diff=date_diff($start,$end);
        return $diff->format("%H:%i:%s");
    }


    protected function notifrekap()
    {
        $bulan=(date("Y-m-d"));

        $bulan=date("m",strtotime("-1 month",strtotime($bulan)));
        $tahun=date("Y");

//        return dd($bulan);

        $cekrekap=rekapbulanan::where('instansi_id','=',Auth::user()->instansi_id)
           ->whereMonth('periode','=',$bulan)
           ->whereYear('periode','=',$tahun)
            ->count();
//        $cekrekap=rekapbulanan::all()
//            ->count();
//        dd($cekrekap);
        if ($cekrekap==0){
            return "Segera lakukan rekap absensi pegawai bulan lalu. Jika rekap absensi pegawai rampung silahkan lakukan rekap bulanan dengan mengklik ";
        }
        else
        {
            return "";
        }
    }
}
