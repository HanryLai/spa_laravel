<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
class OwnCors
{
    
    public function handle($request, Closure $next)
    {
         return $next($request)
            ->header('Access-Control-Allow-Origin', 'http://127.0.0.1:5500')
            ->header('Access-Control-Allow-Methods', '*')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Allow-Headers', 'X-CSRF-Token');
    }
}