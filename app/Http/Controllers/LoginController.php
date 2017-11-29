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
          // dd(Auth::user()->role->namaRole="kadis");
            if (Auth::user()->role->namaRole=="kadis"){
              return redirect('/home/pegawai');
            }
            elseif (Auth::user()->role->namaRole=="sekda"){
              return redirect('/dashboard');
            }
            elseif (Auth::user()->role->namaRole=="pegawai"){
              return redirect('/user/pegawai');
            }
            elseif (Auth::user()->role->namaRole=="gubernur"){
              return redirect('/dashboard/gub');
            }
            else {
              return redirect('/home');
            }
        }elseif (Auth::attempt([
            'username'=>$request->username,
            'password'=>$request->password
        ])){
          // dd(Auth::user()->role->namaRole="kadis");
          if (Auth::user()->role->namaRole=="kadis"){
            return redirect('/home/pegawai');
          }
          elseif (Auth::user()->role->namaRole=="sekda"){
            return redirect('/dashboard');
          }
          elseif (Auth::user()->role->namaRole=="pegawai"){
            return redirect('/user/pegawai');
          }
          elseif (Auth::user()->role->namaRole=="gubernur"){
            return redirect('/dashboard/gub');
          }
          else {
            // dd(Auth::user()->role->namaRole="pegawai");
            return redirect('/home');
          }
        }else{
            return redirect()->back()->with('error', 'Login gagal !!');
        }
    }
}
