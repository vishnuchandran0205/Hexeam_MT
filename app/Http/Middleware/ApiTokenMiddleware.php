<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token='WW123';
        $headerToken=$request->header('token');
            if($headerToken == ""){
                
                return response()->json([
                    'responseMsg' => 'Authentication error.',
                    'responseCode' => 500,
                    'responseStatus' => false
                ]);
            }
            
            if($headerToken == $token){
                return $next($request);
            }else{
               return response()->json([
                'responseMsg' => 'Invalid params.',
                'responseCode' => 400,
                'responseStatus' => false
               ]);
            }
        
        
    }
}
