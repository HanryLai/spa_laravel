<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
     //get all staff
    public function index(){
        $staffs = Staff::all();
        foreach($staffs as $staff){
            $staff->user;
        }
        return response()->json($staffs,200);
    }

    //find by id
    public function findById($id){
        $staff = Staff::find($id);
        if($staff){
            $staff->user;
            return response()->json($staff,200);
        }
        return response()->json(["message"=>"Not found"],404);
    }
    //create staff
    public function createStaff(Request $request){
       try {
            if($request->role!='staff'){
                return response(['message'=>'role must be staff'],400);
            }
            // create new user
            $userCtrl = new UserController();
            $user =  $userCtrl->create_user($request); 
           

            // create staff has foreign key is id user
            $staff = new staff();
            $staff->user_id = $user->id;
            $staff->accumulated_point = null;
            $staff->save();
            
            // return user and staff
            return response()->json(["user"=>$user,"staff"=>$staff],201);
        } catch (\Throwable $th) {
           return ($th);
        }
    }

    //update accumulated point
    public function update_accumulated_point(Request $request,$staff_id){
       try{
            $staff = staff::find($staff_id);
            if($staff){
                $staff->accumulated_point += $request->point;
                $staff->save();
                return response()->json($staff,200);
            }
            return response()->json(["message"=>"Not found"],404);
       }
         catch(\Throwable $th){
            return $th;
         }
    }
}