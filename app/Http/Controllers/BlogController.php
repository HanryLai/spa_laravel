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

    public function updateBlog(Request $request,string $id_blog){
        try {
            DB::beginTransaction();
            $blog = Blog::find($id_blog);
            if(!$blog) throw new Error("Not found this blog",404);
            
            //check request have img 
            $requestIsImg = $request->has('image');

            //check request img default
            $requestIsDefaultImg = basename($request->file('image')) == env('DEFAULT_IMAGE ','default.png');

            //check this blog container default img
            $serverIsDefaultImg = basename($blog->url_img) == env('DEFAULT_IMAGE ','default.png');

            echo($requestIsImg."\n".$requestIsDefaultImg."\n".$serverIsDefaultImg);

            if($requestIsImg && !$requestIsDefaultImg){
                if(!$requestIsDefaultImg){
                    echo("server dont have default img");
                    Storage::delete('public/blog_img/'.basename($blog->url_img));
                }
                else echo("Default");
                $name_img = $request->file('image')->store('blog_img','public');
                $access_img=asset('storage/'.$name_img);
            }else{
                Storage::delete("public/blog_img/".basename($blog->url_img));
                $access_img = asset('storage/'.env('DEFAULT_IMAGE ','default.png'));
            }
            $data = $request->all();
            $blog ->update([
                'title'=>$data['title'],
                'content'=>$data['content'],
                'url_img'=> $access_img 
            ]);
            DB::commit();
            return response()->json(['data'=>$blog],200);
        } catch (\Throwable $th) {
            return response()->json(['Error'=>$th],200);
        }
    }
}