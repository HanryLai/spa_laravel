<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\json;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = DB::table('customer')
        ->join('user','customer.user_id',"=","user_id")
        ->select("user.*","customer.*")
        ->get();

        return response($customers,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create_customer(Request $request)
    {
        try {
            // create new user
            if($request->role != 'customer'){
                return response(['message'=>'role must be customer'],400);
            }
            $userCtrl = new UserController();
            $user =  $userCtrl->create_user($request); 
            echo($user->id);

            // create customer has foreign key is id user
            $customer = new Customer();
            $customer->user_id = $user->id;
            $customer->accumulated_point = null;
            $customer->save();
            
            // return user and customer
            $result = json_decode($user,true) + json_decode($customer,true);
            return  json_encode($result);
        } catch (\Throwable $th) {
            throw $th;
        }
        

    }

    /**
     * Display the specified resource.
     */
    public function getCustomerByIdUser(String $id)
    {
        $userCtrl = new UserController();
        $user = $userCtrl->getById($id);
        // echo($user);
        if(!$user){
            return response(['message'=>'not found this user'],404);
        }
        $customer = new Customer();
        $customer = Customer::where('user_id', $user->id)->first();
        // $customer = User::find($user->id)->customer;
        if(!$customer) return  response(['message'=>'not found this customer'],404);
        return response(['user'=>$user,'customer'=>$customer],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_accumulated_point(Request $request,string $customer_id)
    {
        try {
            $point = $request->input('point');
            $customer = DB::table('customer')->select("customer.*")
            ->where("id","=",$customer_id)->first();
            
            if($customer->accumulated_point == null){
                $customer->accumulated_point = 0;
            }
            $customer->accumulated_point+=$point;
            $update_customer = DB::table('customer')->where('id',$customer_id)
            ->update(['accumulated_point'=>$customer->accumulated_point]);
            return response()->json($customer,200);
        } catch (\Throwable $th) {
            return response()->json($th,500);
        }
    }

    
}