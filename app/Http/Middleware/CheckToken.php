<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class CheckToken
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
        $data= DB::table('tb_users')->where('api_token',$request->header('token'))->get();
        if (count($data) == 1)
        {
            return $next($request);
        }
        else
        {
            return response()->json([
                    'status' => 204,
                    'message'=>"Must be login",
                ]);
        }
        
    }
}
