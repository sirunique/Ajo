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
        if(auth::guard('admin')->check()){
            return redirect()->route('admin.admin');
        }
        else if(auth::guard('investor')->check()){
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}
