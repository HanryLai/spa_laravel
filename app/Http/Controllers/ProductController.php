<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ComboProduct;
use App\Models\Product;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            return response()->json(["error"=>$th->getMessage()],500);
        }
    }

    public function findProductById(String $id_product){
        try {
            $product = Product::find($id_product);
            if($product) return response()->json(["message"=>"product by id ".$id_product,"data"=>$product],200);
            else throw new Error("Not found this product");
        } catch (\Throwable $th) {
           return response()->json(["error"=>"product by id ".$id_product,"data"=>$th->getMessage()],500);
        }
    }

    public function findComboProductByProductID(String $product_id){
        try {
            $product = Product::find($product_id);
            $details = $product->comboProductDetail;
            $ComboProduct=[];
            for($i = 0;$i<count($details);$i++){
                $ComboProduct[] = $details[$i]->comboProduct;
            }
            
            if($product)  return response()->json(["message"=>"combo product container product id".$product_id,"data"=>$ComboProduct],200);
        } catch (\Throwable $th) {
             return response()->json(["error"=>"combo product container product id ".$product_id,"data"=>$th->getMessage()],500);
        }
    }

    public function getAll(){
        try {
            $product = Product::all();
            if($product) return response()->json(["message"=>"product by id ","data"=>$product],200);
            else throw new Error("Not found this product");
        } catch (\Throwable $th) {
           return response()->json(["error"=>"product by id ","data"=>$th->getMessage()],500);
        }
    }



    public function update_product(Request $request,String $id_product){
        $product = Product::find($id_product);
        if(!$product) return response()->json(["message"=>"not found this product"],404);
        $data = $request->all();
        $path_access = $data['image'];
        if($request->hasFile('image')){
            $fileName = basename($product->url_img);
            if(Storage::exists("public/product_img/".$fileName)){
                echo("Co ton tai path");
                Storage::delete("public/product_img/".$fileName);
                $path = $data['image']->store('product_img','public');
                $path_access = asset('storage/'.$path);
                $product->url_img = $path_access;
            }else echo ("khong ton tai img");
        }
        
        try {
            $product->update([
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'url_img'=>$path_access
            ]);
            if($product->wasChanged())  return Response()->json(['message'=>"update successfully",
                "data"=>$product],200);
            else return Response()->json(['message'=>"Error, make sure input not mistake field",],200);
        } catch (\Throwable $th) {
             return Response()->json(['message'=>"Error, make sure input not mistake field","error"=>$th->getMessage()],500);
            
        }
    }

    public function deleteProductById(String $id_product){
        try {
            // find product
            $product = Product::find($id_product);
            if(!$product) throw new Error( "Not found product this product",404);
            //get name img product
            $img_access = basename($product->url_img);
            Storage::delete("public/product_img/".$img_access);
            // check exist or not to confirm image product was deleted 
            if(Storage::exists("public/product_img/".$img_access)) throw new Error("Delete image product faild",500);
            $product->delete();
            return response()->json(["message"=>"delete successfully"],200);
        } catch (\Throwable $th) {
             return Response()->json(["Error"=>$th->getMessage()],$th->getCode());
        } 
    }
}