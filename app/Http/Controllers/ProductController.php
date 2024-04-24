<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
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
        if($request->hasFile('image')){
        }
        $fileName = basename($product->url_img);
        Storage::delete("storage/".$fileName);
        
        // $fileName = basename($product->url_img);
        // Storage::delete($product->image);

        return $fileName;
    }
}