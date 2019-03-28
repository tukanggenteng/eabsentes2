<?php

namespace App\Http\Controllers;
use App\Hari_Libur;
use App\Role_Hari_Libur;
use Illuminate\Http\Request;

class RoleHariLiburController extends Controller
{
    //
    public function index()
    {
        $datahariliburs=Hari_Libur::select('*')->paginate(30);

        return view('roleharilibur.index',['datahariliburs'=>$datahariliburs]);
    }

    public function eventcalendar(Request $request)
    {
        $awal=date('Y-m-d',strtotime($request->start));
        $akhir=date('Y-m-d',strtotime($request->end));
        $bulan=date('m',strtotime($awal));
        $tahun=date('Y',strtotime($awal));
        // dd($bulan);
        $event=array();

        $datas=Role_Hari_Libur::leftJoin('hari_liburs','role_hari_liburs.hari_libur_id','=','hari_liburs.id')
               ->select('role_hari_liburs.*','hari_liburs.nama_hari_libur')
               ->get();
        // return $datas;

        foreach ($datas as $data){
            $e=array();
            $banyak=$this->difftanggal($data->tanggalberlakuharilibur,$data->tanggalberlakuharilibur);
            // $banyak=$this->difftanggal($awal,$akhir);
            // dd($banyak);
            for ($x = 0; $x < $banyak+1; $x++)
            {
                if (($awal <= date('Y-m-d', strtotime($data->tanggalberlakuharilibur.' +'.$x.' day '))) && ($akhir>=date('Y-m-d', strtotime($data->tanggalberlakuharilibur.' +'.$x.' day ')))) {
                    $e['id']=encrypt($data->id);
                    $e['title']=$data->nama_hari_libur;
                    // $tanggalawal=date('Y-m-d',strtotime($data->tanggal_awalrule.' '.$x.' day'));
                    $e['start']=date('Y-m-d H:i:s', strtotime($data->tanggalberlakuharilibur.' +'.$x.' day 00:00:00'));
                    $e['end']=date('Y-m-d H:i:s', strtotime($data->tanggalberlakuharilibur.' +'.$x.' day 23:59:59'));

                    // $e['start']=date('Y-m-d H:i:s', strtotime($awal.' '.$x.' day '.$data->jam_masukjadwal));
                    // $e['end']=date('Y-m-d H:i:s', strtotime($awal.' '.$x.' day '.$data->jam_keluarjadwal));
                    $e['allDay']=false;

                    $warna=rtrim("rgb(221, 75, 57)",")");
                    $warna2=ltrim($warna,"rgb(");
                    $warna3=explode(", ",$warna2);

                    $warna4=$this->fromRGB($warna3[0],$warna3[1],$warna3[2]);
                    $e['backgroundColor']=$warna4;
                    $e['borderColor']=$warna4;
                    array_push($event,$e);
                }
            }
        }
        // dd($event);
        return $event;
    }


    public function store(Request $request)
    {
        $dataroleharilibur=new Role_Hari_Libur();
        $dataroleharilibur->hari_libur_id=decrypt($request->harilibur_id);
        $dataroleharilibur->tanggalberlakuharilibur=$request->tanggalberlakuharilibur;
        $dataroleharilibur->save();
        
        if ($dataroleharilibur->save())
        {
            return response()->json("success");
        }
        else
        {
            return response()->json("failed"); 
        }
            
    }

    public function destroy(Request $request)
    {
        $id=decrypt($request->id);

        $table=Role_Hari_Libur::find(($id));

        if ($table->delete())
        {
            return response()->json("success");
        }
        else
        {
            return response()->json("failed");
        }
    }
}
