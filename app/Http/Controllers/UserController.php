<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use ArrayObject;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = DB::table('user')->get();
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create_user(Request $request):User
    {
      try {
            $new_user = new User;
            $body = $request->all();
            $new_user->id = $body["id"];
            $new_user->username = $body["username"];
            $new_user->email = $body["email"];
            $new_user->phone = $body["phone"];
            $new_user->role = $body["role"];
            $new_user->login_at = null;
            $new_user->save();

            return $new_user;
      } catch (\Throwable $th) {
        return $th;
      }
    }

    /**
     * Display the user by id.
     */
    public function getById(String $id)
    {
        try {
            $user = User::find($id);
            if(!$user) throw new Error("User not found",404);
            return response()->json(["data"=>$user],200);
        } catch (\Throwable $th) {
            return response()->json(["Error"=>$th->getMessage()],$th->getCode());
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $body = $request->all();
        $user_current = User::find($id);
        if(!$user_current){
            return response()->json(['message'=>'user not found'],404);
        };

        $body['id'] = $user_current->id;
        $user_current->update($body);
        return $user_current;
    }
    
    
}