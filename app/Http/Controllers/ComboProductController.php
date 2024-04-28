<?php

namespace App\Http\Controllers;

use App\Models\ComboProduct;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComboProductController extends Controller
{
    public function create_combo_product(Request $request){
        try {
            $data = $request->all();
            $access_url_img = null;
            if($request->hasFile('image')){
                $path = $request->file('image')->store('combo_product_img','public');
                $access_url_img = asset("storage/".$path);
            }
            $combo_product = new ComboProduct();
            $combo_product->name = $data['name'];
            $combo_product->url_img = $access_url_img;
            $combo_product->description = $data['description'];
            $combo_product->price = $data['price'];
            $combo_product->save();
            return response()->json($combo_product,200);
        } catch (\Throwable $th) {
            return response()->json(["error"=>$th->getMessage()],500);
        }
    }

    public function findComboProductById(String $id_product){
        try {
            $combo_product = ComboProduct::find($id_product);
            if($combo_product) return response()->json(["message"=>"product by id ".$id_product,"data"=>$combo_product],200);
            else throw new Error("Not found this product");
        } catch (\Throwable $th) {
           return response()->json(["error"=>"product by id ".$id_product,"data"=>$th->getMessage()],500);
        }
    }

    public function getAll(){
        try {
            $combo_product = ComboProduct::all();
            if($combo_product) return response()->json(["message"=>"product by id ","data"=>$combo_product],200);
            else throw new Error("Not found this product");
        } catch (\Throwable $th) {
           return response()->json(["error"=>"product by id ","data"=>$th->getMessage()],500);
        }
    }

    public function update_combo_product(Request $request,String $id_product){
        $combo_product = ComboProduct::find($id_product);
        if(!$combo_product) return response()->json(["message"=>"not found this product"],404);
        $data = $request->all();
        $path_access = $data['image'];
        if($request->hasFile('image')){
            $fileName = basename($combo_product->url_img);
            if(Storage::exists("public/combo_product_img/".$fileName)){
                echo("Co ton tai path");
                Storage::delete("public/combo_product_img/".$fileName);
                $path = $data['image']->store('product_img','public');
                $path_access = asset('storage/'.$path);
                $combo_product->url_img = $path_access;
            }else echo ("khong ton tai img");
        }
        
        try {
            $combo_product->update([
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'url_img'=>$path_access
            ]);
            if($combo_product->wasChanged())  return Response()->json(['message'=>"update successfully",
                "data"=>$combo_product],200);
            else return Response()->json(['message'=>"Error, make sure input not mistake field",],200);
        } catch (\Throwable $th) {
             return Response()->json(['message'=>"Error, make sure input not mistake field","error"=>$th->getMessage()],500);
            
        }
    }

    public function deleteComboProductById(String $id_product){
        try {
            // find product
            $combo_product = ComboProduct::find($id_product);
            if(!$combo_product) throw new Error( "Not found product this product",404);
            //get name img product
            $img_access = basename($combo_product->url_img);
            Storage::delete("public/product_img/".$img_access);
            // check exist or not to confirm image product was deleted 
            if(Storage::exists("public/product_img/".$img_access)) throw new Error("Delete image product faild",500);
            $combo_product->delete();
            return response()->json(["message"=>"delete successfully"],200);
        } catch (\Throwable $th) {
             return Response()->json(["Error"=>$th->getMessage()],$th->getCode());
        } 
    }
}