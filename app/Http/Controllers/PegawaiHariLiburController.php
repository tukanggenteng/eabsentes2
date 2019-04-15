<?php

namespace App\Http\Controllers;
use App\Pegawai_Hari_Libur;
use App\pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;

class PegawaiHariLiburController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return view('pegawai_harilibur.manajemenpegawaiharilibur');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getdata()
    {
        $finger=DB::raw("(SELECT pegawai_id,COUNT(pegawai_id) as pegawai_hari_liburs from pegawai_hari_liburs GROUP BY pegawai_id) as pegawai_hari_liburs");
        $users=pegawai::leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
              ->leftJoin($finger,'pegawai_hari_liburs.pegawai_id','=','pegawais.id')
              ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
              ->select('pegawais.*','instansis.namaInstansi','pegawai_hari_liburs.*')
              ->get();
        return Datatables::of($users)
            ->addColumn('action', function ($users) {
                return '<input type="checkbox" name="checkbox2[]" value="'.encrypt($users->id).'" class="flat-red cekbox2">';
            })
            ->editColumn('pegawai_hari_liburs',function ($users)
            {
                if ($users->pegawai_hari_liburs>0)
                {
                    return 'Ya';
                }
                else
                {
                    return 'Tidak';
                }
            })
            ->make(true);
    }

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
        //

     
        
        // dd($request);

        $status=false;

        if ($request->tombol=="tambah")
        {
            foreach ($request->checkbox2 as $data){
                $data=decrypt($data);
                
                $checktable=Pegawai_Hari_Libur::where('pegawai_id','=',$data)->count();

                if ($checktable==0)
                {
                    $table= new Pegawai_Hari_Libur();
                    $table->pegawai_id=$data;
                    if ($table->save())
                    {
                        // $roleharilibur=Role_Hari_Libur::
                        $status=true;
                    }
                    else
                    {
                        $status=false;
                    }
                }

                
            }
            if ($status)
            {
                return redirect('/harilibur/pegawai')->with('success','Berhasil menyimpan hari libur nasional pegawai !');
            }
            else
            {
                return redirect('/harilibur/pegawai')->with('err','Gagal menyimpan hari libur nasional pegawai!');
            }
        }
        else
        {
            foreach ($request->checkbox2 as $data){
                $data=decrypt($data);
                

                $table=Pegawai_Hari_Libur::where('pegawai_id','=',$data)->first();
                if ($table->delete())
                {
                    $status=true;
                }
                else
                {
                    $status=false;
                }
            }
            if ($status)
            {
                return redirect('/harilibur/pegawai')->with('success','Berhasil menyimpan hari libur nasional pegawai !');
            }
            else
            {
                return redirect('/harilibur/pegawai')->with('err','Gagal menyimpan hari libur nasional pegawai!');
            }
        }
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        
    }
}
