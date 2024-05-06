<?php

namespace App\Http\Controllers;

use App\Models\CategoryProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryProductController extends Controller
{
    //create category product 
    public function create_category_product($product_id,$category_id){
        DB::beginTransaction();
        try{    
            $category_product = new CategoryProductDetail();
            $category_product->category_id = $category_id;
            $category_product->product_id = $product_id;
            $category_product->save();
            DB::commit();
            return $category_product;
        }catch(\Throwable $th){
            DB::rollBack();
            return $th;
        }

    }

    //delete category product by product_id and category_id
    public function delete_category_product($product_id,$category_id){
        DB::beginTransaction();
        try{
            $category_product = CategoryProductDetail::where('product_id',$product_id)->where('category_id',$category_id)->first();
            if($category_product == null){
                return response()->json(["message"=>"Not exist this category product"],404);
            }
            $category_product->delete();
            DB::commit();
            return true;
        }catch(\Throwable $th){
            DB::rollBack();
            return $th;
        }
    }

}