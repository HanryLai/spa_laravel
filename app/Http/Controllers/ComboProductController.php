<?php

namespace App\Http\Controllers;

use App\Models\ComboProduct;
use App\Models\ComboProductDetail;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComboProductController extends Controller
{

    // input list_product container array id product and
    //  list quantity container int quantity of product
    public function create_combo_product(Request $request){
        try {
            $data = $request->all();
            $access_url_img = null;

            //create image
            if($request->hasFile('image')){
                $path = $request->file('image')->store('combo_product_img','public');
                $access_url_img = asset("storage/".$path);
            }
            
            //create combo-product
            $combo_product = new ComboProduct();
            $combo_product->name = $data['name'];
            $combo_product->url_img = $access_url_img;
            $combo_product->description = $data['description'];
            $combo_product->price = $data['price'];
            $combo_product->save();

            if(!$request->has('list_product')){
                throw new Error("Please check field list_product, it is empty now");
            }
            //create list combo_product_detail
            $listProduct =  collect();
            for($i = 0;$i<count($data['list_product']);$i++){
                try {
                    $detail = new ComboProductDetail();
                    $detail->product_id = $data['list_product'][$i];
                    $detail->combo_product_id = $combo_product->id;
                    if(!isset($data['quantity'][$i])){
                        throw new Error("not exist quantity product $i",404);
                    }
                    $detail->quantity = $data['quantity'][$i];
                    $detail->save();
                    $listProduct->push($detail);
                } catch (\Throwable $th) {
                    throw new Error($th,500);
                }

            }
            return response()->json(["combo-product"=>$combo_product,"list-product"=>$listProduct],200);
        } catch (\Throwable $th) {
            return response()->json(["error"=>$th->getMessage()],500);
        }
    }

    public function findComboProductById(String $id_product){
        try {
            $combo_product = ComboProduct::find($id_product);
            $combo_product->comboProductDetails;
            if($combo_product) return response()->json(["message"=>"combo product by id ".$id_product,
            "combo-product"=>$combo_product],200);
            else throw new Error("Not found this product");
        } catch (\Throwable $th) {
           return response()->json(["error"=>"product by id ".$id_product,"data"=>$th->getMessage()],500);
        }
    }

    public function getAll(){
        try {
            $combo_product = ComboProduct::all();
            $listCP = collect();
            for($i = 0;$i<count($combo_product);$i++){
                $combo = ComboProduct::find($combo_product[$i]['id']);
                $combo->comboProductDetails;
                $listCP->push($combo);
            }
            if($combo_product) return response()->json(["message"=>"combo product find all ","data"=>$listCP],200);
            else throw new Error("Not found this product");
        } catch (\Throwable $th) {
           return response()->json(["error"=>"product by id ","data"=>$th->getMessage()],500);
        }
    }

    public function update_combo_product(Request $request,String $id_product){
        $combo_product = ComboProduct::find($id_product);
        if(!$request->has('list_product')){
                throw new Error("Please check field list_product, it is empty now");
            }
        if(!$combo_product) return response()->json(["message"=>"not found this product"],404);
        $data = $request->all();
        $path_access = $data['image'];
        if($request->hasFile('image')){
            $fileName = basename($combo_product->url_img);
            if(Storage::exists("public/combo_product_img/".$fileName)){
                Storage::delete("public/combo_product_img/".$fileName);
                $path = $data['image']->store('combo_product_img','public');
                $path_access = asset('storage/'.$path);
                $combo_product->url_img = $path_access;
            }else echo ("khong ton tai img");
        }
        
        try {
            $combo_product->update([
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'url_img'=>$path_access,
            ]);
            // delete full combo_product_detail old
            ComboProductDetail::where('combo_product_id',$combo_product['id'])->delete();
            //create new list combo_product_detail
            $listProduct =  collect();
            for($i = 0;$i<count($data['list_product']);$i++){
                try {
                    $detail = new ComboProductDetail();
                    $detail->product_id = $data['list_product'][$i];
                    $detail->combo_product_id = $combo_product->id;
                    if(!isset($data['quantity'][$i])){
                        throw new Error("not exist quantity product $i",404);
                    }
                    $detail->quantity = $data['quantity'][$i];
                    $detail->save();
                    $listProduct->push($detail);
                } catch (\Throwable $th) {
                    throw new Error($th,500);
                }

            }
            
            if($combo_product->wasChanged())  return Response()->json(['message'=>"update successfully",
                "data"=>$combo_product],200);
            else return Response()->json(['message'=>"Error, make sure input not mistake field",],200);
        } catch (\Throwable $th) {
             return Response()->json(['message'=>"Error, make sure input not mistake field","error"=>$th->getMessage()],500);
            
        }
    }

    // public function deleteComboProductById(String $id_product){
    //     try {
    //         // find product
    //         $combo_product = ComboProduct::find($id_product);
    //         if(!$combo_product) throw new Error( "Not found product this product",404);
    //         //get name img product
    //         $img_access = basename($combo_product->url_img);
    //         Storage::delete("public/product_img/".$img_access);
    //         // check exist or not to confirm image product was deleted 
    //         if(Storage::exists("public/product_img/".$img_access)) throw new Error("Delete image product faild",500);
    //         $combo_product->delete();
    //         return response()->json(["message"=>"delete successfully"],200);
    //     } catch (\Throwable $th) {
    //          return Response()->json(["Error"=>$th->getMessage()],$th->getCode());
    //     } 
    // }
}