<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Statistical extends Controller
{
    public function almostOutOfStock(){
        $product = DB::table('product')->where('quantity','<',10)->get();
        return response()->json($product); 
    }

    public function totalProduct(){
        $total = DB::table('product')->count();
        return response()->json($total);
    }

    public function outOfStock(){
        $product = DB::table('product')->where('quantity','=',0)->get();
        return response()->json($product); 
    }

    public function productInStock(){
        $product = DB::table('product')->where('quantity','=',
        0)->get();
        return response()->json($product);
    }

}