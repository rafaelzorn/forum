<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAdmin
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
        if (Auth::guard($guard)->check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        $request->session()->flash('message',[
            'type' 	  => 'warning',
            'message' => 'You must be an administrator to see this page.'
        ]);

        return redirect()->back();
    }
}
