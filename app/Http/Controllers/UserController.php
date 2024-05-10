<?php

namespace App\Http\Controllers;
use App\Models\User;
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
        try {
          $users = DB::table('user')->get();
          if(!$users) throw new Error("Not exist any user",404);
          return response()->json(["Message"=>"List users","data"=>$users],200);
        } catch (\Throwable $th) {
          return response()->json(["Error"=>$th->getMessage()],$th->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create_user(Request $request):User
    {
      try {
            $new_user = new User;
            $body = $request->all();
            if($request->has('id')){
              $new_user->id = $body["id"];
            }
            $new_user->username = $body["username"];
            $new_user->email = $body["email"];
            $new_user->phone = $body["phone"];
            $new_user->role = $body["role"];
            $new_user->login_at = null;
            // password exist (password use for admin,staff or customer of website)
            if($request->has('password') && $body['role'] != 'customer'){
              $new_user->password = bcrypt($body["password"]);
            }
            else if( $body['role'] == 'customer'){
              $new_user->password = null;
            }
            else{
              throw new Error("Password is required",400);
            }

            $new_user->save();
            // dd($new_user);
            return $new_user;
      } catch (\Throwable $th) {
        throw $th;
      }
    }

    /**
     * Display the user by id.
     */
    public function getById(String $id)
    {
      return  $user = User::find($id);
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
    
    public function findRoleByUserId(String $id){
      return $customer = User::find($id)->customer;
    }

    // Authorization
    public function login(Request $request){
      $jwtController = new JWTController();
      $jwt = $jwtController->generateJWT($request->id,$request->role);
      return response()->json(["token"=>$jwt],200);
    }

    public function verify(Request $request){
      $jwtController = new JWTController();
      $token = $request->header('authorization');
      $token = explode(' ',$token)[1];
      $verify = $jwtController->verityJWT($token);
      return response()->json(["verify"=>$verify],200);
    }
  }