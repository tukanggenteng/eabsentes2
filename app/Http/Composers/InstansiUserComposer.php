<?php
namespace App\Http\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\pegawai;
use App\rulejadwalpegawai;
use App\rulejammasuk;
use App\adminpegawai;
use App\hapusfingerpegawai;
use App\dokter;
use App\perawatruangan;
use App\ruangan;
use App\lograspberry;
use App\historyfingerpegawai;

class InstansiUserComposer {

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if (Auth::check()) {
            
            $instansi=Auth::user()->instansi_id;
            // dd($instansi);
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

            // return $data;
            $view->with('notification', $data);

            $tanggalsekarang=date("Y-m-d");

            $countpegawai=0;

            $countdoctor=0;

            $countperawat=0;


            if ((Auth::user()->role->namaRole=="user") )
            {
            

                $datadokter=dokter::pluck('pegawai_id')->all();
                $dataperawat=perawatruangan::pluck('pegawai_id')->all();

                $datapegawais=pegawai::where('instansi_id','=',Auth::user()->instansi_id)
                                ->whereNotIn('id',$dataperawat)
                                ->whereNotIn('id',$datadokter)
                                ->get();
                
                foreach ($datapegawais as $datapegawai)
                {
                    // dd($datapegawai);
                    $datapegawaitanpajadwal=rulejadwalpegawai::join('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                            ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                            // ->where('tanggal_awalrule','<=',$tanggalsekarang)
                            // ->whereNotIn('pegawais.id',$datadokter)
                            ->where('rulejadwalpegawais.pegawai_id','=',$datapegawai->id)
                            // ->havingRaw('rulejadwalpegawais.tanggal_akhirrule','<',$tanggalsekarang)
                            ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalsekarang)
                            // ->select('pegawais.id')
                            // ->groupBy('pegawais.id')
                            ->count();
                    // dd($datapegawaitanpajadwal);
                    
                    if ($datapegawaitanpajadwal<1)
                    {
                        // dd('sd');
                        $countpegawai++;
                        // dd($countpegawai);
                    }
                }

                

                
            }
            elseif (Auth::user()->role->namaRole=="rs")
            {
                $datadokter=dokter::pluck('pegawai_id')->all();
                $dataperawat=perawatruangan::pluck('pegawai_id')->all();

                $datapegawaidoktors=pegawai::where('instansi_id','=',Auth::user()->instansi_id)
                                ->whereIn('id',$datadokter)
                                ->get();
                
                foreach ($datapegawaidoktors as $datapegawai)
                {
                    // dd($datapegawai);
                    $datapegawaitanpajadwal=rulejadwalpegawai::join('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                            ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                            // ->where('tanggal_awalrule','<=',$tanggalsekarang)
                            // ->whereNotIn('pegawais.id',$datadokter)
                            ->where('rulejadwalpegawais.pegawai_id','=',$datapegawai->id)
                            // ->havingRaw('rulejadwalpegawais.tanggal_akhirrule','<',$tanggalsekarang)
                            ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalsekarang)
                            // ->select('pegawais.id')
                            // ->groupBy('pegawais.id')
                            ->count();
                    // dd($datapegawaitanpajadwal);
                    
                    if ($datapegawaitanpajadwal<1)
                    {
                        // dd('sd');
                        $countdoctor++;
                        // dd($countpegawai);
                    }
                }

                $datapegawais=pegawai::where('instansi_id','=',Auth::user()->instansi_id)
                                ->whereNotIn('id',$dataperawat)
                                ->whereNotIn('id',$datadokter)
                                ->get();

                foreach ($datapegawais as $datapegawai)
                {
                    // dd($datapegawai);
                    $datapegawaitanpajadwal=rulejadwalpegawai::join('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                            ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                            // ->where('tanggal_awalrule','<=',$tanggalsekarang)
                            // ->whereNotIn('pegawais.id',$datadokter)
                            ->where('rulejadwalpegawais.pegawai_id','=',$datapegawai->id)
                            // ->havingRaw('rulejadwalpegawais.tanggal_akhirrule','<',$tanggalsekarang)
                            ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalsekarang)
                            // ->select('pegawais.id')
                            // ->groupBy('pegawais.id')
                            ->count();
                    // dd($datapegawaitanpajadwal);
                    
                    if ($datapegawaitanpajadwal<1)
                    {
                        // dd('sd');
                        $countpegawai++;
                        // dd($countpegawai);
                    }
                }
            }
            elseif (Auth::user()->role->namaRole=="karu")
            {
                $ruanganid=ruangan::where('instansi_id','=',Auth::user()->instansi_id)->first();

                $datadokter=dokter::pluck('pegawai_id')->all();
                $dataperawat=perawatruangan::where('ruangan_id','=',$ruanganid->id)->pluck('pegawai_id');

                $datapegawais=pegawai::where('instansi_id','=',Auth::user()->instansi_id)
                                ->whereIn('id',$dataperawat)
                                ->get();
                // dd($dataperawat);
                foreach ($datapegawais as $datapegawai)
                {
                    // dd($datapegawai);
                    $datapegawaitanpajadwal=rulejadwalpegawai::join('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                            ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                            // ->where('tanggal_awalrule','<=',$tanggalsekarang)
                            // ->whereNotIn('pegawais.id',$datadokter)
                            ->where('rulejadwalpegawais.pegawai_id','=',$datapegawai->id)
                            // ->havingRaw('rulejadwalpegawais.tanggal_akhirrule','<',$tanggalsekarang)
                            ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalsekarang)
                            // ->select('pegawais.id')
                            // ->groupBy('pegawais.id')
                            ->count();
                    // dd($datapegawaitanpajadwal);
                    
                    if ($datapegawaitanpajadwal<1)
                    {
                        // dd('sd');
                        $countperawat++;
                        // dd($countpegawai);
                    }
                }
            }
            
            // dd($countpegawai);
            $view->with('datapegawai', $countpegawai);                
            $view->with('datadokter', $countdoctor);                
            $view->with('dataperawat', $countperawat);                
        }
    }
 
}