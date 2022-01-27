<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsManajer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle(Request $request, Closure $next)
    // {
    //     if (auth()->user()->role_id == 2) {
    //         return $next($request);
    //     }

    //     // return $next($request);
    //     return redirect('home')->with('error', "Anda Tidak Dapat Mengakses Halaman ini");
    // }

    public function handle($request, Closure $next)
    {
        if (auth()->user()->role_id == 2) {
            return $next($request);
        }

        // return $next($request);
        return redirect('home')->with('error', "Anda Tidak Dapat Mengakses Halaman ini");
    }
}
