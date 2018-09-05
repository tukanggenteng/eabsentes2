<?php

namespace App\Http\Controllers;
use App\macaddresse;
use App\instansi;
use Illuminate\Http\Request;

class MacAdressControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('throttle:60,1');
    }

    public function index()
    {
        //
        $tables=macaddresse::leftJoin('instansis','macaddresses.instansi_id','=','instansis.id')
                ->select('macaddresses.*','instansis.namaInstansi')
                ->get();
        return view('macaddress.macaddress',['tables'=>$tables]);
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
        //
        // dd($request->instansi_id[0]);
        $this->validate($request, [
            'macaddress'=>'required | min:17 | unique:macaddresses',
            'instansi_id'=>'required'
        ]);
        
        $user = new macaddresse;
        $user->macaddress = strtoupper($request->macaddress);
        $user->instansi_id = ($request->instansi_id[0]);
        $user->save();
        return redirect('/macaddress');
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
        $id=decrypt($id);

        $table=macaddresse::where('id','=',$id)->first();

        $instansis=instansi::where('id','!=','1')->get();
        // dd($instansis);
        // dd($table);
        return view('macaddress.editmacaddress',['tables'=>$table,'id'=>$id,'instansis'=>$instansis]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
        $this->validate($request, [
            'macaddress'=>'required | min:17 | unique:macaddresses',
        ]);
        $table=macaddresse::where('id','=',$request->id)->first();
        $table->macaddress=$request->macaddress;
        $user->instansi_id = ($request->instansi_id[0]);
        $table->save();
        return redirect('/macaddress');
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
    public function destroy($id)
    {
        //
        $id=decrypt($id);
        $table=macaddresse::find($id);
        $table->delete();
        return redirect('/macaddress');
    }

    public function macaddress(){

      $tables=macaddresse::all();
      return $tables;
    }
}
