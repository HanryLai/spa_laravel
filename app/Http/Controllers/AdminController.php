<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    //get all admin
    public function index(){
        $admins = Admin::all();
        foreach($admins as $admin){
            $admin->user;
        }
        return response()->json($admins,200);
    }

    //find by id
    public function findById($id){
        $admin = Admin::find($id);
        if($admin){
            $admin->user;
            return response()->json($admin,200);
        }
        return response()->json(["message"=>"Not found"],404);
    }
    //create admin
    public function createAdmin(Request $request){
       try {
            if($request->role!='admin'){
                return response(['message'=>'role must be admin'],400);
            }
            // create new user
            $userCtrl = new UserController();
            $user =  $userCtrl->create_user($request); 
           

            // create admin has foreign key is id user
            $admin = new Admin();
            $admin->user_id = $user->id;
            $admin->accumulated_point = null;
            $admin->save();
            
            // return user and admin
            return response()->json(["user"=>$user,"admin"=>$admin],201);
        } catch (\Throwable $th) {
           return ($th);
        }
    }

    //update accumulated point
    public function update_accumulated_point(Request $request,$admin_id){
       try{
            $admin = Admin::find($admin_id);
            if($admin){
                $admin->accumulated_point += $request->point;
                $admin->save();
                return response()->json($admin,200);
            }
            return response()->json(["message"=>"Not found"],404);
       }
         catch(\Throwable $th){
            return $th;
         }
    }
}