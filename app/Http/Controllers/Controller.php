<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\rekapbulanan;
use Carbon\Carbon;
use App\att;
use App\dokter;
use App\atts_tran;
use App\log_att_tran;
use App\macaddresse;
use App\instansi;
use App\jadwalkerja;
use App\pegawai;
use App\rulejadwalpegawai;
use App\rulejammasuk;
use App\perawatruangan;
use App\adminpegawai;
use App\hapusfingerpegawai;
use App\lograspberry;
use App\historyfingerpegawai;
use App\logattendance;
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

    public function difftanggal($base, $toadd) {
        date_default_timezone_set('Asia/Makassar');
        $toadd = date_create($toadd);
        $base = date_create($base);
        $diff=date_diff($toadd,$base);
        // return $diff->format("%d");
        return $diff->days;
    }

    public static function difftanggal2($base, $toadd) {
        date_default_timezone_set('Asia/Makassar');
        $toadd = date_create($toadd);
        $base = date_create($base);
        $diff=date_diff($toadd,$base);
        // return $diff->format("%d");
        return $diff->days;
    }

    public static function diffbulan($base, $toadd) {
        date_default_timezone_set('Asia/Makassar');
        $toadd = date_create($toadd);
        $base = date_create($base);
        $diff=date_diff($toadd,$base);

        return $diff->m;
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


    public function store_atts_trans($pegawai_id_fingerprint,
                                    $tanggal_fingerprint,
                                    $jam_fingerprint,
                                    $status_fingerprint,
                                    $instansi_fingerprint,
                                    $macadress_fingerprint,
                                    $flag)
    {
        if ($flag)
        {
            $save = new atts_tran();
            $save->pegawai_id = $pegawai_id_fingerprint;
            $save->tanggal = $tanggal_fingerprint;
            $save->jam = $jam_fingerprint;
            $save->lokasi_alat = $instansi_fingerprint;
            $save->status_kedatangan = $status_fingerprint;
            $save->save();
        }
        

       
            $data= new log_att_tran();
            $data->pegawai_id=$pegawai_id_fingerprint;
            $data->instansi_id=$instansi_fingerprint;
            $data->tanggal=$tanggal_fingerprint;
            $data->jam=$jam_fingerprint;
            $data->status= $status_fingerprint;
            $data->flag=$flag;
            $data->expire=false;
            
            $datamacaddress=macaddresse::where('macaddress','=',strtoupper($macadress_fingerprint))
                                        ->first();
            $data->macaddress_id=$datamacaddress->id;

            if ($datamacaddress->instansi_id == null)
            {
                $datamacaddress->instansi_id= $instansi_fingerprint;
                $datamacaddress->save();
            }

            $data->save();

            $checkdata = log_att_tran::where('expire','=',false);

            if ($checkdata->count() >= 50000)
            {
                // $checkdata->limit(50000)->delete();
                foreach ($checkdata->get() as $key => $data)
                {
                    $changedata=log_att_tran::where('id','=',$data->id)
                                            ->first();
                    $changedata->expire=true;
                    $changedata->save();
                }
            }
            

    }

    private function absensiberlanjut($pegawai_id_fingerprint,$tanggal_keluar,$jam_keluarjadwal,$tanggal_fingerprint,$jam_fingerprint,$instansi_fingerprint)
    {

        $table2=att::leftjoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                                            ->where('atts.tanggal_att','=',$tanggal_keluar)
                                            ->where('atts.pegawai_id','=',$pegawai_id_fingerprint)
                                            ->whereNull('atts.jam_masuk')
                                            ->whereNull('atts.jam_keluar')
                                            ->whereNotNull('atts.tanggal_keluar')
                                            ->where('jadwalkerjas.sifat','!=','FD')
                                            ->first();
        
        $table=att::where('tanggal_att','=',$tanggal_keluar)
                                            ->where('pegawai_id','=',$pegawai_id_fingerprint)
                                            ->whereNull('jam_masuk')
                                            ->whereNull('jam_keluar')
                                            ->whereNotNull('tanggal_keluar')
                                            // ->where('jadwalkerjas.sifat','!=','FD')
                                            ->first();
        // dd($table2->jam_masukjadwal." == ".$jam_keluarjadwal);
        // dd(($table2->jam_masukjadwal == $jam_keluarjadwal) && ($jam_fingerprint >= $jam_keluarjadwal));
        if (($table2->jam_masukjadwal == $jam_keluarjadwal) && ($jam_fingerprint >= $jam_keluarjadwal))
        {
            $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
            ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();


            $table->keteranganmasuk_id="1";


            if (($pegawai[0]['instansi_id']==$instansi_fingerprint) || ($table->jenisabsen_id==2))
            {
                $table->jenisabsen_id = "1";
            }else {
                // $table->jenisabsen_id = "8";
                $table->jenisabsen_id = "1";
            }

            $table->apel="0";
            $table->terlambat="00:00:00";
            $table->jam_masuk = $jam_keluarjadwal;
            $table->tanggal_att = $tanggal_fingerprint;
            $table->masukinstansi_id = $instansi_fingerprint;

            return $table->save();
        }

    }

    protected function jam_masuk($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint)
    {
        
         
            $pegawaitidakterjadwal_exist=dokter::where('pegawai_id','=',$pegawai_id_fingerprint)->first();
            
            if ($pegawaitidakterjadwal_exist != null)
            {

                $checkatt=att::where('pegawai_id', '=', $pegawai_id_fingerprint)
                            ->where('tanggal_att','=',$tanggal_fingerprint)
                            ->whereNull('jam_keluar')
                            ->whereNotNull('jam_masuk')        
                            // ->oldest()
                            // ->first();
                            ->count();
                // dd($checkatt);
                if ($checkatt==0)
                {
                    $jadwalkerja=jadwalkerja::where('sifat','=','FD')
                                    ->first();
                    $att = new att();
                    $att->pegawai_id = $pegawai_id_fingerprint;
                    $att->tanggal_att=$tanggal_fingerprint;
                    $att->terlambat='00:00:00';
                    $att->jadwalkerja_id=$jadwalkerja->id;
                    $att->jenisabsen_id = '13';
                    $att->akumulasi_sehari='00:00:00';
                    $att->save();
                }
                else
                {

                }

            }

            $absens = att::where('pegawai_id', '=', $pegawai_id_fingerprint)
                        ->where('tanggal_att','=',$tanggal_fingerprint)
                        ->whereNull('jam_keluar')
                        ->whereNull('jam_masuk')        
                        // ->oldest()
                        // ->first();
                        ->get();


            foreach ($absens as $key=>$absen)
            {
                //dd($absen->jadwalkerja_id);
                //cek kecocokan jam masuk berdasarkan jadwalkerja
                $cek = jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                    ->select('jadwalkerjas.id', 'jadwalkerjas.sifat' , 'jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal', 'rulejammasuks.jamsebelum_masukkerja')
                    ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                    ->withTrashed()

                    ->first();
                $cekabsen= jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                    ->select('jadwalkerjas.id', 'jadwalkerjas.sifat' , 'jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal', 'rulejammasuks.jamsebelum_masukkerja')
                    ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                    ->withTrashed()
                    ->count();
                
                if ($cekabsen==0)
                {
                    $flag=false;
                    $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);
                                    
                    return "Failed";
                }
                

                $jammasukjadwal = $cek->jam_masukjadwal;
                $jamsebelummasukkerja=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$cek->jamsebelum_masukkerja));
                $jamkeluarjadwal=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$cek->jam_keluarjadwal));
                $jamkeluarjadwalh_plus_1=date("Y-m-d H:i:s", strtotime("+1 day",strtotime($jamkeluarjadwal)));
                $jamfingerprint=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                // dd($jamfingerprint);
                // dd($jamkeluarjadwalh_plus_1);


                if (($jamfingerprint >= $jamsebelummasukkerja) && ($jamfingerprint<=$jamkeluarjadwal)) {
                    $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                            ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                            ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                            ->where('id','=',$absen->id)                            
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

                        $table->keteranganmasuk_id="1";

                        if ($jam_fingerprint>$jammasukjadwal)
                        {
                            $terlambatnya=$this->kurangwaktu($jam_fingerprint,$jammasukjadwal);
                            $table->apel="0";

                            if ($cek->sifat=="FD"){

                                $terlambatnya="00:00:00";
                            }

                        }
                        else {
                            if ($cek->sifat=="WA"){
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


                        $flag=true;
                        $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);


                        $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();

                        if ($jam_fingerprint>$jammasukjadwal){

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
                        $tanggalbaru=date("d-F-Y",strtotime($tanggal_fingerprint));

                        event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));

                        $att=new logattendance();
                        $att->pegawai_id=$pegawai_id_fingerprint;
                        $att->instansi_id=$instansi_fingerprint;
                        $att->tanggal=$tanggal_fingerprint;
                        $att->function="jam_masuk";
                        $att->status="Success3";
                        $att->save();

                        return "Success";
                        //bisadatang

                } 
                elseif (($jamfingerprint >= $jamsebelummasukkerja) && ($jamfingerprint<=$jamkeluarjadwalh_plus_1)) 
                {

                    $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                            ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                            ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                            ->where('id','=',$absen->id)                            
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

                        $table->keteranganmasuk_id="1";

                        if ($jam_fingerprint>$jammasukjadwal)
                        {
                            $terlambatnya=$this->kurangwaktu($jam_fingerprint,$jammasukjadwal);
                            $table->apel="0";

                            if ($cek->sifat=="FD"){

                                $terlambatnya="00:00:00";
                            }

                        }
                        else {
                            if ($cek->sifat=="WA"){
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


                        $flag=true;
                        $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);


                        $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();

                        if ($jam_fingerprint>$jammasukjadwal){

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
                        $tanggalbaru=date("d-F-Y",strtotime($tanggal_fingerprint));

                        event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));

                        $att=new logattendance();
                        $att->pegawai_id=$pegawai_id_fingerprint;
                        $att->instansi_id=$instansi_fingerprint;
                        $att->tanggal=$tanggal_fingerprint;
                        $att->function="jam_masuk";
                        $att->status="Success3";
                        $att->save();

                        return "Success";
                        //bisadatang
                }
                
            }
            
            $flag=false;
            $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);
                    
            return ("Success");    
        
        
    }

    protected function jam_masuk_tanpaapel($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint)
    {
        $table=attendancecheck::where('tanggalcheckattendance','=',$tanggal_fingerprint)
                    ->count();
        //dd("sd");
        if ($table > 0)
        {
         
            $pegawaitidakterjadwal_exist=dokter::where('pegawai_id','=',$pegawai_id_fingerprint)->first();
            
            if ($pegawaitidakterjadwal_exist != null)
            {

                $checkatt=att::where('pegawai_id', '=', $pegawai_id_fingerprint)
                            ->where('tanggal_att','=',$tanggal_fingerprint)
                            ->whereNull('jam_keluar')
                            ->whereNotNull('jam_masuk')        
                            // ->oldest()
                            // ->first();
                            ->count();
                // dd($checkatt);
                if ($checkatt==0)
                {
                    $jadwalkerja=jadwalkerja::where('sifat','=','FD')
                                    ->first();
                    $att = new att();
                    $att->pegawai_id = $pegawai_id_fingerprint;
                    $att->tanggal_att=$tanggal_fingerprint;
                    $att->terlambat='00:00:00';
                    $att->jadwalkerja_id=$jadwalkerja->id;
                    $att->jenisabsen_id = '13';
                    $att->akumulasi_sehari='00:00:00';
                    $att->save();
                }
                else
                {

                }

            }

            $absens = att::where('pegawai_id', '=', $pegawai_id_fingerprint)
                        ->where('tanggal_att','=',$tanggal_fingerprint)
                        ->whereNull('jam_keluar')
                        ->whereNull('jam_masuk')        
                        // ->oldest()
                        // ->first();
                        ->get();


            foreach ($absens as $key=>$absen)
            {
                //dd($absen->jadwalkerja_id);
                //cek kecocokan jam masuk berdasarkan jadwalkerja
                $cek = jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                    ->select('jadwalkerjas.id', 'jadwalkerjas.sifat' , 'jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal', 'rulejammasuks.jamsebelum_masukkerja')
                    ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                    ->withTrashed()

                    ->first();
                $cekabsen= jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                    ->select('jadwalkerjas.id', 'jadwalkerjas.sifat' , 'jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal', 'rulejammasuks.jamsebelum_masukkerja')
                    ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                    ->withTrashed()
                    ->count();
                
                if ($cekabsen==0)
                {
                    $flag=false;
                    $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);
                                    
                    return "Failed";
                }
                

                $jammasukjadwal = $cek->jam_masukjadwal;
                $jamsebelummasukkerja=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$cek->jamsebelum_masukkerja));
                $jamkeluarjadwal=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$cek->jam_keluarjadwal));
                $jamkeluarjadwalh_plus_1=date("Y-m-d H:i:s", strtotime("+1 day",strtotime($jamkeluarjadwal)));
                $jamfingerprint=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                // dd($jamfingerprint);
                // dd($jamkeluarjadwalh_plus_1);


                if (($jamfingerprint >= $jamsebelummasukkerja) && ($jamfingerprint<=$jamkeluarjadwal)) {
                    $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                            ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                            ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                            ->where('id','=',$absen->id)                            
                            ->first();
                            
                        $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                            ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();
                        // if (($pegawai[0]['instansi_id']==$instansi_fingerprint) || ($table->jenisabsen_id==2))
                        // {
                        //     $table->jenisabsen_id = "1";
                        // }else {
                        //     // $table->jenisabsen_id = "8";
                        //     $table->jenisabsen_id = "1";
                        // }

                        $table->keteranganmasuk_id="1";

                        if ($jam_fingerprint>$jammasukjadwal)
                        {
                            $terlambatnya=$this->kurangwaktu($jam_fingerprint,$jammasukjadwal);
                            $table->apel="0";

                            if ($cek->sifat=="FD"){

                                $terlambatnya="00:00:00";
                            }

                        }
                        else {
                            if ($cek->sifat=="WA"){
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


                        $flag=true;
                        $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);


                        $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();

                        if ($jam_fingerprint>$jammasukjadwal){

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
                        $tanggalbaru=date("d-F-Y",strtotime($tanggal_fingerprint));

                        event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));

                        $att=new logattendance();
                        $att->pegawai_id=$pegawai_id_fingerprint;
                        $att->instansi_id=$instansi_fingerprint;
                        $att->tanggal=$tanggal_fingerprint;
                        $att->function="jam_masuk";
                        $att->status="Success3";
                        $att->save();

                        return "Success";
                        //bisadatang

                } 
                elseif (($jamfingerprint >= $jamsebelummasukkerja) && ($jamfingerprint<=$jamkeluarjadwalh_plus_1)) 
                {

                    $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                            ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                            ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                            ->where('id','=',$absen->id)                            
                            ->first();
                            
                        $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                            ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();
                        // if (($pegawai[0]['instansi_id']==$instansi_fingerprint) || ($table->jenisabsen_id==2))
                        // {
                        //     $table->jenisabsen_id = "1";
                        // }else {
                        //     // $table->jenisabsen_id = "8";
                        //     $table->jenisabsen_id = "1";
                        // }

                        $table->keteranganmasuk_id="1";

                        if ($jam_fingerprint>$jammasukjadwal)
                        {
                            $terlambatnya=$this->kurangwaktu($jam_fingerprint,$jammasukjadwal);
                            $table->apel="0";

                            if ($cek->sifat=="FD"){

                                $terlambatnya="00:00:00";
                            }

                        }
                        else {
                            if ($cek->sifat=="WA"){
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


                        $flag=true;
                        $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);


                        $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();

                        if ($jam_fingerprint>$jammasukjadwal){

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
                        $tanggalbaru=date("d-F-Y",strtotime($tanggal_fingerprint));

                        event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));

                        $att=new logattendance();
                        $att->pegawai_id=$pegawai_id_fingerprint;
                        $att->instansi_id=$instansi_fingerprint;
                        $att->tanggal=$tanggal_fingerprint;
                        $att->function="jam_masuk";
                        $att->status="Success3";
                        $att->save();

                        return "Success";
                        //bisadatang
                }
                
            }
            
            $flag=false;
            $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);
                    
            return ("Success");
        }        
        else
        {
            
                    
            return "Failed";
        }
        
    }

    protected function keluaristirahat($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint){
     
                
            $date=date('N',strtotime($tanggal_fingerprint));

            if ($date==5){
                $hitungabsen=att::where('pegawai_id','=',$pegawai_id_fingerprint)
                ->where('tanggal_att','=',$tanggal_fingerprint)
                ->whereNotNull('jam_masuk')
                ->count();
                
                // dd($hitungabsen);

                $table=attendancecheck::where('tanggalcheckattendance','=',$tanggal_fingerprint)
                        ->count();

                if ($table > 0)
                {

                    if ($hitungabsen>0) {
                        $absens = att::where('pegawai_id', '=', $pegawai_id_fingerprint)
                            ->where('tanggal_att', '=', $tanggal_fingerprint)
                            ->whereNotNull('jam_masuk')
                            ->whereNull('keluaristirahat')
                            ->whereNull('jam_keluar')
                            ->get();

                        foreach ($absens as $key => $absen)
                        {
                            //cek kecocokan jam masuk berdasarkan jadwalkerja
                            $cek = jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                                ->select('jadwalkerjas.id', 'jadwalkerjas.jam_masukjadwal','jadwalkerjas.jam_keluarjadwal', 'rulejammasuks.jamsebelum_masukkerja')
                                ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                                ->first();
                            $jamawal = date("H:i:s", strtotime($cek->jamsebelum_masukkerja));
                            $jammasukjadwal = date("H:i:s", strtotime($cek->jam_masukjadwal));
                            
                            $jamfingerprint = date("H:i:s", strtotime($jam_fingerprint));


                            if (($cek->sifat=="WA") && ($cek->jam_keluarjadwal > date("H:i:s", strtotime("11:30:00"))))
                            {
                                
                                //meubah data masuk
                                $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                                ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                                ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                                ->first();
                                                //    dd($table);
                                $batasawal=date("H:i:s",strtotime("11:30:00"));
                                $jam_fingerprint=date("H:i:s",strtotime($jam_fingerprint));
                                
                                    if ($jam_fingerprint>=$batasawal && $jam_fingerprint < date("H:i:s",strtotime("14:00:00"))){
                                        $table->keluaristirahat = $batasawal;
                                        $table->save();

                                        $flag=true;
                                        $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);


                                        $att=new logattendance();
                                        $att->pegawai_id=$pegawai_id_fingerprint;
                                        $att->instansi_id=$instansi_fingerprint;
                                        $att->tanggal=$tanggal_fingerprint;
                                        $att->function="keluaristirahat";
                                        $att->status="Success";
                                        $att->save();

                                        return "Success";
                                    }
                                    else
                                    {
                                        $table->keluaristirahat = $jam_fingerprint;
                                        $table->save();
                                        
                                        $flag=true;
                                        $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);


                                        $att=new logattendance();
                                        $att->pegawai_id=$pegawai_id_fingerprint;
                                        $att->instansi_id=$instansi_fingerprint;
                                        $att->tanggal=$tanggal_fingerprint;
                                        $att->function="keluaristirahat";
                                        $att->status="Success2";
                                        $att->save();

                                        return "Success";
                                    }
                        
                                
                            }
                            else
                            {
                                $flag=false;
                                $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);

                                $att=new logattendance();
                                $att->pegawai_id=$pegawai_id_fingerprint;
                                $att->instansi_id=$instansi_fingerprint;
                                $att->tanggal=$tanggal_fingerprint;
                                $att->function="keluaristirahat";
                                $att->status="Success4";
                                $att->save();

                                return "Success";
                            }
                        }

                        $flag=false;
                        $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);
                                
                        return ("Success");
                    }
                    else
                    {
                        $flag=false;
                        $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);


                        $att=new logattendance();
                        $att->pegawai_id=$pegawai_id_fingerprint;
                        $att->instansi_id=$instansi_fingerprint;
                        $att->tanggal=$tanggal_fingerprint;
                        $att->function="keluaristirahat";
                        $att->status="Success5";
                        $att->save();
                        return "Success";
                    }
                }        
                else
                {
                   
                    return "Failed";
                }
            }
            else{
                $table=attendancecheck::where('tanggalcheckattendance','=',$tanggal_fingerprint)
                        ->count();
                if ($table > 0)
                {
                    $flag=false;
                    $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);

                    $att=new logattendance();
                    $att->pegawai_id=$pegawai_id_fingerprint;
                    $att->instansi_id=$instansi_fingerprint;
                    $att->tanggal=$tanggal_fingerprint;
                    $att->function="keluaristirahat";
                    $att->status="Success6";
                    $att->save();
                    return "Success";
                }        
                else
                {
                 
                    return "Failed";
                }
            }
        
    }

    protected function masukistirahat($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint){
        
        $table=attendancecheck::where('tanggalcheckattendance','=',$tanggal_fingerprint)
                    ->count();
        //dd("sd");
        if ($table > 0)
        {

            $date=date('N',strtotime($tanggal_fingerprint));

            if ($date==5){
                $hitungabsen=att::where('pegawai_id','=',$pegawai_id_fingerprint)
                ->where('tanggal_att','=',$tanggal_fingerprint)
                ->count();
                
                $absens = att::where('pegawai_id', '=', $pegawai_id_fingerprint)
                            ->where('tanggal_att', '=', $tanggal_fingerprint)
                            ->whereNotNull('jam_masuk')
                            ->whereNotNull('keluaristirahat')
                            ->whereNull('jam_keluar')
                            ->get();

                    foreach ($absens as $key => $absen)
                    {
                        if ($hitungabsen>0) {

                            // $absen = att::where('pegawai_id', '=', $pegawai_id_fingerprint)
                            //     ->where('tanggal_att','=',$tanggal_fingerprint)
                            //     ->latest()
                            //     ->first();
            
                            
            
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
                                    $batasawal=date("H:i:s",strtotime("13:00:00"));
                                    $batasakhir=date("H:i:s",strtotime("14:00:00"));
                                    $jam_fingerprint=date("H:i:s",strtotime($jam_fingerprint));
            
                                    if ($table->keluaristirahat!=null)
                                    {
                                        if (($jam_fingerprint>=$batasawal) && ($jam_fingerprint<=$batasakhir))
                                        {
                                            $table->masukistirahat = $batasakhir;
                                            $table->save();
        
                                            $flag=true;
                                            $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);

            
                                            $att=new logattendance();
                                            $att->pegawai_id=$pegawai_id_fingerprint;
                                            $att->instansi_id=$instansi_fingerprint;
                                            $att->tanggal=$tanggal_fingerprint;
                                            $att->function="masukistirahat";
                                            $att->status="Success";
                                            $att->save();
            
                                            return "Success";
                                        }
                                        else
                                        {
                                            $table->masukistirahat = $jam_fingerprint;
                                            $table->save();
        
                                            $flag=true;
                                            $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);

                                            $att=new logattendance();
                                            $att->pegawai_id=$pegawai_id_fingerprint;
                                            $att->instansi_id=$instansi_fingerprint;
                                            $att->tanggal=$tanggal_fingerprint;
                                            $att->function="masukistirahat";
                                            $att->status="Success2";
                                            $att->save();
            
                                            return "Success";
                                        }
                                        
                                    }
            
                                    else{
                                        $flag=false;
                                        $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);

                                        $att=new logattendance();
                                        $att->pegawai_id=$pegawai_id_fingerprint;
                                        $att->instansi_id=$instansi_fingerprint;
                                        $att->tanggal=$tanggal_fingerprint;
                                        $att->function="masukistirahat";
                                        $att->status="Success3";
                                        $att->save();
            
                                        return "Success";
                                    }
                                    
                                }
                                else
                                {
                                    $flag=false;
                                    $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);

                                    $att=new logattendance();
                                    $att->pegawai_id=$pegawai_id_fingerprint;
                                    $att->instansi_id=$instansi_fingerprint;
                                    $att->tanggal=$tanggal_fingerprint;
                                    $att->function="masukistirahat";
                                    $att->status="Success4";
                                    $att->save();
                                    return "Success";
                                }
                        }
                        else
                        {
                            $flag=false;
                            $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);

                            $att=new logattendance();
                            $att->pegawai_id=$pegawai_id_fingerprint;
                            $att->instansi_id=$instansi_fingerprint;
                            $att->tanggal=$tanggal_fingerprint;
                            $att->function="masukistirahat";
                            $att->status="Success5";
                            $att->save();
                            return "Success";
                        }
                    }

                    $flag=false;
                    $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);
                            
                    return ("Success");
                
            }
            else{
                
                $table=attendancecheck::where('tanggalcheckattendance','=',$tanggal_fingerprint)
                        ->count();
                if ($table > 0)
                {
                    $flag=false;
                    $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);

                    $att=new logattendance();
                    $att->pegawai_id=$pegawai_id_fingerprint;
                    $att->instansi_id=$instansi_fingerprint;
                    $att->tanggal=$tanggal_fingerprint;
                    $att->function="masukistirahat";
                    $att->status="Success6";
                    $att->save();
                    return "Success";
                }        
                else
                {
                   
                    return "Failed";
                }
            }
        }
        else
        {
            return "Failed";
        }
    }

    protected function jam_keluar($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint){
        //cek absen jam masuk yang kosong hari ini
        
       
            return $this->kehadiranpulang($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint);

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

    protected function kehadiranpulang($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint)
    {

        $pegawaitidakterjadwal_exist=dokter::where('pegawai_id','=',$pegawai_id_fingerprint)->first();

        // dd($pegawaitidakterjadwal_exist == null);
        
        if ($pegawaitidakterjadwal_exist == null)
        {
            // pegawai yang terjadwal
            $absens= att::leftjoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            ->where('atts.pegawai_id', '=', $pegawai_id_fingerprint)
            ->where('atts.tanggal_keluar', '=', $tanggal_fingerprint)
            // ->where('atts.jenisabsen_id', '=', '2')
            ->select('atts.*','jadwalkerjas.jenis_jadwal',
                    'jadwalkerjas.jam_masukjadwal',
                    'jadwalkerjas.jam_keluarjadwal',
                    'jadwalkerjas.sifat',
                    'jadwalkerjas.lewathari'
            )
            ->whereNotNull('atts.jam_masuk')
            ->whereNull('atts.jam_keluar')
            ->oldest()
            ->get();
        } 
        else
        {
            $absens = att::leftjoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            ->where('atts.pegawai_id', '=', $pegawai_id_fingerprint)
            // ->where('atts.jenisabsen_id', '=', '2')
            ->select('atts.*','jadwalkerjas.jenis_jadwal',
                    'jadwalkerjas.jam_masukjadwal',
                    'jadwalkerjas.jam_keluarjadwal',
                    'jadwalkerjas.sifat',
                    'jadwalkerjas.lewathari'
            )
            ->whereNotNull('atts.jam_masuk')
            ->whereNull('atts.jam_keluar')
            ->whereNull('atts.tanggal_keluar')
            ->oldest()
            ->get();
        }
            // dd($absens);
            // dd($absens);
            foreach ($absens as $key => $absen) {
                //cek kecocokan jam masuk berdasarkan jadwalkerja
                $cek = jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                    ->select('jadwalkerjas.*', 'rulejammasuks.jamsebelum_pulangkerja', 'rulejammasuks.jamsebelum_masukkerja')
                    ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                    ->get();
                
                $jamawal=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$cek[0]['jamsebelum_masukkerja']));
                $jamakhir=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$cek[0]['jamsebelum_pulangkerja']));
                $jammasuk=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$cek[0]['jam_masukjadwal']));
                $jampulang=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$cek[0]['jam_keluarjadwal']));
   
                $jamfingerprint = date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                $jammasukdatabase=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$absen->jam_masuk));
                $jamakhir2=$jamakhir;


                if ($pegawaitidakterjadwal_exist==null)
                {
                    // dd(($jamfingerprint > $jammasuk) && ($jamfingerprint <= $jamakhir));
                    if (($jamfingerprint > $jammasuk) && ($jamfingerprint <= $jamakhir))
                    // kondisi pulang tepat waktu    
                    {
                        // dd("ad");
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
                            $harike=date('N', strtotime($absen->tanggal_att));
                            // if (($harike==5) && ($table2[0]['sifat']=="WA") && ($absen->masukistirahat!=null))
                            $cekperawatruangan=perawatruangan::where('pegawai_id','=',$pegawai_id_fingerprint)->first();
                            if (($harike==5) && ($table2[0]['jam_keluarjadwal']>="14:00:00") && ($table2[0]['jam_masukjadwal']<="11:30:00") && ($cekperawatruangan==null))                        
                            {
                            

                                if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                                {
                                    $jamban=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$absen->jam_masuk));                                                                                                                                
                                    $jam_fingerprint=date("H:i:s",strtotime($jam_fingerprint));
                                    $batasakhir=date("H:i:s",strtotime("11:30:00"));
                                    $batasakhir2=date("H:i:s",strtotime("14:00:00"));

                                    if ($jam_fingerprint < $batasakhir){
                                        $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                                        $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                        // dd("halilintar");

                                    }
                                    elseif ($jam_fingerprint < $batasakhir2)
                                    {
                                        $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$batasakhir));
                                        $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                        // dd("aha");
                                        
                                    }
                                    else
                                    {
                                        $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal']));
                                        $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                        // dd("kurang");
                                        $akumulasi=date("H:i:s", strtotime("-2 hours -30 minutes",strtotime($akumulasi)));
                                    }
                                    
                                    // $jamban2=date("Y-m-d H:i:s", strtotime("-2 hours 30 minutes",strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal'])));

                                    // $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                }
                                else
                                {
                                    $jamban=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$table2[0]['jam_masukjadwal']));
                                    $jam_fingerprint=date("H:i:s",strtotime($jam_fingerprint));
                                    $batasakhir=date("H:i:s",strtotime("11:30:00"));
                                    $batasakhir2=date("H:i:s",strtotime("14:00:00"));

                                    if ($jam_fingerprint < $batasakhir){
                                        $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                                        $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                        // dd("halilintar1");

                                    }
                                    elseif ($jam_fingerprint < $batasakhir2)
                                    {
                                        $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$batasakhir));
                                        $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                        // dd("halilintar2");
                                        // dd($akumulasi);

                                    }
                                    else
                                    {
                                        $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal']));
                                        $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                        $akumulasi=date("H:i:s", strtotime("-2 hours -30 minutes",strtotime($akumulasi)));
                                        // dd($akumulasi);
                                        // dd($jamban." >> ".$jamban2);

                                    }
                                    // $jamban2=date("Y-m-d H:i:s", strtotime("-2 hours 30 minutes",strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal'])));
                                    // $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                }
                            }
                            else
                            {
                                if ($jamfingerprint < $jampulang)
                                {
                                    $akumulasi=$this->kurangwaktu($jamfingerprint,$jammasuk);
                                }
                                else
                                {
                                    $akumulasi=$this->kurangwaktu($jammasuk,$jampulang);
                                }
                                
                            }
                            
                        }
                        // dd($jamfingerprint." < ".$jampulang);
                        // dd($akumulasi);

                        $table = att::where('tanggal_att', '=', $absen->tanggal_att)
                            ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                            ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                            ->where('id','=',$absen->id)                    
                            ->first();
                        // dd($table);

                        $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                            ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();
                        
                        if (($table->jenisabsen_id=="2") || ($table->jenisabsen_id=="13")){
                            $table->jenisabsen_id = "1";
                        }
                        $table->keterangankeluar_id="1";
                        // dd($akumulasi);
                        $table->jam_keluar = $jam_fingerprint;
                        $table->keluarinstansi_id = $instansi_fingerprint;
                        $table->akumulasi_sehari = $akumulasi;
                        $table->save();


                        $cekabsensambung=att::leftjoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                                            ->where('atts.tanggal_att','=',$table->tanggal_keluar)
                                            ->where('atts.pegawai_id','=',$pegawai_id_fingerprint)
                                            ->whereNull('atts.jam_masuk')
                                            ->whereNull('atts.jam_keluar')
                                            ->whereNotNull('atts.tanggal_keluar')
                                            ->where('jadwalkerjas.sifat','!=','FD');
                        // dd($cekabsensambung->count());
                        if ($cekabsensambung->count() > 0)
                        {
                            $absensiberlanjut=$this->absensiberlanjut($pegawai_id_fingerprint,$table->tanggal_keluar,$table2[0]['jam_keluarjadwal'],$tanggal_fingerprint,$jam_fingerprint,$instansi_fingerprint);
                        }


                        $flag=true;
                        $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);


                        $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                            ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();

                        $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();
                        $status = "pulang";
                        if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                            $class = "bg-green";
                        } else {
                            $class = "bg-yellow";
                        }

                        $tanggalbaru=date("d-F-Y",strtotime($tanggal_fingerprint));
                        event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                        
                        $att=new logattendance();
                        $att->pegawai_id=$pegawai_id_fingerprint;
                        $att->instansi_id=$instansi_fingerprint;
                        $att->tanggal=$tanggal_fingerprint;
                        $att->function="kehadiranharikemarin";
                        $att->status="Success";
                        $att->save();

                        return "Success";
                        //Bisa Pulang
                    }
                    else
                    {
                        // dd('as');
                        $flag=false;
                        $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);

                        // return "sd";
                        return "Success";

                    }
                }
                else
                {
                    $datang=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$absen->jam_masuk));
                    $pulang=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                    // dd($absen->tanggal_att);
                    $akumulasi=($this->kurangwaktu($datang,$pulang));
                    // $patokan=new DateTime($this->kurangwaktu("00:00:00","24:00:00"));
                    $pulang=Carbon::createFromFormat('Y-m-d H:i:s', $tanggal_fingerprint." ".$jam_fingerprint);
                    $batas=Carbon::createFromFormat('Y-m-d H:i:s', $absen->tanggal_att." ".$absen->jam_masuk)->addDay();
                    // dd($pulang->greaterThanOrEqualTo($batas));
                    // dd($pulang." >= ".$batas);
                    if ($pulang->greaterThanOrEqualTo($batas))
                    {
                        $table = att::where('tanggal_att', '=', $absen->tanggal_att)
                            ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                            ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                            ->where('id','=',$absen->id)                    
                            ->first();
                        $table->tanggal_keluar=$tanggal_fingerprint;
                        $table->jenisabsen_id=2;
                        $table->save();

                        $flag=false;
                        $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);

                    }
                    else
                    {
                        

                        $table = att::where('tanggal_att', '=', $absen->tanggal_att)
                            ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                            ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                            ->where('id','=',$absen->id)                    
                            ->first();

                            $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                            ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();
                        
                        if (($table->jenisabsen_id=="2") || ($table->jenisabsen_id=="13")){
                            $table->jenisabsen_id = "1";
                        }
                        $table->keterangankeluar_id="1";
                        $table->tanggal_keluar=$tanggal_fingerprint;
                        $table->jam_keluar = $jam_fingerprint;
                        $table->keluarinstansi_id = $instansi_fingerprint;
                        $table->akumulasi_sehari = $akumulasi;
                        $table->save();

                        $flag=true;
                        $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);


                        $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                            ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();

                        $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();
                        $status = "pulang";
                        if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                            $class = "bg-green";
                        } else {
                            $class = "bg-yellow";
                        }

                        $tanggalbaru=date("d-F-Y",strtotime($tanggal_fingerprint));
                        event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                        
                        $att=new logattendance();
                        $att->pegawai_id=$pegawai_id_fingerprint;
                        $att->instansi_id=$instansi_fingerprint;
                        $att->tanggal=$tanggal_fingerprint;
                        $att->function="kehadiranharikemarin";
                        $att->status="Success";
                        $att->save();
                        // dd("as");
                        return "Success";
                    }
                }

            }//sambungan foreach

            $flag=false;
            $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);

            $att=new logattendance();
            $att->pegawai_id=$pegawai_id_fingerprint;
            $att->instansi_id=$instansi_fingerprint;
            $att->tanggal=$tanggal_fingerprint;
            $att->function="kehadiranharikemarin";
            $att->status="Success3";
            $att->save();
            // dd("as");
            return "Success";


       
        
    }

    //function untuk jam keluar
    //selesai -> belum dicoba
    protected function kehadiranhariini ($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint)
    {
        $hitungabsen = att::leftjoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            ->where('atts.pegawai_id', '=', $pegawai_id_fingerprint)
            ->where('atts.tanggal_att', '=', $tanggal_fingerprint)
            ->select('atts.*','jadwalkerjas.jenis_jadwal',
                    'jadwalkerjas.jam_masukjadwal',
                    'jadwalkerjas.jam_keluarjadwal',
                    'jadwalkerjas.sifat',
                    'jadwalkerjas.lewathari'

            )
            ->whereNotNull('atts.jam_masuk')
            ->whereNull('atts.jam_keluar')
            ->where('jadwalkerjas.lewathari','=','0')
            // ->latest()
            ->count();

        if ($hitungabsen>0){
            
            $absens = att::leftjoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            ->where('atts.pegawai_id', '=', $pegawai_id_fingerprint)
            ->where('atts.tanggal_att', '=', $tanggal_fingerprint)
            ->whereNotNull('atts.jam_masuk')
            ->select('atts.*','jadwalkerjas.jenis_jadwal',
                    'jadwalkerjas.jam_masukjadwal',
                    'jadwalkerjas.jam_keluarjadwal',
                    'jadwalkerjas.sifat',
                    'jadwalkerjas.lewathari'

            )
            ->whereNotNull('atts.jam_masuk')
            ->whereNull('atts.jam_keluar')
            ->where('jadwalkerjas.lewathari','=','0')
            //->latest()
            ->get();
            //  dd($absens);
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
                    $jammasuk= date("H:i:s", strtotime($cek[0]['jam_masukjadwal']));
                // $jamakhir2 = date("H:i:s", strtotime("-30 minutes", strtotime($jamakhir)));

                $jamfingerprint = date("H:i:s", strtotime($jam_fingerprint));
                // dd($jamawal."    ".$jamfingerprint."    ".$jamakhir2);
                // if (($jamfingerprint >= $jamakhir) && ($jamfingerprint <= ( $jamawal))) 
                // if (($jamfingerprint > $jammasuk) && ($jamfingerprint >= $jamakhir) && ($jamfingerprint <= $jamawal))
                if (($jamfingerprint > $jammasuk) && ($jamfingerprint <= $jamawal))

                {

                    
           
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
                        $harike=date('N', strtotime($tanggal_fingerprint));
                            //ini yang bujur be istiraahat
                            

                            $jamkeluarjadwal=date("H:i:s",strtotime($table2[0]['jam_keluarjadwal']));

                            if (($harike==5) && ($table2[0]['jam_keluarjadwal']>="14:00:00") && ($table2[0]['jam_masukjadwal']<="11:30:00"))                        
                            {
                                if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                                {
                                    // $jamban=$absen->jam_masuk;
                                    $jamban=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$absen->jam_masuk));                                                                                                
                                    $jam_fingerprint=date("H:i:s",strtotime($jam_fingerprint));
                                    $batasakhir=date("H:i:s",strtotime("11:30:00"));
                                    $batasakhir2=date("H:i:s",strtotime("14:00:00"));

                                    if ($jam_fingerprint < $batasakhir){
                                        $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                                    }
                                    elseif ($jam_fingerprint < $batasakhir2)
                                    {
                                        $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$batasakhir));
                                    }
                                    else
                                    {
                                        $jamban2=date("Y-m-d H:i:s", strtotime("-2 hours 30 minutes",strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal'])));
                                    }
                 
                                    $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                }
                                else
                                { 
                                    // $jamban=($table2[0]['jam_masukjadwal']);
                                    $jamban=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$table2[0]['jam_masukjadwal']));
                                    $jam_fingerprint=date("H:i:s",strtotime($jam_fingerprint));
                                    $batasakhir=date("H:i:s",strtotime("11:30:00"));
                                    $batasakhir2=date("H:i:s",strtotime("14:00:00"));

                                    if ($jam_fingerprint < $batasakhir){
                                        $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                                    }
                                    elseif ($jam_fingerprint < $batasakhir2)
                                    {
                                        $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$batasakhir));
                                    }
                                    else
                                    {
                                        $jamban2=date("Y-m-d H:i:s", strtotime("-2 hours 30 minutes",strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal'])));
                                    }
                                    // $jamban2=date("Y-m-d H:i:s", strtotime("-2 hours 30 minutes",strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal'])));
                                    $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                }

                            }
                            else
                            {

                                if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                                {
                                    $jamban=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$absen->jam_masuk));                                
                                    $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal']));
                                    $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                }
                                else
                                {
                                    // $jamban=($table2[0]['jam_masukjadwal']);
                                    $jamban=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$table2[0]['jam_masukjadwal']));                                                                
                                    $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal']));
                                    $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                                }
                            }
                    }

                    

                    $table = att::where('tanggal_att', '=', $tanggal_fingerprint)
                        ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                        ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                        ->where('id','=',$absen->id)
                        ->first();
                    // dd($jamban." + ".$jamban2." = ".$akumulasi);
                    // dd("sd");
                    $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                        ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();

                    if (($table->jenisabsen_id=="2") || ($table->jenisabsen_id=="13")){
                        $table->jenisabsen_id = "1";
                    }
                    $table->keterangankeluar_id="1";


                    $table->jam_keluar = $jam_fingerprint;
                    $table->keluarinstansi_id = $instansi_fingerprint;
                    $table->akumulasi_sehari = $akumulasi;
                    $table->save();

                    $flag=true;
                    $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);


                    $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                        ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();

                    $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();

                    
                    $status="pulang";
                    if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                        $class = "bg-green";
                    } else {
                        $class = "bg-yellow";
                    }

                    $tanggalbaru=date("d-F-Y",strtotime($tanggal_fingerprint));
                    event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                    
                    $att=new logattendance();
                    $att->pegawai_id=$pegawai_id_fingerprint;
                    $att->instansi_id=$instansi_fingerprint;
                    $att->tanggal=$tanggal_fingerprint;
                    $att->function="kehadiranhariini";
                    $att->status="Success";
                    $att->save();
                    
                    // dd("asd");   
                    return "Success";
                    //Bisa Pulan 3
                }
                else
                {
                    $flag=false;
                    $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);

                    // return "sd";
                    return "Success";

                }
            } //sambungan foreach
        }
        else
        {
            $att=new logattendance();
            $att->pegawai_id=$pegawai_id_fingerprint;
            $att->instansi_id=$instansi_fingerprint;
            $att->tanggal=$tanggal_fingerprint;
            $att->function="kehadiranhariini";
            $att->status="Success3";
            $att->save();

            $flag=false;
            $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);

            return "Success";
        }
        
    }


    //selesai -> belum dicoba
    protected function kehadiranharikemarin($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$tanggalkemarin){
        $hitungabsen = att::leftjoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            ->where('atts.pegawai_id', '=', $pegawai_id_fingerprint)
            ->where('atts.tanggal_att', '=', $tanggalkemarin)
            ->select('atts.*','jadwalkerjas.jenis_jadwal',
                    'jadwalkerjas.jam_masukjadwal',
                    'jadwalkerjas.jam_keluarjadwal',
                    'jadwalkerjas.sifat',
                    'jadwalkerjas.lewathari'

            )
            ->whereNotNull('atts.jam_masuk')
            ->whereNull('atts.jam_keluar')
            ->where('jadwalkerjas.lewathari','=','1')
            // ->latest()
            ->count();
        // dd("sd");
        if ($hitungabsen>0){
            $absens = att::leftjoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
            ->where('atts.pegawai_id', '=', $pegawai_id_fingerprint)
            ->where('atts.tanggal_att', '=', $tanggalkemarin)
            ->select('atts.*','jadwalkerjas.jenis_jadwal',
                    'jadwalkerjas.jam_masukjadwal',
                    'jadwalkerjas.jam_keluarjadwal',
                    'jadwalkerjas.sifat',
                    'jadwalkerjas.lewathari'
            )
            ->whereNotNull('atts.jam_masuk')
            ->whereNull('atts.jam_keluar')
            ->where('jadwalkerjas.lewathari','=','1')
            ->get();
            // dd($absens);
            foreach ($absens as $key => $absen) {
                //cek kecocokan jam masuk berdasarkan jadwalkerja
                $cek = jadwalkerja::join('rulejammasuks', 'jadwalkerjas.id', '=', 'rulejammasuks.jadwalkerja_id')
                    ->select('jadwalkerjas.*', 'rulejammasuks.jamsebelum_pulangkerja', 'rulejammasuks.jamsebelum_masukkerja')
                    ->where('jadwalkerjas.id', '=', $absen->jadwalkerja_id)
                    ->get();
                
                    $jamawal=date("Y-m-d H:i:s", strtotime($tanggalkemarin." ".$cek[0]['jamsebelum_masukkerja']));
                    $jamakhir=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$cek[0]['jamsebelum_pulangkerja']));
                    $jammasuk=date("Y-m-d H:i:s", strtotime($tanggalkemarin." ".$cek[0]['jam_masukjadwal']));
   
                $jamfingerprint = date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                $jamakhir2=$jamakhir;
              
                if (($jamfingerprint > $jammasuk) && ($jamfingerprint <= $jamakhir))

                // kondisi pulang tepat waktu    
                {
                    // dd("pulang tepat waktu");
                    //menghitung data absen trans pegawai
                    // $cari = atts_tran::where('pegawai_id', '=', $pegawai_id_fingerprint)
                    //     ->where('tanggal', '=', $tanggal_fingerprint)
                    //     ->where('status_kedatangan', '=', $status_fingerprint)
                    //     ->count();
                    // //jika hasil nya lebih dari 0 maka
                    // if ($cari > 0) {
                    //     //melakukan perubahan data absen trans yang ada
                    //     $table = atts_tran::where('tanggal', '=', $tanggal_fingerprint)
                    //         ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                    //         ->first();
                    //     $table->jam = $jam_fingerprint;
                    //     $table->tanggal = $tanggal_fingerprint;
                    //     $table->save();
                    // } else {
                    //     //menambah data absen trans yang baru
                    //     $save = new atts_tran();
                    //     $save->pegawai_id = $pegawai_id_fingerprint;
                    //     $save->tanggal = $tanggal_fingerprint;
                    //     $save->jam = $jam_fingerprint;
                    //     $save->lokasi_alat = $instansi_fingerprint;
                    //     $save->status_kedatangan = $status_fingerprint;
                    //     $save->save();
                    // }
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
                        $harike=date('N', strtotime($tanggalkemarin));
                        // if (($harike==5) && ($table2[0]['sifat']=="WA") && ($absen->masukistirahat!=null))
                        if (($harike==5) && ($table2[0]['jam_keluarjadwal']>="14:00:00") && ($table2[0]['jam_masukjadwal']<="11:30:00"))                        
                        {
                           

                            if ($absen->jam_masuk > $table2[0]['jam_masukjadwal'])
                            {
                                $jamban=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$absen->jam_masuk));                                                                                                                                
                                $jam_fingerprint=date("H:i:s",strtotime($jam_fingerprint));
                                $batasakhir=date("H:i:s",strtotime("11:30:00"));
                                $batasakhir2=date("H:i:s",strtotime("14:00:00"));

                                if ($jam_fingerprint < $batasakhir){
                                    $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                                }
                                elseif ($jam_fingerprint < $batasakhir2)
                                {
                                    $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$batasakhir));
                                }
                                else
                                {
                                    $jamban2=date("Y-m-d H:i:s", strtotime("-2 hours 30 minutes",strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal'])));
                                }
                                
                                // $jamban2=date("Y-m-d H:i:s", strtotime("-2 hours 30 minutes",strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal'])));

                                $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                            }
                            else
                            {
                                $jamban=date("Y-m-d H:i:s", strtotime($absen->tanggal_att." ".$table2[0]['jam_masukjadwal']));
                                $jam_fingerprint=date("H:i:s",strtotime($jam_fingerprint));
                                $batasakhir=date("H:i:s",strtotime("11:30:00"));
                                $batasakhir2=date("H:i:s",strtotime("14:00:00"));

                                if ($jam_fingerprint < $batasakhir){
                                    $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$jam_fingerprint));
                                }
                                elseif ($jam_fingerprint < $batasakhir2)
                                {
                                    $jamban2=date("Y-m-d H:i:s", strtotime($tanggal_fingerprint." ".$batasakhir));
                                }
                                else
                                {
                                    $jamban2=date("Y-m-d H:i:s", strtotime("-2 hours 30 minutes",strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal'])));
                                }
                                // $jamban2=date("Y-m-d H:i:s", strtotime("-2 hours 30 minutes",strtotime($tanggal_fingerprint." ".$table2[0]['jam_keluarjadwal'])));
                                $akumulasi=$this->kurangwaktu($jamban,$jamban2);
                            }
                        }
                        
                    }

                    

                    $table = att::where('tanggal_att', '=', $tanggalkemarin)
                        ->where('pegawai_id', '=', $pegawai_id_fingerprint)
                        ->where('jadwalkerja_id', '=', $absen->jadwalkerja_id)
                        ->where('id','=',$absen->id)                    
                        ->first();

                    $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                        ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();
                    
                    if (($table->jenisabsen_id=="2") || ($table->jenisabsen_id=="13")){
                        $table->jenisabsen_id = "1";
                    }
                    $table->keterangankeluar_id="1";

                    $table->jam_keluar = $jam_fingerprint;
                    $table->keluarinstansi_id = $instansi_fingerprint;
                    $table->akumulasi_sehari = $akumulasi;
                    $table->save();

                    $flag=true;
                    $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);


                    $pegawai = pegawai::join('instansis', 'pegawais.instansi_id', '=', 'instansis.id')
                        ->where('pegawais.id', '=', $pegawai_id_fingerprint)->get();

                    $instansi = instansi::where('id', '=', $instansi_fingerprint)->get();
                    $status = "pulang";
                    if ($pegawai[0]['namaInstansi'] == $instansi[0]['namaInstansi']) {
                        $class = "bg-green";
                    } else {
                        $class = "bg-yellow";
                    }

                    $tanggalbaru=date("d-F-Y",strtotime($tanggal_fingerprint));
                    event(new Timeline($pegawai_id_fingerprint, $tanggalbaru, $jam_fingerprint, $instansi_fingerprint, $status_fingerprint, $pegawai[0]['nama'], $pegawai[0]['namaInstansi'], $instansi[0]['namaInstansi'], $status, $class));
                    
                    $att=new logattendance();
                    $att->pegawai_id=$pegawai_id_fingerprint;
                    $att->instansi_id=$instansi_fingerprint;
                    $att->tanggal=$tanggal_fingerprint;
                    $att->function="kehadiranharikemarin";
                    $att->status="Success";
                    $att->save();

                    return "Success";
                    //Bisa Pulang
                }
                else
                {
                    $flag=false;
                    $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);

                    // return "sd";
                    return "Success";

                }
               
            }//sambungan foreach
        }
        else
        {
            $flag=false;
            $store_atts_tran=$this->store_atts_trans($pegawai_id_fingerprint,$tanggal_fingerprint,$jam_fingerprint,$status_fingerprint,$instansi_fingerprint,$macadress_fingerprint,$flag);

            $att=new logattendance();
            $att->pegawai_id=$pegawai_id_fingerprint;
            $att->instansi_id=$instansi_fingerprint;
            $att->tanggal=$tanggal_fingerprint;
            $att->function="kehadiranharikemarin";
            $att->status="Success3";
            $att->save();
            return "Success";
        }
    }


}
