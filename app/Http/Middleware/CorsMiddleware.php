<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('OPTIONS')){
            return response()->json([
                'message'=> 'Cors Pre-Flight'
            ],200,[
                'Access-Control-Allow-Origin'=>'*',
                'Access-Control-Allow-Methods'=>'GET,POST,PUT,OPTIONS,DELETE',
                'Access-Control-Allow-Headers'=>'Content-Type,X-Auth-Token,origin'
            ]);
        }
        return $next($request);
    }
}
