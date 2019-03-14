<?php

namespace App\Http\Middleware;

use Closure;

class MyMiddleware
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
        if ($request->has('email') && $request->has('password'))
        {
            return $next($request);
        }
        else
        {
            return redirect('/');
        } 
           
            
    }
}
