<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    // get by id
    public function get_by_id($id){
        $category = Category::find($id);
        if($category == null){
            return response()->json(["message"=>"Not exist this category"],404);
        }
        return response()->json(["data"=>$category],200);
    }

    //get all category
    public function get_all(){
        $category = Category::all();
        if($category->count() == 0){
            return response()->json(["message"=>"No data"],404);
        }
        return response()->json(["data"=>$category],200);
    }
    
    //create
    public function create_category(Request $request){
        DB::beginTransaction();
        try{    
            $category = new Category();
            $category->name = $request->name;
            $category->description = $request->description;
            $category->save();
            DB::commit();
            return response()->json(["data"=>$category],201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(["error"=>$e->getMessage()],500);
        }

    }

    //update category
    public function update_category(Request $request,$id){
        DB::beginTransaction();
        try{
            $category = Category::find($id);
            if($category == null){
                return response()->json(["message"=>"Not exist this category"],404);
            }
            $category->name = $request->name;
            $category->description = $request->description;
            $category->save();
            DB::commit();
            return response()->json(["data"=>$category],200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(["error"=>$e->getMessage()],500);
        }
    }
}