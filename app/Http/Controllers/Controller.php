<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\rekapbulanan;
use Carbon\Carbon;
use App\att;
use App\atts_tran;
use App\instansi;
use App\jadwalkerja;
use App\pegawai;
use App\rulejadwalpegawai;
use App\rulejammasuk;
use App\adminpegawai;
use App\hapusfingerpegawai;
use App\lograspberry;
use App\historyfingerpegawai;
use App\attendancecheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Request as Request2;
use App\Events\Timeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    
    protected function encryptOTP($data){
        $hitungchar=strlen($data);
        $pecahstring=str_split($data);
        $kunci="D4v1Nc!j4R4k134rp4K4130ff1c3*72@1}a1-=+121D4v1Nc!j4R4k134rp4K4130ff1c3*72@1}a1-=+121D4v1Nc!j4R4k134rp4K4130ff1c3*72@1}a1-=+121D4v1Nc!j4R4k134rp4K4130ff1c3*72@1}a1-=+121";
        $pecahkunci=str_split($kunci);
        $hasilhash="";
        foreach ($pecahstring as $key => $value) {
            $hasilstring=ord($value);
            $hasilkunci=ord($pecahkunci[$key]);
            $hasilstringkunci=$hasilkunci+$hasilstring;
            $modhasilstringkunci=fmod($hasilstringkunci, 26);
            $hasilmod=$modhasilstringkunci+41;
            $hurufmod=chr($hasilmod);
            $hasilhash=$hasilhash.$hurufmod;
        }
        return ($hasilhash);
    }

    protected function encryptOTPRaspi($data){
        $hitungchar=strlen($data);
        $pecahstring=str_split($data);
        $kunci="p4y03n9t3d03hw4k4c4ub0l0b0l0w4k4c4u";
        $pecahkunci=str_split($kunci);
        $hasilhash="";
        foreach ($pecahstring as $key => $value) {
            $hasilstring=ord($value);
            $hasilkunci=ord($pecahkunci[$key]);
            $hasilstringkunci=$hasilkunci+$hasilstring;
            $modhasilstringkunci=fmod($hasilstringkunci, 26);
            $hasilmod=$modhasilstringkunci+41;
            $hurufmod=chr($hasilmod);
            $hasilhash=$hasilhash.$hurufmod;
        }
        return ($hasilhash);
    }

    // kurangwaktu(dari,sampai)
    protected function kurangwaktu($base, $toadd) {
        date_default_timezone_set('Asia/Makassar');
        $toadd = date_create($toadd);
        $base = date_create($base);
        $diff=date_diff($toadd,$base);
        return ($diff->days * 24 + $diff->h) .$diff->format(":%I:%S");
    }

    protected function difftanggal($base, $toadd) {
        date_default_timezone_set('Asia/Makassar');
        $toadd = date_create($toadd);
        $base = date_create($base);
        $diff=date_diff($toadd,$base);
        // return $diff->format("%d");
        return $diff->days;
    }

    protected function fromRGB($R, $G, $B)
    {

        $R = dechex($R);
        if (strlen($R)<2)
        $R = '0'.$R;

        $G = dechex($G);
        if (strlen($G)<2)
        $G = '0'.$G;

        $B = dechex($B);
        if (strlen($B)<2)
        $B = '0'.$B;

        return '#' . $R . $G . $B;
    }

    protected function tambahwaktu($time1,$time2){
        $times = array($time1, $time2);
        $seconds = 0;
        foreach ($times as $time)
        {
            list($hour,$minute,$second) = explode(':', $time);
            $seconds += $hour*3600;
            $seconds += $minute*60;
            $seconds += $second;
        }
        $hours = floor($seconds/3600);
        $seconds -= $hours*3600;
        $minutes  = floor($seconds/60);
        $seconds -= $minutes*60;
        if($seconds < 9)
        {
        $seconds = "0".$seconds;
        }
        if($minutes < 9)
        {
        $minutes = "0".$minutes;
        }
            if($hours < 9)
        {
        $hours = "0".$hours;
        }
        return "{$hours}:{$minutes}:{$seconds}";
    }

    #tidak terpakai
    protected function notifrekap()
    {
        $bulan=(date("Y-m-d"));

        $bulan=date("m",strtotime("-1 month",strtotime($bulan)));
        $tahun=date("Y");

        $date=date('N');

        $sekarang=date('Y-m-d');
        $status=false;
        if ($date==1){
            $hari='Senin';
            $awal=date("Y-m-d",strtotime("-7 days",strtotime($sekarang)));
            $akhir=date("Y-m-d",strtotime("-1 days",strtotime($sekarang)));
            $status=true;
        }
        elseif ($date==2){
            $hari='Selasa';
            $awal=date("Y-m-d",strtotime("-8 days",strtotime($sekarang)));
            $akhir=date("Y-m-d",strtotime("-2 days",strtotime($sekarang)));
            $status=true;
        }

        if ($status==true)
        {
        $cekrekap=rekapbulanan::leftJoin('pegawais','pegawais.id','=','rekapbulanans.pegawai_id')
            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
            ->where('rekapbulanans.periode','=',$awal)
            ->count();
        $hitungatts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                    ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                    ->where('atts.tanggal_att','>=',$awal)
                    ->where('atts.tanggal_att','<=',$akhir)
                    ->distinct()
                    ->count('atts.tanggal_att');
        }
        else
        {
            $hitungatts=1;
            $cekrekap=1;
        }   

        if (($cekrekap==0) && ($hitungatts >= 5)){
            return "Segera lakukan rekap absensi pegawai minggu lalu. Jika rekap absensi pegawai rampung silahkan lakukan rekap mingguan dengan mengklik ";
        }
        else
        {
            return "";
        }
    }

    protected function jam_masuk($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint){

        $hitungabsen=att::where('pegawai_id','=',$pegawai_id_fingerprint)
        ->where('tanggal_att','=',$tanggal_fingerprint)
        ->count();
        
        if ($hitungabsen>0) {
            // dd("asd");
            $absen = att::where('pegawai_id', '=', $pegawai_id_fingerprint)
                ->where('tanggal_att','=',$tanggal_fingerprint)
                ->latest()
                ->first();

                //cek kecocokan jam masuk berdasarkan jadwalkerja
                $cek = jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                    ->select('jadwalkerjas.id', 'jadwalkerjas.sifat' , 'jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal', 'rulejammasuks.jamsebelum_masukkerja')
                    ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                    ->get();
                $jamawal = date("H:i:s", strtotime($cek[0]['jamsebelum_masukkerja']));
                $jamakhir = $cek[0]['jam_masukjadwal'];
                //menentukan jam toleransi masuk pegawai
                // $jamakhir2 = date("H:i:s", strtotime("+30 minutes", strtotime($jamakhir)));
                $jamakhir2=$jamakhir;
                $jamfingerprint = date("H:i:s", strtotime($jam_fingerprint));
                // cek bisa masuk atau tidak
                if (($jamfingerprint >= $cek[0]['jamsebelum_masukkerja']) && ($jamfingerprint<=$cek[0]['jam_keluarjadwal'])) {
                    //menghitung data absen trans pegawai
                    $cari = atts_tran::where('pegawai_id', '=', $pegawai_id_fingerprint)
                        ->where('tanggal', '=', $tanggal_fingerprint)
                        ->where('status_kedatangan', '=', $status_fingerprint)
                        ->count();
                        //jika hasil nya lebih dari 0 maka
                    if ($cari > 0) {
                        
                        // gasan dokter meabsen lgi
                        if (($cek[0]['sifat']=="FD") && (($absen->jam_keluar!=null))){
                            
                                    $user = new att();
                                    $user->pegawai_id = $jadwalpegawai->pegawai_id;
                                    $user->tanggal_att=$tanggalharini;
                                    $user->terlambat='00:00:00';
                                    $user->jadwalkerja_id=$jadwalkerja->jadwalkerja_id;
                                    if ($jadwalkerja->sifat=="FD"){
                                        $user->jenisabsen_id = '13';
                                    }
                                    else{
                                        $user->jenisabsen_id = '2';
                                    }
                                    $user->akumulasi_sehari='00:00:00';
                                    $user->save();

                                    //menambah data absen trans yang baru
                                    $save = new atts_tran();
                                    $save->pegawai_id = $pegawai_id_fingerprint;
                                    $save->tanggal = $tanggal_fingerprint;
                                    $save->jam = $jam_fingerprint;
                                    $save->lokasi_alat = $instansi_fingerprint;
                                    $save->status_kedatangan = $status_fingerprint;
                                    $save->save();

                                    //meubah data masuk
                                    $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                                        ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                                        ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                                        ->first();
                                        
                                    $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                                        ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();
                                    if (($pegawai[0]['instansi_id']==$instansi_fingerprint) || ($table->jenisabsen_id==2))
                                    {
                                        $table->jenisabsen_id = "1";
                                    }else {
                                        // $table->jenisabsen_id = "8";
                                        $table->jenisabsen_id = "1";
                                    }

                                    if ($jam_fingerprint>$jamakhir)
                                    {
                                        $terlambatnya=$this->kurangwaktu($jam_fingerprint,$jamakhir);
                                        $table->apel="0";

                                    }
                                    else {
                                        if ($cek[0]['sifat']=="WA"){
                                            $table->apel="1";
                                        }
                                        else
                                        {
                                            $table->apel="0";
                                        }
                                        $terlambatnya="00:00:00";
                                    }

                                    

                                    $table->terlambat=$terlambatnya;
                                    $table->jam_masuk = $jam_fingerprint;
                                    $table->tanggal_att = $tanggal_fingerprint;
                                    $table->masukinstansi_id = $instansi_fingerprint;

                                    $table->save();



                                    $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();

                                    if ($jam_fingerprint>$jamakhir2){

                                    $status = "hadir terlambat";
                                    }
                                    else {

                                    $status = "hadir";
                                    }
                                    if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                                        $class = "bg-green";
                                    } else {
                                        $class = "bg-yellow";
                                    }
                                    $tanggalbaru = date("d-M-Y");

                                    event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                                    return "Success";
                                    //bisadatang
                        }
                        else
                        {
                            //menambah data absen trans yang baru
                            // $save = new atts_tran();
                            // $save->pegawai_id = $pegawai_id_fingerprint;
                            // $save->tanggal = $tanggal_fingerprint;
                            // $save->jam = $jam_fingerprint;
                            // $save->lokasi_alat = $instansi_fingerprint;
                            // $save->status_kedatangan = $status_fingerprint;
                            // $save->save();

                            // //meubah data masuk
                            // $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                            //     ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                            //     ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                            //     ->first();
                                
                            // $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                            //     ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();
                            // if (($pegawai[0]['instansi_id']==$instansi_fingerprint) || ($table->jenisabsen_id==2))
                            // {
                            //     $table->jenisabsen_id = "1";
                            // }else {
                            //     // $table->jenisabsen_id = "8";
                            //     $table->jenisabsen_id = "1";
                            // }

                            // if ($jam_fingerprint>$jamakhir)
                            // {
                            //     $terlambatnya=$this->kurangwaktu($jam_fingerprint,$jamakhir);
                            //     $table->apel="0";

                            // }
                            // else {
                            //     if ($cek[0]['sifat']=="WA"){
                            //         $table->apel="1";
                            //     }
                            //     else
                            //     {
                            //         $table->apel="0";
                            //     }
                            //     $terlambatnya="00:00:00";
                            // }

                            // $table->terlambat=$terlambatnya;
                            // $table->jam_masuk = $jam_fingerprint;
                            // $table->tanggal_att = $tanggal_fingerprint;
                            // $table->masukinstansi_id = $instansi_fingerprint;

                            // $table->save();



                            // $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();

                            // if ($jam_fingerprint>$jamakhir2){

                            //     $status = "hadir terlambat";
                            // }
                            // else {

                            //     $status = "hadir";
                            // }
                            // if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                            //     $class = "bg-green";
                            // } else {
                            //     $class = "bg-yellow";
                            // }
                            // $tanggalbaru = date("d-M-Y");

                            // event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                            // return "Success";
                            //bisadatang
                            
                        }
                    } 
                    else 
                    {
                        // dd("asd");
                        //menambah data absen trans yang baru
                        $save = new atts_tran();
                        $save->pegawai_id = $pegawai_id_fingerprint;
                        $save->tanggal = $tanggal_fingerprint;
                        $save->jam = $jam_fingerprint;
                        $save->lokasi_alat = $instansi_fingerprint;
                        $save->status_kedatangan = $status_fingerprint;
                        $save->save();

                        //meubah data masuk
                        $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                            ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                            ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                            ->first();
                            
                        $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                            ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();
                        if (($pegawai[0]['instansi_id']==$instansi_fingerprint) || ($table->jenisabsen_id==2))
                        {
                            $table->jenisabsen_id = "1";
                        }else {
                            // $table->jenisabsen_id = "8";
                            $table->jenisabsen_id = "1";
                        }

                        if ($jam_fingerprint>$jamakhir)
                        {
                            $terlambatnya=$this->kurangwaktu($jam_fingerprint,$jamakhir);
                            $table->apel="0";

                            if ($cek[0]['sifat']=="FD"){

                                $terlambatnya="00:00:00";
                            }

                        }
                        else {
                            if ($cek[0]['sifat']=="WA"){
                                $table->apel="1";
                            }
                            else
                            {
                                $table->apel="0";
                            }
                            $terlambatnya="00:00:00";
                        }

                        $table->terlambat=$terlambatnya;
                        $table->jam_masuk = $jam_fingerprint;
                        $table->tanggal_att = $tanggal_fingerprint;
                        $table->masukinstansi_id = $instansi_fingerprint;

                        $table->save();



                        $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();

                        if ($jam_fingerprint>$jamakhir2){

                        $status = "hadir terlambat";
                        }
                        else {

                        $status = "hadir";
                        }
                        if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                            $class = "bg-green";
                        } else {
                            $class = "bg-yellow";
                        }
                        $tanggalbaru = date("d-M-Y");

                        event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                        return "Success";
                        //bisadatang
                    }

                } else {
                    return ("Success");
                }
        }
        else
        {
            $table=attendancecheck::where('tanggalcheckattendance','=',$tanggal_fingerprint)
                    ->count();
            if ($table > 0)
            {
                return "Success";
            }        
            else
            {
                return "Failed";
            }
        }
    }

    protected function jam_masuktanpaapel($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint){

        $hitungabsen=att::where('pegawai_id','=',$pegawai_id_fingerprint)
        ->where('tanggal_att','=',$tanggal_fingerprint)
        ->count();
        
        if ($hitungabsen>0) {

            $absen = att::where('pegawai_id', '=', $pegawai_id_fingerprint)
                ->where('tanggal_att','=',$tanggal_fingerprint)
                ->latest()
                ->first();

                //cek kecocokan jam masuk berdasarkan jadwalkerja
                $cek = jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                    ->select('jadwalkerjas.id', 'jadwalkerjas.sifat' , 'jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal', 'rulejammasuks.jamsebelum_masukkerja')
                    ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                    ->get();
                $jamawal = date("H:i:s", strtotime($cek[0]['jamsebelum_masukkerja']));
                $jamakhir = $cek[0]['jam_masukjadwal'];
                //menentukan jam toleransi masuk pegawai
                // $jamakhir2 = date("H:i:s", strtotime("+30 minutes", strtotime($jamakhir)));
                $jamakhir2=$jamakhir;
                $jamfingerprint = date("H:i:s", strtotime($jam_fingerprint));
                // cek bisa masuk atau tidak
                if (($jamfingerprint >= $cek[0]['jamsebelum_masukkerja']) && ($jamfingerprint<=$cek[0]['jam_keluarjadwal'])) {
                    //menghitung data absen trans pegawai
                    $cari = atts_tran::where('pegawai_id', '=', $pegawai_id_fingerprint)
                        ->where('tanggal', '=', $tanggal_fingerprint)
                        ->where('status_kedatangan', '=', $status_fingerprint)
                        ->count();
                    //jika hasil nya lebih dari 0 maka
                    if ($cari > 0) {
                        // gasan dokter meabsen lgi
                        if (($cek[0]['sifat']=="FD") && (!empty($absen->jam_keluar))){
                                    $user = new att();
                                    $user->pegawai_id = $jadwalpegawai->pegawai_id;
                                    $user->tanggal_att=$tanggalharini;
                                    $user->terlambat='00:00:00';
                                    $user->jadwalkerja_id=$jadwalkerja->jadwalkerja_id;
                                    if ($jadwalkerja->sifat=="FD"){
                                        $user->jenisabsen_id = '13';
                                    }
                                    else{
                                        $user->jenisabsen_id = '2';
                                    }
                                    $user->akumulasi_sehari='00:00:00';
                                    $user->save();

                                    //menambah data absen trans yang baru
                                    $save = new atts_tran();
                                    $save->pegawai_id = $pegawai_id_fingerprint;
                                    $save->tanggal = $tanggal_fingerprint;
                                    $save->jam = $jam_fingerprint;
                                    $save->lokasi_alat = $instansi_fingerprint;
                                    $save->status_kedatangan = $status_fingerprint;
                                    $save->save();

                                    //meubah data masuk
                                    $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                                        ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                                        ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                                        ->first();
                                        
                                    $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                                        ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();
                                    if (($pegawai[0]['instansi_id']==$instansi_fingerprint) || ($table->jenisabsen_id==2))
                                    {
                                        $table->jenisabsen_id = "1";
                                    }else {
                                        // $table->jenisabsen_id = "8";
                                        $table->jenisabsen_id = "1";
                                    }

                                    if ($jam_fingerprint>$jamakhir)
                                    {
                                        $terlambatnya=$this->kurangwaktu($jam_fingerprint,$jamakhir);
                                        $table->apel="0";

                                    }
                                    else {
                                        if ($cek[0]['sifat']=="WA"){
                                            $table->apel="0";
                                        }
                                        else
                                        {
                                            $table->apel="0";
                                        }
                                        $terlambatnya="00:00:00";
                                    }

                                    

                                    $table->terlambat=$terlambatnya;
                                    $table->jam_masuk = $jam_fingerprint;
                                    $table->tanggal_att = $tanggal_fingerprint;
                                    $table->masukinstansi_id = $instansi_fingerprint;

                                    $table->save();



                                    $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();

                                    if ($jam_fingerprint>$jamakhir2){

                                    $status = "hadir terlambat";
                                    }
                                    else {

                                    $status = "hadir";
                                    }
                                    if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                                        $class = "bg-green";
                                    } else {
                                        $class = "bg-yellow";
                                    }
                                    $tanggalbaru = date("d-M-Y");

                                    event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                                    return "Success";
                                    //bisadatang
                        }

                    } 
                    else 
                    {
                        //menambah data absen trans yang baru
                        $save = new atts_tran();
                        $save->pegawai_id = $pegawai_id_fingerprint;
                        $save->tanggal = $tanggal_fingerprint;
                        $save->jam = $jam_fingerprint;
                        $save->lokasi_alat = $instansi_fingerprint;
                        $save->status_kedatangan = $status_fingerprint;
                        $save->save();

                        //meubah data masuk
                        $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                            ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                            ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                            ->first();
                            
                        $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                            ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();
                        if (($pegawai[0]['instansi_id']==$instansi_fingerprint) || ($table->jenisabsen_id==2))
                        {
                            $table->jenisabsen_id = "1";
                        }else {
                            // $table->jenisabsen_id = "8";
                            $table->jenisabsen_id = "1";
                        }

                        if ($jam_fingerprint>$jamakhir)
                        {
                            $terlambatnya=$this->kurangwaktu($jam_fingerprint,$jamakhir);
                            $table->apel="0";

                        }
                        else {
                            if ($cek[0]['sifat']=="WA"){
                                $table->apel="0";
                            }
                            else
                            {
                                $table->apel="0";
                            }
                            $terlambatnya="00:00:00";
                        }

                        $table->terlambat=$terlambatnya;
                        $table->jam_masuk = $jam_fingerprint;
                        $table->tanggal_att = $tanggal_fingerprint;
                        $table->masukinstansi_id = $instansi_fingerprint;

                        $table->save();



                        $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();

                        if ($jam_fingerprint>$jamakhir2){

                        $status = "hadir terlambat";
                        }
                        else {

                        $status = "hadir";
                        }
                        if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                            $class = "bg-green";
                        } else {
                            $class = "bg-yellow";
                        }
                        $tanggalbaru = date("d-M-Y");

                        event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                        return "Success";
                        //bisadatang
                    }

                } else {
                    return ("Success");
                }
        }
        else
        {
            $table=attendancecheck::where('tanggalcheckattendance','=',$tanggal_fingerprint)
                    ->count();
            if ($table > 0)
            {
                return "Success";
            }        
            else
            {
                return "Failed";
            }
        }
    }

    protected function keluaristirahat($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint){
        
        $date=date('N',strtotime($tanggal_fingerprint));

        if ($date==5){
            $hitungabsen=att::where('pegawai_id','=',$pegawai_id_fingerprint)
            ->where('tanggal_att','=',$tanggal_fingerprint)
            ->count();
            
            // dd($hitungabsen);

            if ($hitungabsen>0) {

                $absen = att::where('pegawai_id', '=', $pegawai_id_fingerprint)
                    ->where('tanggal_att','=',$tanggal_fingerprint)
                    ->latest()
                    ->first();

                    //cek kecocokan jam masuk berdasarkan jadwalkerja
                    $cek = jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                        ->select('jadwalkerjas.id', 'jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal', 'rulejammasuks.jamsebelum_masukkerja')
                        ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                        ->first();
                    $jamawal = date("H:i:s", strtotime($cek->jamsebelum_masukkerja));
                    $jamakhir = $cek->jam_masukjadwal;
                    //menentukan jam toleransi masuk pegawai
                    // $jamakhir2 = date("H:i:s", strtotime("+30 minutes", strtotime($jamakhir)));
                    $jamakhir2=$jamakhir;
                    $jamfingerprint = date("H:i:s", strtotime($jam_fingerprint));
                    // dd($absen->jadwalkerja_id);


                    // if ($absen->jadwalkerja_id=="1")
                    if (($cek->sifat=="WA") && ($cek->jam_keluarjadwal > date("H:i:s", strtotime("12:00:00"))))
                    {
                        //meubah data masuk
                        $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                        ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                        ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                        ->first();
                                        //    dd($table);
                        if ($table->jam_masuk!=null)
                        {
                            $table->keluaristirahat = $jam_fingerprint;
                            $table->save();
                            return "Success";
                        }
                        else{
                            return "Success";
                        }
                        
                    }
                    else
                    {
                        return "Success";
                    }

                
            }
            else
            {
            return "Success";
            }
        }
        else{
            $table=attendancecheck::where('tanggalcheckattendance','=',$tanggal_fingerprint)
                    ->count();
            if ($table > 0)
            {
                return "Success";
            }        
            else
            {
                return "None";
            }
        }
    }

    protected function masukistirahat($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint){
        
        $date=date('N',strtotime($tanggal_fingerprint));

        if ($date==5){
            $hitungabsen=att::where('pegawai_id','=',$pegawai_id_fingerprint)
            ->where('tanggal_att','=',$tanggal_fingerprint)
            ->count();
            
            if ($hitungabsen>0) {

                $absen = att::where('pegawai_id', '=', $pegawai_id_fingerprint)
                    ->where('tanggal_att','=',$tanggal_fingerprint)
                    ->latest()
                    ->first();

                    //cek kecocokan jam masuk berdasarkan jadwalkerja
                    $cek = jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                        ->select('jadwalkerjas.id', 'jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal', 'rulejammasuks.jamsebelum_masukkerja')
                        ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                        ->first();
                    $jamawal = date("H:i:s", strtotime($cek->jamsebelum_masukkerja));
                    $jamakhir = $cek->jam_masukjadwal;
                    //menentukan jam toleransi masuk pegawai
                    // $jamakhir2 = date("H:i:s", strtotime("+30 minutes", strtotime($jamakhir)));
                    $jamakhir2=$jamakhir;
                    $jamfingerprint = date("H:i:s", strtotime($jam_fingerprint));
                    // dd($absen->jadwalkerja_id);


                    // if ($absen->jadwalkerja_id=="1")
                    if (($cek->sifat=="WA") && ($cek->jam_keluarjadwal > date("H:i:s", strtotime("12:00:00"))))
                    {
                        //meubah data masuk
                        $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                        ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                        ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                        ->first();
                                        //    dd($table);
                        if ($table->keluaristirahat!=null)
                        {
                            $table->masukistirahat = $jam_fingerprint;
                            $table->save();
                            return "Success";
                        }
                        else{
                            return "Success";
                        }
                        
                    }
                    else
                    {
                        return "Success";
                    }
            }
            else
            {
            return "Success";
            }
        }
        else{
            
            $table=attendancecheck::where('tanggalcheckattendance','=',$tanggal_fingerprint)
                    ->count();
            if ($table > 0)
            {
                return "Success";
            }        
            else
            {
                return "None";
            }
        }
    }

    protected function jam_keluar($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint){
        //cek absen jam masuk yang kosong hari ini
        $hitungabsen=att::where('pegawai_id','=',$pegawai_id_fingerprint)
        ->where('tanggal_att','=',$tanggal_fingerprint)
        ->whereNull('jam_masuk')
        ->count();
        // dd($hitungabsen);
        //ini proses data kehadiran hari ini
        if ($hitungabsen>0) {
            // dd("asd");
            //function kehadiranhariini
            //memakai variabel tanggal_fingerprint
            return $this->kehadiranhariini($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint);
        }
        else
        //ini proses hari kemarin
        {

            //pencarian absen hari ini yang jam masuk nya terisi

            $tanggalkemarin=date("Y-m-d",strtotime("-1 day",strtotime($tanggal_fingerprint)));

            // cek absen pulang yg tidak kosong kemarin
            $cekjamkeluar=att::where('pegawai_id','=',$pegawai_id_fingerprint)
                ->where('tanggal_att','=',$tanggalkemarin)
                ->whereNull('jam_keluar')
                ->whereNotNull('jam_masuk')
                ->count();

            if ($cekjamkeluar>0) {
                // function kehadiranharikemarin
                //variabel $tanggalkemarin
                return $this->kehadiranharikemarin($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$tanggalkemarin);
            }
            else{
                //pencarian absen hari ini yang jam masuk nya terisi
                // function kehadiranharikemarinfingerprint
                //memakai variabel tanggal_fingerprint
                return $this->kehadiranharikemarinfingerprint($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint);
                
                // return "Success";
            }
        }
    }

    protected function notification($instansi){
            $tambahpegawai=array();
            $datatambahpegawai=array();
            $updatefinger=array();
            $dataupdatefinger=array();
            $data=array();


            $finger=DB::raw("(SELECT pegawai_id,COUNT(pegawai_id) as finger from fingerpegawais group by pegawai_id) as fingerpegawais");
            $tanpapegawai=hapusfingerpegawai::pluck('pegawai_id')->all();
            $adminsidikjari=adminpegawai::pluck('pegawai_id')->all();

            $table=pegawai::
            leftJoin($finger,'fingerpegawais.pegawai_id','=','pegawais.id')
            ->where('instansi_id','=',$instansi)
            ->where('finger','=',2)
            ->whereNotIn('id',$tanpapegawai)
            ->whereNotIn('id',$adminsidikjari)
            ->count();

            $countlogpegawai=lograspberry::where('instansi_id','=',$instansi)
                        ->where('jumlahpegawaifinger','<',$table)
                        ->count();

            $tambahpegawai['pegawaifinger']=$countlogpegawai;

            array_push($data,$tambahpegawai);

            $countfingerupdate=historyfingerpegawai::where('instansi_id','=',$instansi)
                                ->where('statushapus','=',0)
                                ->count();
            $updatefinger['updatefinger']=$countfingerupdate;

            array_push($data,$updatefinger);

            return $data;
            // View::share('notification', $data);
    }

    public function aturanjadwalharian($periodes,$tanggal){
        // return "jalan";
        $x="";
        $tahun=date('Y');
        $bulan=date('m');
        $tanggal=date('Y-m-d', strtotime($tahun."-".$bulan."-".$tanggal));
        foreach ($periodes as $periode){
            $hitung=count($periode);

            if ($hitung > 0){
                if (($tanggal >= $periode['tanggal_awalrule']) && ($tanggal<=$periode['tanggal_akhirrule'])){

                    $x=$x.'<span class="badge '.$periode['classdata'].'">'.$periode['singkatan'].'</span>';
                    
                    // echo "Benar".$periode['tanggal_awalrule'].">".$tanggal."<".$periode['tanggal_akhirrule'];
                }
            }
            else
            {
                $x=$x."-";
            }
        }
        return $x;
    }


    //function untuk jam keluar
    //selesai -> belum dicoba
    protected function kehadiranhariini ($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint){
        $absens = att::where('pegawai_id', '=', $pegawai_id_fingerprint)
        ->where('tanggal_att', '=', $tanggal_fingerprint)
        ->whereNotNull('jam_masuk')
        ->get();
        foreach ($absens as $key => $absen) {
            //cek kecocokan jam masuk berdasarkan jadwalkerja
            // dd($absen);
            $cek = jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                ->select('jadwalkerjas.id', 'jadwalkerjas.jam_keluarjadwal', 'rulejammasuks.jamsebelum_pulangkerja')
                ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                ->get();

                $jamawal = date("H:i:s", strtotime($cek[0]['jamsebelum_pulangkerja']));
                $jamakhir = date("H:i:s", strtotime($cek[0]['jam_keluarjadwal']));
            //menentukan jam toleransi masuk pegawai
            // $jamakhir2 = date("H:i:s", strtotime("-30 minutes", strtotime($jamakhir)));
            $jamakhir2=$jamakhir;
            $jamfingerprint = date("H:i:s", strtotime($jam_fingerprint));
            // dd($jamawal."    ".$jamfingerprint."    ".$jamakhir2);
            if (($jamfingerprint >= $jamakhir2) && ($jamfingerprint <= ( $jamawal))) {

                //menghitung data absen trans pegawai
                $cari = atts_tran::where('pegawai_id', '=', $pegawai_id_fingerprint)
                    ->where('tanggal', '=', $tanggal_fingerprint)
                    ->where('status_kedatangan', '=', $status_fingerprint)
                    ->count();
                //jika hasil nya lebih dari 0 maka
                if ($cari > 0) {
                    //melakukan perubahan data absen trans yang ada
                    $table = atts_tran::where('tanggal', '=', $tanggal_fingerprint)
                        ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                        ->first();
                    $table->jam = $jam_fingerprint;
                    $table->tanggal = $tanggal_fingerprint;
                    $table->save();
                } else {
                    //menambah data absen trans yang baru
                    $save = new atts_tran();
                    $save->pegawai_id = $pegawai_id_fingerprint;
                    $save->tanggal = $tanggal_fingerprint;
                    $save->jam = $jam_fingerprint;
                    $save->lokasi_alat = $instansi_fingerprint;
                    $save->status_kedatangan = $status_fingerprint;
                    $save->save();
                }
                //meubah data masuk
                //meubah data masuk
                $table2 = jadwalkerja::where('id','=',$absen->jadwalkerja_id)
                ->get();

                if ($table2[0]['sifat']=="FD")
                {
                    $awal=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$absen->jam_masuk));
                    $akhir=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                    $akumulasi=$this->kurangwaktu($awal,$akhir);
                }
                else
                {
                    if ($table2[0]['jam_masukjadwal']>$table2[0]['jam_keluarjadwal'])
                    {
                        // dd("tes5");
                        $harike=date('N', strtotime($tanggal_fingerprint));
                        if (($harike==5) && ($table2[0]['sifat']=="WA") && ($absen->masukistirahat!=null)  && ($absen->keluaristirahat!=null))
                        {
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jam_masuk=$absen->jam_masuk;
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                            else
                            {
                                $jam_masuk=$table2[0]['jam_masukjadwal'];
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                        }
                        else
                        {

                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jamban=$absen->jam_masuk;
                                $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal']));
                                $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                            }
                            else
                            {
                                $jamban=($table2[0]['jam_masukjadwal']);
                                $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal']));
                                $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                            }
                        }
                    }
                    else{

                        $harike=date('N', strtotime($tanggal_fingerprint));
                        if (($harike==5) && ($table2[0]['sifat']=="WA") && ($absen->masukistirahat!=null)  && ($absen->keluaristirahat!=null))
                        {
                                
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jam_masuk=$absen->jam_masuk;
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                            else
                            {
                                $jam_masuk=$table2[0]['jam_masukjadwal'];
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                        }
                        else
                        {

                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jamban=$absen->jam_masuk;
                                $jamban2=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$table2[0]['jam_keluarjadwal']));
                                $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                            }
                            else
                            {
                                $jamban=($table2[0]['jam_masukjadwal']);
                                $jamban2=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$table2[0]['jam_keluarjadwal']));
                                $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                            }
                        }
                    }
                }

                $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                    ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                    ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                    ->first();

                $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                    ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();

                $table->jenisabsen_id = "1";
                $table->jam_keluar = $jam_fingerprint;
                $table->keluarinstansi_id = $instansi_fingerprint;
                $table->akumulasi_sehari = $akumulasi;
                $table->save();

                $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                    ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();

                $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();

                if (($jamfingerprint >= $jamakhir2) && ($jamfingerprint <= ( $jamawal))) {

                $status="pulang";
                }
                if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                    $class = "bg-green";
                } else {
                    $class = "bg-yellow";
                }
                $tanggalbaru = date("d-M-Y");

                event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                return "Success";
                //Bisa Pulan 3
            }
            elseif(($jamfingerprint < ( $jamakhir)))

            {
                $cari = atts_tran::where('pegawai_id', '=', $pegawai_id_fingerprint)
                    ->where('tanggal', '=', $tanggal_fingerprint)
                    ->where('status_kedatangan', '=', $status_fingerprint)
                    ->count();
                //jika hasil nya lebih dari 0 maka
                if ($cari > 0) {
                    //melakukan perubahan data absen trans yang ada
                    $table = atts_tran::where('tanggal', '=', $tanggal_fingerprint)
                        ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                        ->first();
                    $table->jam = $jam_fingerprint;
                    $table->tanggal = $tanggal_fingerprint;
                    $table->save();
                } else {
                    //menambah data absen trans yang baru
                    $save = new atts_tran();
                    $save->pegawai_id = $pegawai_id_fingerprint;
                    $save->tanggal = $tanggal_fingerprint;
                    $save->jam = $jam_fingerprint;
                    $save->lokasi_alat = $instansi_fingerprint;
                    $save->status_kedatangan = $status_fingerprint;
                    $save->save();
                }
                //meubah data masuk
            // dd($absen->jadwalkerja_id);
            // $table2 = jadwalkerja::find($absen->jadwalkerja_id)
            //     ->get();
            $table2 = jadwalkerja::where('id','=',$absen->jadwalkerja_id)
                ->get();

                if ($table2[0]['sifat']=="FD")
                {
                    // dd("FD");
                    $awal=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$absen->jam_masuk));
                    // $awal = date_create($awal);
                    // dd($awal);
                    $akhir=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                    // $akhir = date_create($akhir);
                    // dd($akhir);
                    $akumulasi=$this->kurangwaktu($akhir,$awal);
                    // dd($awal." >> ".$akhir." >> ".$akumulasi);
                }
                else
                {
                    if ($table2[0]['jam_masukjadwal']>$table2[0]['jam_keluarjadwal'])
                    {
                        $harike=date('N', strtotime($tanggal_fingerprint));
                        if (($harike==5) && ($table2[0]['sifat']=="WA") && ($absen->masukistirahat!=null) && ($absen->keluaristirahat!=null))
                        {
                            
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jam_masuk=$absen->jam_masuk;
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                            else
                            {
                                $jam_masuk=$table2[0]['jam_masukjadwal'];
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                        }
                        else
                        {

                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jamban=$absen->jam_masuk;
                                $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                                $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                            }
                            else
                            {
                                $jamban=($table2[0]['jam_masukjadwal']);
                                $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                                $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                            }
                        }
                    }
                    else{
                        $harike=date('N', strtotime($tanggal_fingerprint));
                        if (($harike==5) && ($table2[0]['sifat']=="WA") && ($absen->masukistirahat!=null) && ($absen->keluaristirahat!=null))
                        {
                                
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jam_masuk=$absen->jam_masuk;
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                            else
                            {
                                $jam_masuk=$table2[0]['jam_masukjadwal'];
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                        }
                        else
                        {
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jamban=$absen->jam_masuk;
                                $jamban2=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$jam_fingerprint));
                                $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                            }
                            else
                            {
                                $jamban=($table2[0]['jam_masukjadwal']);
                                $jamban2=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$jam_fingerprint));
                                $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                            }
                        }
                    }
                }
                
                $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                    ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                    ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                    ->first();    
            
                $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                    ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();
                   
                $table->jenisabsen_id = "1";
                $table->jam_keluar = $jam_fingerprint;
                $table->keluarinstansi_id = $instansi_fingerprint;
                $table->akumulasi_sehari = $akumulasi;
                $table->save();

                $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                    ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();

                $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();
                $status = "pulang lebih cepat";
                if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                    $class = "bg-green";
                } else {
                    $class = "bg-yellow";
                }
                $tanggalbaru = date("d-M-Y", strtotime($tanggal_fingerprint));

                event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                return "Success";
                //Pulang Cepat
            }
        }
    }


    //selesai -> belum dicoba
    protected function kehadiranharikemarin($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$tanggalkemarin){
        $absens = att::where('pegawai_id', '=', $pegawai_id_fingerprint)
            ->where('tanggal_att', '=', $tanggalkemarin)
            ->get();
            
        foreach ($absens as $key => $absen) {
            //cek kecocokan jam masuk berdasarkan jadwalkerja
            $cek = jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                ->select('jadwalkerjas.*', 'rulejammasuks.jamsebelum_pulangkerja', 'rulejammasuks.jamsebelum_masukkerja')
                ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                ->get();

            $jamawal = date("H:i:s", strtotime($cek[0]['jamsebelum_pulangkerja']));
            $jamakhir = date("H:i:s", strtotime($cek[0]['jam_keluarjadwal']));
            //menentukan jam toleransi masuk pegawai
            // $jamakhir2 = date("H:i:s", strtotime("-30 minutes", strtotime($jamakhir)));
            $jamfingerprint = date("H:i:s", strtotime($jam_fingerprint));
            $jamakhir2=$jamakhir;
            //if (($jamfingerprint >= $jamakhir2) && ($jamfingerprint <= ( $jamawal))) {
            if (($jamfingerprint >= $jamakhir) && ($jamfingerprint <= ( $jamawal))) 
            // kondisi pulang tepat waktu    
            {
                // dd("pulang tepat waktu");
                //menghitung data absen trans pegawai
                $cari = atts_tran::where('pegawai_id', '=', $pegawai_id_fingerprint)
                    ->where('tanggal', '=', $tanggal_fingerprint)
                    ->where('status_kedatangan', '=', $status_fingerprint)
                    ->count();
                //jika hasil nya lebih dari 0 maka
                if ($cari > 0) {
                    //melakukan perubahan data absen trans yang ada
                    $table = atts_tran::where('tanggal', '=', $tanggal_fingerprint)
                        ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                        ->first();
                    $table->jam = $jam_fingerprint;
                    $table->tanggal = $tanggal_fingerprint;
                    $table->save();
                } else {
                    //menambah data absen trans yang baru
                    $save = new atts_tran();
                    $save->pegawai_id = $pegawai_id_fingerprint;
                    $save->tanggal = $tanggal_fingerprint;
                    $save->jam = $jam_fingerprint;
                    $save->lokasi_alat = $instansi_fingerprint;
                    $save->status_kedatangan = $status_fingerprint;
                    $save->save();
                }
                //meubah data masuk
                $table2 = jadwalkerja::where('id','=',$absen->jadwalkerja_id)
                ->get();

                if ($table2[0]['sifat']=="FD")
                {
                    $awal=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$absen->jam_masuk));
                    $akhir=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                    $akumulasi=$this->kurangwaktu($awal,$akhir);
                }
                else
                {
                    if ($table2[0]['jam_masukjadwal']>$table2[0]['jam_keluarjadwal'])
                    {
                        $harike=date('N', strtotime($tanggalkemarin));
                        if (($harike==5) && ($table2[0]['sifat']=="WA") && ($absen->masukistirahat!=null))
                        {
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jam_masuk=$absen->jam_masuk;
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                            else
                            {
                                $jam_masuk=$table2[0]['jam_masukjadwal'];
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                        }
                        else
                        {
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jamban=$absen->jam_masuk;
                                $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal']));
                                $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                            }
                            else
                            {
                                $jamban=($table2[0]['jam_masukjadwal']);
                                $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal']));
                                $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                            }
                                
                        } 

                    }
                    else{
                        $harike=date('N', strtotime($tanggalkemarin));
                        if (($harike==5) && ($table2[0]['sifat']=="WA") && ($absen->masukistirahat!=null))
                        {
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jam_masuk=$absen->jam_masuk;
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                            else
                            {
                                $jam_masuk=$table2[0]['jam_masukjadwal'];
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                        }
                        else
                        {
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jamban=$absen->jam_masuk;
                                $jamban2=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$table2[0]['jam_keluarjadwal']));
                                $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                            }
                            else
                            {
                                $jamban=($table2[0]['jam_masukjadwal']);
                                $jamban2=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$table2[0]['jam_keluarjadwal']));
                                $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                            }
                        }
                    }
                }

                $table = att::where('tanggal_att', '=', $tanggalkemarin)
                    ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                    ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                    ->first();

                $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                    ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();
                
                    
                $table->jenisabsen_id = "1";
                $table->jam_keluar = $jam_fingerprint;
                $table->keluarinstansi_id = $instansi_fingerprint;
                $table->akumulasi_sehari = $akumulasi;
                $table->save();

                $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                    ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();

                $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();
                $status = "pulang";
                if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                    $class = "bg-green";
                } else {
                    $class = "bg-yellow";
                }
                $tanggalbaru = date("d-M-Y");

                event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                return "Success";
                //Bisa Pulang
            }
            elseif (($jamfingerprint < ( $jamakhir)))
            //kondisi pulang cepat
            {
                // dd("pulang cepat");
                $cari = atts_tran::where('pegawai_id', '=', $pegawai_id_fingerprint)
                    ->where('tanggal', '=', $tanggal_fingerprint)
                    ->where('status_kedatangan', '=', $status_fingerprint)
                    ->count();
                //jika hasil nya lebih dari 0 maka
                if ($cari > 0) {
                    //melakukan perubahan data absen trans yang ada
                    $table = atts_tran::where('tanggal', '=', $tanggal_fingerprint)
                        ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                        ->first();
                    $table->jam = $jam_fingerprint;
                    $table->tanggal = $tanggal_fingerprint;
                    $table->save();
                } else {
                    //menambah data absen trans yang baru
                    $save = new atts_tran();
                    $save->pegawai_id = $pegawai_id_fingerprint;
                    $save->tanggal = $tanggal_fingerprint;
                    $save->jam = $jam_fingerprint;
                    $save->lokasi_alat = $instansi_fingerprint;
                    $save->status_kedatangan = $status_fingerprint;
                    $save->save();
                }
                //meubah data masuk
                // dd($absen->jadwalkerja_id);
                $table2 = jadwalkerja::where('id','=',$absen->jadwalkerja_id)
                ->get();
                // dd($table2[0]['sifat']);
                if ($table2[0]['sifat']=="FD")
                {
                    // dd("FD");
                    $awal=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$absen->jam_masuk));
                    // $awal = date_create($awal);
                    // dd($awal);
                    $akhir=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                    // $akhir = date_create($akhir);
                    // dd($akhir);
                    $akumulasi=$this->kurangwaktu($akhir,$awal);
                    // dd($awal." >> ".$akhir." >> ".$akumulasi);
                }
                else
                {
                    if ($table2[0]['jam_masukjadwal']>$table2[0]['jam_keluarjadwal'])
                    {
                        $harike=date('N', strtotime($tanggalkemarin));
                        if (($harike==5) && ($table2[0]['sifat']=="WA") && ($absen->masukistirahat!=null))
                        {
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jam_masuk=$absen->jam_masuk;
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                            else
                            {
                                $jam_masuk=$table2[0]['jam_masukjadwal'];
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                        }
                        else
                        {
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jamban=$absen->jam_masuk;
                                $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                                $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                            }
                            else
                            {
                                $jamban=($table2[0]['jam_masukjadwal']);
                                $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                                $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                            }
                                
                        } 

                    }
                    else{
                        $harike=date('N', strtotime($tanggalkemarin));
                        if (($harike==5) && ($table2[0]['sifat']=="WA") && ($absen->masukistirahat!=null))
                        {
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jam_masuk=$absen->jam_masuk;
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                            else
                            {
                                $jam_masuk=$table2[0]['jam_masukjadwal'];
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                        }
                        else
                        {
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jamban=$absen->jam_masuk;
                                $jamban2=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$jam_fingerprint));
                                $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                            }
                            else
                            {
                                $jamban=($table2[0]['jam_masukjadwal']);
                                $jamban2=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$jam_fingerprint));
                                $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                            }
                        }
                    }
                }

                $table = att::where('tanggal_att', '=', $tanggalkemarin)
                    ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                    ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                    ->first();

                $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                    ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();
            
                $table->jenisabsen_id = "1";
                $table->jam_keluar = $jam_fingerprint;
                $table->keluarinstansi_id = $instansi_fingerprint;
                $table->akumulasi_sehari = $akumulasi;
                $table->save();

                $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                    ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();

                $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();
                $status = "pulang lebih cepat";
                if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                    $class = "bg-green";
                } else {
                    $class = "bg-yellow";
                }
                $tanggalbaru = date("d-M-Y");

                event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                return "Success";
                // return "Failed";
                //Pulang Cepat
            }
        }
    }

    // selesai -> belum dicoba
    protected function kehadiranharikemarinfingerprint($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint){
        $absens = att::where('pegawai_id', '=', $pegawai_id_fingerprint)
        ->where('tanggal_att', '=', $tanggal_fingerprint)
        ->whereNull('jam_keluar')
        ->whereNotNull('jam_masuk')
        ->get();
        foreach ($absens as $key => $absen) {
            //cek kecocokan jam masuk berdasarkan jadwalkerja
            // dd($absen);
            $cek = jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                ->select('jadwalkerjas.id', 'jadwalkerjas.jam_keluarjadwal', 'rulejammasuks.jamsebelum_pulangkerja')
                ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                ->get();

            $jamawal = date("H:i:s", strtotime($cek[0]['jamsebelum_pulangkerja']));
            $jamakhir = date("H:i:s", strtotime($cek[0]['jam_keluarjadwal']));
            //menentukan jam toleransi masuk pegawai
            // $jamakhir2 = date("H:i:s", strtotime("-30 minutes", strtotime($jamakhir)));
            $jamakhir2=$jamakhir;
            $jamfingerprint = date("H:i:s", strtotime($jam_fingerprint));
            // dd($jamawal."    ".$jamfingerprint."    ".$jamakhir2);
            if (($jamfingerprint >= $jamakhir2) && ($jamfingerprint <= ( $jamawal))) {

                //menghitung data absen trans pegawai
                $cari = atts_tran::where('pegawai_id', '=', $pegawai_id_fingerprint)
                    ->where('tanggal', '=', $tanggal_fingerprint)
                    ->where('status_kedatangan', '=', $status_fingerprint)
                    ->count();
                //jika hasil nya lebih dari 0 maka
                if ($cari > 0) {
                    //melakukan perubahan data absen trans yang ada
                    $table = atts_tran::where('tanggal', '=', $tanggal_fingerprint)
                        ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                        ->first();
                    $table->jam = $jam_fingerprint;
                    $table->tanggal = $tanggal_fingerprint;
                    $table->save();
                } else {
                    //menambah data absen trans yang baru
                    $save = new atts_tran();
                    $save->pegawai_id = $pegawai_id_fingerprint;
                    $save->tanggal = $tanggal_fingerprint;
                    $save->jam = $jam_fingerprint;
                    $save->lokasi_alat = $instansi_fingerprint;
                    $save->status_kedatangan = $status_fingerprint;
                    $save->save();
                }
                //meubah data masuk
                //meubah data masuk
                $table2 = jadwalkerja::where('id','=',$absen->jadwalkerja_id)
                ->get();

                if ($table2[0]['sifat']=="FD")
                {
                    $awal=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$absen->jam_masuk));
                    $akhir=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                    $akumulasi=$this->kurangwaktu($awal,$akhir);
                }
                else
                {
                    if ($table2[0]['jam_masukjadwal']>$table2[0]['jam_keluarjadwal'])
                    {
                        // dd("tes5");
                        $harike=date('N', strtotime($tanggal_fingerprint));
                        if (($harike==5) && ($table2[0]['sifat']=="WA") && ($absen->masukistirahat!=null) && ($absen->keluaristirahat!=null))
                        {
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jam_masuk=$absen->jam_masuk;
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                            else
                            {
                                $jam_masuk=$table2[0]['jam_masukjadwal'];
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                        }
                        else
                        {

                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jamban=$absen->jam_masuk;
                                $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal']));
                                $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                            }
                            else
                            {
                                $jamban=($table2[0]['jam_masukjadwal']);
                                $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal']));
                                $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                            }
                        }
                    }
                    else{
                        $harike=date('N', strtotime($tanggal_fingerprint));
                        if (($harike==5) && ($table2[0]['sifat']=="WA") && ($absen->masukistirahat!=null) && ($absen->keluaristirahat!=null))
                        {    
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jam_masuk=$absen->jam_masuk;
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                            else
                            {
                                $jam_masuk=$table2[0]['jam_masukjadwal'];
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                        }
                        else
                        {

                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jamban=$absen->jam_masuk;
                                $jamban2=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$table2[0]['jam_keluarjadwal']));
                                $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                            }
                            else
                            {
                                $jamban=($table2[0]['jam_masukjadwal']);
                                $jamban2=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$table2[0]['jam_keluarjadwal']));
                                $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                            }
                        }
                    }
                }

                $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                    ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                    ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                    ->first();

                $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                    ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();

                    // dd($table);

               
                $table->jenisabsen_id = "1";
                $table->jam_keluar = $jam_fingerprint;
                $table->keluarinstansi_id = $instansi_fingerprint;
                $table->akumulasi_sehari = $akumulasi;
                $table->save();

                $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                    ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();

                $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();

                if (($jamfingerprint >= $jamakhir2) && ($jamfingerprint <= ( $jamawal))) {
                // dd("jam awal".$jamakhir2." Jam finger ".$jamfingerprint." jam akhir".$jamawal);

                $status="pulang";
                }
                if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                    $class = "bg-green";
                } else {
                    $class = "bg-yellow";
                }
                $tanggalbaru = date("d-M-Y");

                event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                return "Success";
                //Bisa Pulan 3
            }
            elseif(($jamfingerprint < ( $jamakhir)))
            //kondisi pulang cepat            
            {
                $cari = atts_tran::where('pegawai_id', '=', $pegawai_id_fingerprint)
                    ->where('tanggal', '=', $tanggal_fingerprint)
                    ->where('status_kedatangan', '=', $status_fingerprint)
                    ->count();
                //jika hasil nya lebih dari 0 maka
                if ($cari > 0) {
                    //melakukan perubahan data absen trans yang ada
                    $table = atts_tran::where('tanggal', '=', $tanggal_fingerprint)
                        ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                        ->first();
                    $table->jam = $jam_fingerprint;
                    $table->tanggal = $tanggal_fingerprint;
                    $table->save();
                } else {
                    //menambah data absen trans yang baru
                    $save = new atts_tran();
                    $save->pegawai_id = $pegawai_id_fingerprint;
                    $save->tanggal = $tanggal_fingerprint;
                    $save->jam = $jam_fingerprint;
                    $save->lokasi_alat = $instansi_fingerprint;
                    $save->status_kedatangan = $status_fingerprint;
                    $save->save();
                }
                //meubah data masuk
            
                $table2 = jadwalkerja::find($absen->jadwalkerja_id)
                ->get();

                if ($table2[0]['sifat']=="FD")
                {
                    // dd("FD");
                    $awal=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$absen->jam_masuk));
                    // $awal = date_create($awal);
                    // dd($awal);
                    $akhir=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                    // $akhir = date_create($akhir);
                    // dd($akhir);
                    $akumulasi=$this->kurangwaktu($akhir,$awal);
                    // dd($awal." >> ".$akhir." >> ".$akumulasi);
                }
                else
                {
                    if ($table2[0]['jam_masukjadwal']>$table2[0]['jam_keluarjadwal'])
                    {
                        $harike=date('N', strtotime($tanggal_fingerprint));
                        if (($harike==5) && ($table2[0]['sifat']=="WA") && ($absen->masukistirahat!=null) && ($absen->keluaristirahat!=null) )
                        {
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jam_masuk=$absen->jam_masuk;
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                            else
                            {
                                $jam_masuk=$table2[0]['jam_masukjadwal'];
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                        }
                        else
                        {
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jamban=$absen->jam_masuk;
                                $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                                $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                            }
                            else
                            {
                                $jamban=($table2[0]['jam_masukjadwal']);
                                $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                                $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                            }
                        }
                    }
                    else{
                        $harike=date('N', strtotime($tanggal_fingerprint));
                        if (($harike==5) && ($table2[0]['sifat']=="WA") && ($absen->masukistirahat!=null) && ($absen->keluaristirahat!=null))
                        {
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jam_masuk=$absen->jam_masuk;
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                            else
                            {
                                $jam_masuk=$table2[0]['jam_masukjadwal'];
                                $keluaristirahat=$absen->keluaristirahat;
                                $masukistirahat=$absen->masukistirahat;
                                if ($jamfingerprint < date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']))){
                                    $jam_keluar=$jam_fingerprint;
                                }
                                else{
                                    $jam_keluar=$table2[0]['jam_keluarjadwal'];
                                }
                                $akumulasi1=$this->kurangwaktu($keluaristirahat,$jam_masuk);
                                $akumulasi2=$this->kurangwaktu($jam_keluar,$masukistirahat);
                                $akumulasi=date("H:i:s",strtotime($this->tambahwaktu($akumulasi1,$akumulasi2)));
                            }
                        }
                        else
                        {
                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jamban=$absen->jam_masuk;
                                $jamban2=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$jam_fingerprint));
                                $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                            }
                            else
                            {
                                $jamban=($table2[0]['jam_masukjadwal']);
                                $jamban2=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$jam_fingerprint));
                                $akumulasi=$this->kurangwaktu($jamban2,$jamban);
                            }
                        }
                    }
                }
               
                $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                    ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                    ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                    ->first();    
            
                $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                    ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();
                    
                $table->jenisabsen_id = "1";
                $table->jam_keluar = $jam_fingerprint;
                $table->keluarinstansi_id = $instansi_fingerprint;
                $table->akumulasi_sehari = $akumulasi;
                $table->save();

                $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                    ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();

                $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();
                $status = "pulang lebih cepat";
                if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                    $class = "bg-green";
                } else {
                    $class = "bg-yellow";
                }
                $tanggalbaru = date("d-M-Y", strtotime($tanggalkemarin));

                event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                return "Success";
                //Pulang Cepat
            }
        }
    }

}
