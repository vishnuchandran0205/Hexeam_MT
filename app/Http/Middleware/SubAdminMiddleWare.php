<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubAdminMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->session()->get('userId');
        $user_type = $request->session()->get('user_type');

        $Admin_usertype=2;
        if($Admin_usertype == $user_type){
            return $next($request);
        }else{
            return response()->view('unauthorized', [], 403);
        }
    }
}
