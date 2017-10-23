<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getLogin(){
            return view('welcome');
    }

    public function  postLogin(Request $request){
        if(Auth::attempt([
            'email'=>$request->username,
            'password'=>$request->password
        ])){
            return redirect('/home');
        }elseif (Auth::attempt([
            'username'=>$request->username,
            'password'=>$request->password
        ])){
            return redirect('/home');
        }else{
            return redirect()->back()->with('error', 'Login gagal !!');
        }
    }
}
