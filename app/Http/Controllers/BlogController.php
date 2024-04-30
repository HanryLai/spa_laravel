<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{

    public function findById(string $id_Blog){
        try {
            $blog = Blog::find($id_Blog);
            if($blog) return response()->json(["message"=>"find by id","data"=>$blog],200);
            else throw new Error("not found this blog",404);
        } catch (\Throwable $th) {
            return response()->json(["Error"=>$th->getMessage()],$th->getCode());
        }
    }

    public function findAll(){
        try {
            $blogs = Blog::all();
            if($blogs) return response()->json(["message"=>"find all","data"=>$blogs],200);
            else throw new Error("Not have any blog",404);
        } catch (\Throwable $th) {
            return response()->json(["Error"=>$th->getMessage()],$th->getCode());
        }
    }

    public function createBlog(Request $request){
        try {
            DB::beginTransaction();
            $data = $request->all();

            $access_img = asset('storage/'.env('DEFAULT_IMAGE ','default.png'));
            if($request->has('image') && $request->file('image') != null){
                $path = $request->file('image')->store('blog_img','public');
                $access_img = asset('storage/'.$path);
            }
            
            $blog = new Blog();
            $blog->title = $data['title'];
            $blog->content = $data['content'];
            $blog->url_img = $access_img;
            $blog->save();
            DB::commit();
            return response()->json(["message"=>"create new blog","data"=>$blog],201);
        } catch (\Throwable $th) {
            if(! $access_img == asset('storage/'.env('DEFAULT_IMAGE ','default.png')) ) Storage::delete('public/blog_img/'.basename($access_img));
            DB::rollBack();
            return response($th);            
        }
    }

    
}