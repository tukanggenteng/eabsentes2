<?php

namespace App\Http\Controllers;
use App\logfingererror;
use Illuminate\Http\Request;

class LogFingerErrorController extends Controller
{
    //
    public function index(){
    
    }

    public function create(Request $request){
        $hitung=logfingererror::where('user_id','=',$request->json('user_id'))->count();

        if ($hitung > 0)
        {

        }
        else{
            $table= new logfingererror();
            $table->user_id=$request->json('user_id');
            $table->nama=$request->json('nama');
            $table->save();
        }
        
        return "Success";
    }


}
