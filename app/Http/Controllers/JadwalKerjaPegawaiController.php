<?php

namespace App\Http\Controllers;
use App\att;
use App\jadwalkerja;
use App\pegawai;
use App\dokter;
use App\perawatruangan;
use App\rulejadwalpegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;

class JadwalKerjaPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
//        dd($request->table_search);

        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }
//        dd($inforekap);
        $tanggalsekarang=date("Y-m-d");
        
            $rulejadwal2=rulejadwalpegawai::join('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                // ->where('tanggal_awalrule','<=',$tanggalsekarang)
                ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalsekarang)
                ->select('rulejadwalpegawais.id','pegawais.nip','pegawais.nama','jadwalkerjas.jenis_jadwal','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule')
                ->orderBy('rulejadwalpegawais.tanggal_akhirrule','ASC')
                ->paginate(30);

            $rulejadwal=pegawai::where('instansi_id',Auth::user()->instansi_id)->paginate(30);
            $jadwalkerja=jadwalkerja::where('instansi_id','=',Auth::user()->instansi_id)
                        ->orWhere('instansi_id','=','1')
                        ->get();
            return view('jadwalkerjapegawai.jadwalkerjapegawai',['inforekap'=>$inforekap,'jadwalkerjas'=>$jadwalkerja,'rulejadwals2'=>$rulejadwal2,'rulejadwals'=>$rulejadwal]);
    }

    public function datapegawai(){
                
                $tanggalsekarang=date("Y-m-d");
                
                $datadokter=dokter::pluck('pegawai_id')->all();
                $dataperawat=perawatruangan::pluck('pegawai_id')->all();
                
                $rulejadwal=pegawai::where('instansi_id',Auth::user()->instansi_id)
                            ->whereNotIn('pegawais.id',$datadokter)
                            ->whereNotIn('pegawais.id',$dataperawat)
                            ->get();
                return Datatables::of($rulejadwal)
                    ->editColumn('action', function ($rulejadwal) {
                        return '<input type="checkbox" name="checkbox[]" value="'.encrypt($rulejadwal->id).'" class="flat-red checkbox">';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

    public function datarulejadwalpegawai(){
        $tanggalsekarang=date("Y-m-d");
                $rulejadwal=rulejadwalpegawai::join('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
                ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                // ->where('tanggal_awalrule','<=',$tanggalsekarang)
                ->where('rulejadwalpegawais.tanggal_akhirrule','>=',$tanggalsekarang)
                ->select('rulejadwalpegawais.id','pegawais.nip','pegawais.nama','jadwalkerjas.jenis_jadwal','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule')
                ->orderBy('rulejadwalpegawais.tanggal_akhirrule','ASC')
                ->get(30);
                return Datatables::of($rulejadwal)
                    ->addColumn('action', function ($rulejadwal) {
                        return '<input type="checkbox" name="checkbox2[]" value="'.encrypt($rulejadwal->id).'" class="flat-red cekbox2">';
                    })
                    ->editColumn('tanggal_akhirrule', function ($rulejadwal) {

                        $tanggalsekarang=date("Y-m-d");
                        $minimal=date("Y-m-d",strtotime("-4 day",strtotime($rulejadwal->tanggal_akhirrule)));
                        $minimal=strtotime($minimal);
                        $sekarangi=date("Y-m-d",strtotime("-4 day",strtotime($rulejadwal->tanggal_akhirrule)));

                        if (($minimal >= strtotime($tanggalsekarang)) && (strtotime($tanggalsekarang) <= strtotime($rulejadwal->tanggal_akhirrule))){
                            return $rulejadwal->tanggal_akhirrule;
                        }
                        else
                        {
                            return '<span class="badge bg-red">'.$rulejadwal->tanggal_akhirrule.'</span>';
                        }

                        // return '<input type="checkbox" name="checkbox[]" value="'.encrypt($rulejadwal->id).'" class="flat-red checkbox">';
                    })
                    ->addColumn('aksi', function ($rulejadwal) {
                        return '<a class="btn-sm btn-success" href="/jadwalkerjapegawai/'. encrypt($rulejadwal->id) .'/edit">Edit</a>
                        <a class="btn-sm btn-danger" data-method="delete"
                        data-token="{{csrf_token()}}" href="/jadwalkerjapegawai/'. encrypt($rulejadwal->id) .'/hapus">Hapus</a>';
                    })
                    ->rawColumns(['action','tanggal_akhirrule','aksi'])
                    ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'checkbox'=>'required',
            'jadwalkerjamasuk'=>'required'
        ]);


        foreach ($request->checkbox as $data){
            $data=decrypt($data);
            $hapusspasi=str_replace(" ","",$request->daterange);
            $tanggal=explode("-",$hapusspasi);

            $tanggalhariini=date("Y-m-d");


            $tanggalawal=date("Y-m-d",strtotime($tanggal[0]));
            $tanggalakhir=date("Y-m-d",strtotime($tanggal[1]));

        //    dd($tanggalakhir."+".$tanggalawal);

            $verifikasi=rulejadwalpegawai::
                where('pegawai_id','=',$data)
                ->where('jadwalkerja_id','=',$request->jadwalkerjamasuk)
                // ->where('tanggal_awalrule',',>=',$tanggalawal)
                // ->where('tanggal_akhirrule','<=',$tanggalakhir)
                ->where('tanggal_akhirrule','>=',$tanggalawal)
                // ->orWhere('tanggal_awalrule',',<=',$tanggalawal)
                // ->orWhere('tanggal_akhirrule','>=',$tanggalakhir)
                ->count();

        //    dd($data);

            if ($verifikasi>0) {
                return redirect('/jadwalkerjapegawai')->with('err','Terdapat data jadwal pegawai lebih dari 2 kali pada hari yang sama.');
            }
            else
            {
                if (($tanggalhariini == $tanggalawal)) {
                    $cek = att::where('tanggal_att', '=', $tanggalhariini)
                        ->where('pegawai_id','=',$data)
                        ->where('jadwalkerja_id', '=', $request->jadwalkerjamasuk)
                        ->count();
                    if ($cek == 0) {

                        $jadwalkerja=jadwalkerja::where('id','=',$request->jadwalkerjamasuk)->first();

                        $table = new att();
                        $table->pegawai_id = $data;
                        $table->jadwalkerja_id = $request->jadwalkerjamasuk;
                        $table->tanggal_att = $tanggalhariini;
                        $table->terlambat="00:00:00";
                        $table->akumulasi_sehari="00:00:00";
                        $table->apel="0";  
                        if ($jadwalkerja->sifat=="FD"){
                            $table->jenisabsen_id = '13';
                        }
                        else{
                            $table->jenisabsen_id = '2';
                        }
                        $table->save();
                    }
                }

                $table = new rulejadwalpegawai();
                $table->pegawai_id = $data;
                $table->tanggal_awalrule = $tanggal[0];
                $table->tanggal_akhirrule = $tanggal[1];
                $table->jadwalkerja_id = $request->jadwalkerjamasuk;
                $table->save();

            }
        }
        return redirect('/jadwalkerjapegawai');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        //
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }

        $id=decrypt($id);
        $rulejadwal2=rulejadwalpegawai::join('pegawais','rulejadwalpegawais.pegawai_id','=','pegawais.id')
            ->join('jadwalkerjas','rulejadwalpegawais.jadwalkerja_id','=','jadwalkerjas.id')
            ->select('rulejadwalpegawais.id','rulejadwalpegawais.jadwalkerja_id','pegawais.nip','pegawais.nama','jadwalkerjas.jenis_jadwal','rulejadwalpegawais.tanggal_awalrule','rulejadwalpegawais.tanggal_akhirrule')
            ->where('rulejadwalpegawais.id','=',$id)
            ->get();

            $jadwalkerja=jadwalkerja::all();
        return view('jadwalkerjapegawai.editjadwalkerjapegawai',['inforekap'=>$inforekap,'rulejadwals'=>$rulejadwal2,'jadwalkerjas'=>$jadwalkerja]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
//        dd($id);
        $this->validate($request, [
            'jadwalkerjamasuk'=>'required'
        ]);
        $hapusspasi=str_replace(" ","",$request->daterange);
        $tanggal=explode("-",$hapusspasi);

        $table=rulejadwalpegawai::where('id','=',$request->id)->first();
        $table->jadwalkerja_id=$request->jadwalkerjamasuk;
        $table->tanggal_awalrule=$tanggal[0];
        $table->tanggal_akhirrule=$tanggal[1];
        $table->save();

        return redirect('/jadwalkerjapegawai');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id=decrypt($id);
        $table=rulejadwalpegawai::find($id);
        $table->delete();
        return redirect('/jadwalkerjapegawai');
    }


    public function destroyall(Request $request)
    {
        $this->validate($request, [
            'checkbox2'=>'required',
        ]);

        foreach ($request->checkbox2 as $data){
            $data=decrypt($data);
            $table=rulejadwalpegawai::find($data);
            $table->delete();
        }
        
        return redirect('/jadwalkerjapegawai');
    }

}
