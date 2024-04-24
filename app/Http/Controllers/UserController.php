<?php

namespace App\Http\Controllers;

use App\Models\User;
use ArrayObject;
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
    public function create_user(Request $request)
    {
      try {
          // return "hello world";
            $new_user = new User;
            $body = $request->all();
            $new_user->id = $body["id"];
            $new_user->username = $body["username"];
            $new_user->email = $body["email"];
            $new_user->phone = $body["phone"];
            $new_user->role = $body["role"];
            $new_user->login_at = null;
            $new_user->save();

            return $new_user->toJson();
      } catch (\Throwable $th) {
        return $th;
      }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}