<?php

namespace App\Http\Middleware;
use Auth;

use Closure;
use Illuminate\Http\Response;

class CheckAdminRole
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
        
        if(Auth::user()->role_id == 1){ #admin
            return $next($request);
        }else{
            return redirect('login');
        }
    }
}
