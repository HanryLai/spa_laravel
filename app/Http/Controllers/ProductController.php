<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

use function League\Flysystem\InMemory\time;

class ProductController extends Controller
{
    public function create_product(Request $request){
        try {
            $data = $request->all();
            $access_url_img = null;
            if($request->hasFile('image')){
                $path = $request->file('image')->store('product_img','public');
                $access_url_img = asset("storage/".$path);
            }
            $product = new Product();
            $product->name = $data['name'];
            $product->url_img = $access_url_img;
            $product->description = $data['description'];
            $product->price = $data['price'];
            $product->save();
            return response()->json($product,200);
        } catch (\Throwable $th) {
            echo($th);
            return response()->json($th,500);
        }

    }

    public function update_product(Request $request,String $id_product){
        $product = Product::find($id_product);
        if(!$product) return response()->json(["message"=>"not found this product"],404);
        $data = $request->all();
        if($request->hasFile('image')){
            $fileName = basename($product->url_img);
            if(Storage::exists("public/product_img/".$fileName)){
                echo("Co ton tai path");
                Storage::delete("public/product_img/".$fileName);
            }else echo ("khong ton tai img");
            $path = $data['image']->store('product_img','public');
            $path_access = asset('storage/'.$path);
            $product->url_img = $path_access;
        }
        
        try {
            
            $product->name = $data['name'];
            $product->description = $data['description'];
            $product->price = $data['price'];

            echo($product->name);
            dd($product);
            Product::updated($product);

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}