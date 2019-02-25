<?php

namespace App\Http\Controllers;
use App\att;
use App\atts_tran;
use App\instansi;
use App\jadwalkerja;
use App\pegawai;
use App\rulejadwalpegawai;
use App\rulejammasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Request as Request2;
use App\Events\Timeline;

class AttendanceController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('throttle:100,1');
    }

    public function store(Request $request){  

          // dd($dataattendance['jam']);

          $jam=$request->json('jam');
          $tanggal=$request->json('tanggal');
          $user_id=$request->json('user_id');
          $instansi=$request->json('instansi');
          $status=$request->json('status');
          $macaddress=$request->json('macaddress');

          if ($macaddress==null)
          {
            return "Failed";
          }

          $auth=$request->json('token');
          // return "asd";

          // $jam=$dataattendance['jam'];
          // $tanggal=$dataattendance['tanggal'];
          // $user_id=$dataattendance['user_id'];
          // $instansi=$dataattendance['instansi'];
          // $status=$dataattendance['status'];
          // $macaddress=$dataattendance['macaddress'];
          // $auth=$dataattendance['token'];

          $hasilbasic=$jam.$tanggal.$user_id.$instansi.$status;
          $hasil=$this->encryptOTP($hasilbasic);
          $statusauth=false;
    
          // dd($hasil);

          if ($hasil==$auth)
          {
              
              $statusauth=true;

              if ($status=='0' ){
                return $this->jam_masuk($user_id,$tanggal,$jam,$status,$instansi,$macaddress);
              }
              // ########### absen pulang
              elseif ($status=='1' )
              {
                return $this->jam_keluar($user_id,$tanggal,$jam,$status,$instansi,$macaddress);
              }
              elseif ($status=='2'){
                return $this->keluaristirahat($user_id,$tanggal,$jam,$status,$instansi,$macaddress);
              }
              elseif ($status=='3'){
                return $this->masukistirahat($user_id,$tanggal,$jam,$status,$instansi,$macaddress);
              }
              elseif ($status=='4'){
                return $this->jam_masuktanpaapel($user_id,$tanggal,$jam,$status,$instansi,$macaddress);
              }
          }
          else
          {
            
              $statusauth=false;
              return "Failed";
         
          }

        
      
      
    }

    public function show(){
        $tanggal=date('H:i');
        $tabel=atts_tran::join('pegawais','atts_trans.pegawai_id','=','pegawais.id')->join('instansis','atts_trans.lokasi_alat','=','instansis.id')->where('tanggal','=',$tanggal)->get();
        return $tabel;
    }

    public function go(Request $request){
        $name=$request->name;
        $url="http://eabsen.dev/timeline?name=".$name;
        return new RedirectResponse($url);
    }

    public function encryptdata(Request $request){
        $jam=$request->json('jam2');
        $basic=substr($jam,5);
        $hasilbasic=str_replace(":","",$basic);
        $hasil=$this->encryptOTP($hasilbasic);
        $statusauth="0";
        $auth=$request->json('auth');

        if ($hasil==$auth){
            $statusauth="1";
        }else{
            $statusauth="0";
        }

        return $hasil;
    }

    public function hapusatt($id){
        $table=att::leftJoin('pegawais','pegawais.id','=','atts.pegawai_id')
                ->where('pegawais.instansi_id','=',$id)
                ->select('atts.*','pegawais.nip','pegawais.nama','pegawais.instansi_id')
                ->get();

        foreach ($table as $key =>$data){
            $hapus=att::find($data->id);
            // dd($data);
            $hapus->delete();
        }
        // dd($table);
        return "selesai";
    }

    public function attendanceapi($pegawai,$tanggalawal,$tanggalakhir)
    {
        $tanggalawal=date('Y-m-d',strtotime($tanggalawal));
        $tanggalakhir=date('Y-m-d',strtotime($tanggalakhir));
        $data=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
                ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
                ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
                ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
                ->where('pegawais.nip','=',$pegawai)
                ->where('atts.tanggal_att','>=',$tanggalawal)
                ->where('atts.tanggal_att','<=',$tanggalakhir)
                ->select('atts.tanggal_att',
                'atts.jam_masuk',
                'atts.terlambat',
                'atts.jam_keluar',
                'atts.akumulasi_sehari',
                'atts.keluaristirahat',
                'atts.masukistirahat',
                'atts.apel',
                'jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat','instansismasuk.namaInstansi as namainstansimasuk',
                'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
                ->get();
        return $data;
    }
}
