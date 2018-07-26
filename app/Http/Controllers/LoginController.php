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
            if (Auth::check()) {
              if (Auth::user()->role->namaRole=="kadis")
              {
                return redirect('/home/pegawai');
              }
              elseif ((Auth::user()->role->namaRole=="pegawai"))
              {
                return redirect('/user/pegawai');
              }
              elseif ((Auth::user()->role->namaRole=="bkd")){
                return redirect('/monitoring/instansi');
              }
              // elseif ((Auth::user()->role->namaRole=="karu"))
              // {
              //   return redirect('/home');
              // }
              elseif ((Auth::user()->role->namaRole=="user") || (Auth::user()->role->namaRole=="admin") || (Auth::user()->role->namaRole=="rs"))
              {
                return redirect('/home');
              }
              elseif ((Auth::user()->role->namaRole=="karu")){
                return redirect('/home/ruangan');
              }
                //return redirect('/home');
            }

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
            elseif ((Auth::user()->role->namaRole=="karu")){
              return redirect('/home/ruangan');
            }
            elseif ((Auth::user()->role->namaRole=="bkd")){
              return redirect('/monitoring/instansi');
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
          elseif ((Auth::user()->role->namaRole=="karu")){
            return redirect('/home/ruangan');
          }
          elseif ((Auth::user()->role->namaRole=="bkd")){
            return redirect('/monitoring/instansi');
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
