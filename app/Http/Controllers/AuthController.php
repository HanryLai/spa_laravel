<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    
    //login 
    public function login(Request $request)
    {
        try{
            if(!($request->has('email')&&$request->has('password'))){
                return response()->json([
                    'message' => 'Email and password are required'
                ],400);
            }
            $data = $request->all();
            $email = $data['email'];
            $password = $data['password'];
            
            $user = User::where('email',$email)->first();
            if($user === null){
                return response()->json([
                    'message' => 'user is not exist'
                ],400);
            }
            //check password
            $vaildPassword = Hash::check($password, $user->password);
            if(!$vaildPassword){
                return response()->json([
                    'message' => 'Password is not correct'
                ],400);
            }
            //generate token
            $jwtController = new JWTController();
            $token = $jwtController->generateJWT($user->id,$user->role,false);
            $user->access_token = $token;
            $user->refresh_token = $jwtController->generateJWT($user->id,$user->role,true);
            $user->save();
            return  response()->json([
                'message' => 'Login success',
                'data' => $user
            ],200)->cookie('access_token',$token,60*3);
           
        }
        catch(\Throwable $th){
        return response()->json([
                'message' => 'Login fail'
            ],400);
        }
    }

    public function logout(Request $request)
    {
        try{
            $token_input = $request->cookie('access_token');
            $user = User::where('access_token',$token_input)->first();
            if($user === null){
                return response()->json([
                    'message' => 'User is not exist'
                ],400);
            }
            $user->access_token = null;
            $user->refresh_token = null;
            $user->save();
            return response()->json([
                'message' => 'Logout success'
            ],200);
        }
        catch(\Throwable $th){
            return $th;
        }
    }
}