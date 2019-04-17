<?php

use Illuminate\Database\Seeder;
use App\att;
// use App\harikerja;
use App\jadwalkerja;
// use App\pegawai;
// use App\dokter;
use App\perawatruangan;
// use App\rulejadwalpegawai;
use App\rulejammasuk;
// use App\jadwalminggu;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Jobs\GenerateAttendance;


use App\keterangan_absen;



use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;




use App\Role_Hari_Libur;
use App\Pegawai_Hari_Libur;
use App\attendancecheck;
use App\rekapbulancheck;
use App\rekapminggucheck;
use App\harikerja;
use App\instansi;
use App\jadwalminggu;
use App\pegawai;
use App\dokter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\finalrekapbulanan;
use App\rekapbulanan;
use App\masterbulanan;
use App\rulejadwalpegawai;
use App\jenisabsen;


class GenerateAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $tanggalsekarang=date('Y-m-d',strtotime('2019-04-14'));

        $rulejadwalpegawais=rulejadwalpegawai::where('tanggal_akhirrule','>=',$tanggalsekarang)->get();

        foreach ($rulejadwalpegawais as $rulejadwalpegawai)
        {
            $pegawai=pegawai::where('id','=',$rulejadwalpegawai->pegawai_id)->first();
            $tanggalawal=$tanggalsekarang;
            $tanggalakhir=$rulejadwalpegawai->tanggal_akhirrule;
            $pegawai_id=$rulejadwalpegawai->pegawai_id;
            $jadwalkerja_id=$rulejadwalpegawai->jadwalkerja_id;
            $instansi_id=$pegawai->instansi_id;
            
            // echo $pegawai->id;

            $month=Carbon::now()->month;
            $year = Carbon::now()->year;
            $date = Carbon::createFromDate($year,$month);
            $numberOfWeeks = floor($date->daysInMonth / Carbon::DAYS_PER_WEEK);
            $start = [];
            $end = [];
            $j=1;

            for ($i=1; $i <= $date->daysInMonth ; $i++) {
                Carbon::createFromDate($year,$month,$i); 
                $start['Week: '.$j.' Start Date']= (array)Carbon::createFromDate($year,$month,$i)->startOfWeek()->toDateString();
                $end['Minggu '.$j]= (array)Carbon::createFromDate($year,$month,$i)->endOfweek()->toDateString();
                $i+=7;
                $j++; 
            }
            // $result = array_merge($start,$end);
            // $result['numberOfWeeks'] = ["$numberOfWeeks"];


            

            $diffdate=Controller::difftanggal2($tanggalawal,$tanggalakhir);
            // dd($diffdate);
            for ($k=0 ; $k <= $diffdate ; $k++) {
                // dd($k);
                $tanggalproses=date('Y-m-d',strtotime('+'.$k.' days',strtotime($tanggalawal)));
                // dd($tanggalproses);
                if ($tanggalproses < $tanggalsekarang)
                {

                }
                else
                {
                    $date=date('N',strtotime($tanggalproses));

                    if ($date==1){
                        $hari='Senin';
                    }
                    elseif ($date==2){
                        $hari='Selasa';
                    }
                    elseif ($date==3){
                        $hari='Rabu';
                    }
                    elseif ($date==4){
                        $hari='Kamis';
                    }
                    elseif ($date==5){
                        $hari='Jumat';
                    }
                    elseif ($date==6){
                        $hari='Sabtu';
                    }
                    elseif ($date==7){
                        $hari='Minggu';
                    }

                    if ($tanggalproses <= $end['Minggu 1'][0]){
                        $minggu=1;
                    }
                    elseif ($tanggalproses <= $end['Minggu 2'][0]){
                        $minggu=2;
                    }
                    elseif ($tanggalproses <= $end['Minggu 3'][0]){
                        $minggu=3;
                    }
                    elseif ($tanggalproses <= $end['Minggu 4'][0]){
                        $minggu=4;
                    }
                    elseif ($tanggalproses >= $end['Minggu 4'][0]){
                        $minggu=1;
                    }
                

                    $minggujadwals=jadwalminggu::where('minggu','=',$minggu)->where('jadwalkerja_id','=',$jadwalkerja_id)->where('instansi_id','=',$instansi_id)->get();
                    // dd($minggujadwals);
                    foreach ($minggujadwals as $key => $minggujadwal){
                        $harikerjas=harikerja::leftJoin('jadwalkerjas','harikerjas.jadwalkerja_id','=','jadwalkerjas.id')
                                    ->where('harikerjas.hari','=',$hari)
                                    ->where('harikerjas.instansi_id','=',$instansi_id)
                                    ->where('harikerjas.jadwalkerja_id','=',$minggujadwal->jadwalkerja_id)
                                    ->distinct()
                                    ->get(['jadwalkerja_id','hari']);

                        // dd($harikerjas);
                        foreach ($harikerjas as $key =>$jadwalkerja){
                                $hitung=rulejadwalpegawai::leftJoin('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                                ->leftJoin('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                                ->leftJoin('harikerjas','harikerjas.jadwalkerja_id','=','jadwalkerjas.id')
                                ->where('harikerjas.instansi_id','=',$instansi_id)
                                ->where('harikerjas.hari','=',$hari)
                                ->where('pegawais.instansi_id','=',$instansi_id)
                                ->where('rulejadwalpegawais.jadwalkerja_id','=',$jadwalkerja_id)
                                ->where('pegawais.id','=',$pegawai_id)
                                ->where('rulejadwalpegawais.tanggal_awalrule','<=',$tanggalproses)
                                ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalproses)
                                ->count();

                                $pegawaiterkecuali=dokter::pluck('pegawai_id')->all();

                                $jadwalpegawais=rulejadwalpegawai::leftJoin('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                                            ->where('pegawais.instansi_id','=',$instansi_id)
                                            ->where('rulejadwalpegawais.jadwalkerja_id','=',$jadwalkerja_id)
                                            ->where('rulejadwalpegawais.tanggal_awalrule','<=',$tanggalproses)
                                            ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalproses)
                                            ->whereNotIn('pegawais.id',$pegawaiterkecuali)
                                            ->where('pegawais.id','=',$pegawai_id)
                                            ->get();
                                // dd($jadwalpegawais);
                                foreach ($jadwalpegawais as $jadwalpegawai)
                                {
                                    $cek=att::where('tanggal_att','=',$tanggalproses)
                                        ->where('pegawai_id','=',$jadwalpegawai->pegawai_id)
                                        ->where('jadwalkerja_id','=',$jadwalkerja->jadwalkerja_id)
                                        ->count();
                                    if ($cek > 0){
                                        $hapusatt=att::where('tanggal_att','=',$tanggalproses)
                                                    ->where('pegawai_id','=',$jadwalpegawai->pegawai_id)
                                                    ->where('jadwalkerja_id','=',$jadwalkerja->jadwalkerja_id)
                                                    // ->where('jenisabsen_id','=','2')
                                                    ->delete();





                                        echo "<<".$tanggalproses." pegawai id = ".$pegawai_id." instansi = ".$pegawai->instansi_id;

                                        $sifatjadwalkerja=jadwalkerja::where('id','=',$jadwalkerja_id)->first();
                                        // dd($sifatjadwalkerja->sifat);
                                        $details['tanggalproses']=$tanggalproses;
                                        $details['pegawai_id']=$pegawai_id;
                                        $details['jadwalkerja_id']=$jadwalkerja_id;
                                        $details['sifat']=$sifatjadwalkerja->sifat;
                                        $details['instansi_id']=$pegawai->instansi_id;
                                        dispatch(new GenerateAttendance($details));
                                    }
                                    else{
                                        $roleharilibur=Role_Hari_Libur::where('tanggalberlakuharilibur','=',$tanggalproses)->first();
                                        $pegawaiharilibur=Pegawai_Hari_Libur::where('pegawai_id','',$jadwalpegawai->pegawai_id)->first();
                                        
                                        if (($roleharilibur!=null)&&($pegawaiharilibur!=null))
                                        {

                                        }
                                        else
                                        {   
                                                echo "<<".$tanggalproses." pegawai id = ".$pegawai_id." instansi = ".$pegawai->instansi_id;

                                                $sifatjadwalkerja=jadwalkerja::where('id','=',$jadwalkerja_id)->first();
                                                // dd($sifatjadwalkerja->sifat);
                                                $details['tanggalproses']=$tanggalproses;
                                                $details['pegawai_id']=$pegawai_id;
                                                $details['jadwalkerja_id']=$jadwalkerja_id;
                                                $details['sifat']=$sifatjadwalkerja->sifat;
                                                $details['instansi_id']=$pegawai->instansi_id;
                                                dispatch(new GenerateAttendance($details));
                                        
                                        }
                                    }

                                }
                            
                        
                        }
                    }
                    
                    
                    
                }
                
            }
        }
    }
}
