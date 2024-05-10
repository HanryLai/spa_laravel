<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //create admin
    public function createAdmin(Request $request){
        $data = $request->all();
        $admin = new Admin();
        $admin->name = $data['name'];
        $admin->email = $data['email'];
        $admin->password = Hash::make($data['password']);
        $admin->save();
        return response()->json(["message"=>"create admin success","data"=>$admin],200);
    }
}