<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
          if (Auth::user()->role->namaRole=="kadis")
          {
            return redirect('/home/pegawai');
          }
          elseif ((Auth::user()->role->namaRole=="pegawai"))
          {
            return redirect('/user/pegawai');
          }
          // elseif ((Auth::user()->role->namaRole=="karu"))
          // {
          //   return redirect('/home');
          // }
          elseif ((Auth::user()->role->namaRole=="user") && (Auth::user()->role->namaRole=="rs") && (Auth::user()->role->namaRole=="admin") )
          {
            return redirect('/home');
          }
          elseif ((Auth::user()->role->namaRole=="bkd")){
            return redirect('/monitoring/instansi');
          }
          elseif ((Auth::user()->role->namaRole=="karu")){
            return redirect('/home/ruangan');
          }

            //return redirect('/home');
        }
        // dd((Auth::guard($guard)->check()));

        return $next($request);
    }
}
