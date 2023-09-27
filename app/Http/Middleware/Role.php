<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$role)
    {
        //var_dump($role);
        $cek=0;
        foreach($role as $cekrole){
            if ($request->user()->role == $cekrole) {
                $cek=$cek+1;
            }
        }

        if($cek>0){
            return $next($request);
           
        }
        else{
        abort(403, 'Anda tidak memiliki hak mengakses halaman ini!');}
    }
}
