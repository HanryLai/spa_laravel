<?php

namespace App\Http\Middleware;

use App\Http\Controllers\JWTController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsStaffAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->cookie("access_token")){
            $authrization = $request->cookie("access_token");
            $jwtController = new JWTController();
            $result = $jwtController->verityJWT($authrization);
            if($result == false){
                return response()->json([
                    'message' => 'Unauthorized'
                ],401);
            }
            else{
                if($result['role'] == 'staff'||$result['role'] == 'admin')
                    return $next($request);
            }
        }
        return response()->json(["message"=>"Token is not exist"],401);
    }
}