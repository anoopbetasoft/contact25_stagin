<?php

namespace App\Http\Middleware;
use Auth;

use Closure;
use Illuminate\Http\Response;

class CheckLoginRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		//print_r(Auth::user()->role_id);die;
		if(Auth::user()->role_id == 2){ #user
            return $next($request);
		}else{
            return redirect('login');
        }
       
    }
}
