<?php

namespace App\Http\Controllers;
use App\Hari_Libur;
use App\Role_Hari_Libur;
use Illuminate\Http\Request;

class HariLiburController extends Controller
{
    //
    public function index(Request $request)
    {
        $datahariliburs=Hari_Libur::where('nama_hari_libur','like','%'.$request->namaharilibur.'%')
                            ->get();
        // $datahariliburs=Hari_Libur::get();
       
        return view('harilibur.index',['hariliburs'=>$datahariliburs]);
    }


    public function store(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'namaharilibur'=>'required',
        ]);

        $table = new Hari_Libur;
        $table->nama_hari_libur = $request->namaharilibur;

        // dd("tes");
        if ($table->save())
        {
            return redirect()->back()->with('success','Hari libur berhasil disimpan.');
        }
        else
        {
            return redirect()->back()->with('error','Hari libur gagal disimpan.');
        }
        
    }

    public function edit($id)
    {
        $dataharilibur=Hari_Libur::where('id','=',decrypt($id))
        ->first(); 

        return view('harilibur.show',['harilibur'=>$dataharilibur]);
    }

    public function update($id,Request $request)
    {
        $dataharilibur=Hari_Libur::where('id','=',decrypt($id))
                        ->first();

        $dataharilibur->nama_hari_libur=$request->namaharilibur;
           
        if ($dataharilibur->save())
        {
            return redirect('/harilibur')->with('success','Hari libur berhasil diubah.'); 
        }
        else
        {
            return redirect('/harilibur')->with('error','Hari libur gagal diubah.');
        }
    }

    public function show($id)
    {

    }

    public function destroy($id)
    {
        $dataharilibur=Hari_Libur::where('id','=',decrypt($id))
                        ->first(); 
        // dd($dataharilibur);
        $roleharilibur=Role_Hari_Libur::where('hari_libur_id','=',decrypt($id))->first();
        if ($roleharilibur!=null)
        {
            $roleharilibur->delete();
        }
        if ($dataharilibur->delete())
        {
            return redirect('/harilibur')->with('success','Hari libur berhasil dihapus.'); 
        }
        else
        {
            return redirect('/harilibur')->with('error','Hari libur gagal dihapus.');
        }

    }
}
