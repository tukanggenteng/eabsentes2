<?php

namespace App\Http\Middleware;

use Closure;

class HakAksesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,...$namaRole)
    {
        // dd($namaRole);
    //   dd(auth()->user()->punyaRule());
    //   dd(auth()->check()." & ".auth()->user()->punyaRule($namaRole));
        if(auth()->check() && auth()->user()->punyaRule($namaRole) == true){
            return $next($request);
        }
        else
        {
            return abort(403);
        }
    }
}
