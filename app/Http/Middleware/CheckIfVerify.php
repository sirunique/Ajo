<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
// use Auth;
class CheckIfVerify
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
        if(auth::guard('investor') && is_null(auth::guard('investor')->user()->email_verified_at)){
            auth::guard('investor')->logout();
            $request->session()->invalidate();
            return redirect()->route('notverify');
        }
        return $next($request);
    }
}
