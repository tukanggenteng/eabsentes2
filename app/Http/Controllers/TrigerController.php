<?php

namespace App\Http\Controllers;
use App\triger;
use Illuminate\Http\Request;

class TrigerController extends Controller
{
    //
    public function index(){
        $table=triger::all();
        return $table;
    }

    public function form() {
        $tables=triger::where('id','=','1')->first();
        // dd($tables);
        $trigger=$tables->status;
        return view('trigger.trigger',['status'=>$trigger]);
    }

    public function edit(Request $request) {
        $tables=triger::where('id','=','1')->first();
        $tables->status=$request->triger;
        $tables->save();

        return redirect('/trigger')->with('success', 'Berhasil menyimpan trigger.');
    }
}
