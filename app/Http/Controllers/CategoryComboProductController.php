<?php

namespace App\Http\Controllers;

use App\Models\CategoryComboProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryComboProductController extends Controller
{
    //create category combo product
    public function create_category_combo_product($combo_product_id,$category_id){
        DB::beginTransaction();
        try{    
            $category_combo_product = new CategoryComboProductDetail();
            $category_combo_product->category_id = $category_id;
            $category_combo_product->combo_product_id = $combo_product_id;
            $category_combo_product->save();
            DB::commit();
            return response()->json(["data"=>$category_combo_product],201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(["error"=>$e->getMessage()],500);
        }
    }

    //delete category combo product by combo_product_id and category_id
    public function delete_category_combo_product($combo_product_id,$category_id){
        DB::beginTransaction();
        try{
            $category_combo_product = CategoryComboProductDetail::where('combo_product_id',$combo_product_id)->where('category_id',$category_id)->first();
            if($category_combo_product == null){
                return response()->json(["message"=>"Not exist this category combo product"],404);
            }
            $category_combo_product->delete();
            DB::commit();
            return response()->json(["data"=>$category_combo_product],200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(["error"=>$e->getMessage()],500);
        }
    }
    
}