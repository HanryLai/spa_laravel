<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Voucher;
use App\Models\VoucherBlog;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\get;

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
        DB::beginTransaction();
        try {
            $data = $request->all();
            $access_img = asset('storage/'.env('DEFAULT_IMAGE ','default.png'));
            if($request->hasFile('image') && $request->fileFile('image') != null){
                $path = $request->file('image')->store('blog_img','public');
                $access_img = asset('storage/'.$path);
            }
            
            $blog = new Blog();
            $blog->title = $data['title'];
            $blog->content = $data['content'];
            $blog->url_img = $access_img;
            $blog->save();

            $vouchers = $data['voucher'];
            $listVouchers = [];
            foreach($vouchers as $voucher){
                $new_voucher_blog = new VoucherBlogController();
                
                $result = $new_voucher_blog->createVoucher_Blog($voucher,$blog->id);
                if($result instanceof \Throwable){
                    throw $result;
                }
                else{
                    
                    $listVouchers[] = $voucher;
                }
            }

            $blog->listVoucher = $listVouchers;

            DB::commit();
            return response()->json(["message"=>"create new blog","data"=>$blog],201);
        } catch (\Throwable $th) {
            DB::rollBack();
            
            if($access_img != asset('storage/'.env('DEFAULT_IMAGE ','default.png')) ){
                Storage::delete('public/blog_img/'.basename($access_img));
            }
            return response($th);            
        }
    }

    public function updateBlog(Request $request,string $id_blog){
        try {
            DB::beginTransaction();
            // find old blog
            $blog = Blog::find($id_blog);
            //get all data update
            $data = $request->all();
            // get all vouchers new in this blog
            $vouchers_new = $data['voucher'];
            //get all vouchers in old blog
            $obj_voucherBlog = DB::table('voucher_blog')->where('blog_id',$id_blog)->get();
            $vouchers_old = [];
            foreach($obj_voucherBlog as $obj){
                $vouchers_old[] = $obj->voucher_id;
            }


            // only old voucher -> delete this voucher_blog
            $list_voucher_old = array_diff($vouchers_old,$vouchers_new);

            // echo("vouchers old: ".implode(",",$vouchers_old)."\n");
            // echo("vouchers new: ".implode(",",$vouchers_new)."\n");
            // echo("list_voucher_old: ".implode(",",$list_voucher_old)."\n");
            foreach($list_voucher_old as $voucher){
                $controller = new VoucherBlogController();
                $result = $controller->deteleVoucher_blog($voucher,$id_blog);
                if($result instanceof \Throwable){
                    throw $result;
                }
            }
            //only new voucher
            $list_voucher_new = array_diff($vouchers_new,$vouchers_old);
            foreach($list_voucher_new as $voucher){
                $controller = new VoucherBlogController();
                $result = $controller->createVoucher_Blog($voucher,$id_blog);
                if($result instanceof \Throwable){
                    throw $result;
                }
            }

            if(!$blog) throw new Error("Not found this blog",404);
            
            //check request have img 
            $requestIsImg = $request->hasFile('image');

            //check request img default
            $requestIsDefaultImg = basename($request->file('image')) == env('DEFAULT_IMAGE ','default.png');

            //check this blog container default img
            $serverIsDefaultImg = basename($blog->url_img) == env('DEFAULT_IMAGE ','default.png');


            if($requestIsImg && !$requestIsDefaultImg){
                if(!$requestIsDefaultImg){
                    Storage::delete('public/blog_img/'.basename($blog->url_img));
                }
               
                $name_img = $request->file('image')->store('blog_img','public');
                $access_img=asset('storage/'.$name_img);
            }else{
                Storage::delete("public/blog_img/".basename($blog->url_img));
                $access_img = asset('storage/'.env('DEFAULT_IMAGE ','default.png'));
            }
            



            $blog ->update([
                'title'=>$data['title'],
                'content'=>$data['content'],
                'url_img'=> $access_img 
            ]);

            $blog->list_voucher = $vouchers_new;

            DB::commit();
            return response()->json(['data'=>$blog],200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['Error'=>$th->getMessage()],500);
        }
    }

    public function deleteBlog (string $blog_id){
        try {
            DB::beginTransaction();
            $blog = Blog::find($blog_id);
            if(!$blog) throw new Error("Not found this blog",404);
            $vouchers = DB::table('voucher_blog')->where('blog_id',$blog_id)->get();
            foreach($vouchers as $voucher){
                $controller = new VoucherBlogController();
                $result = $controller->deteleVoucher_blog($voucher->voucher_id,$blog_id);
                if($result instanceof \Throwable){
                    throw $result;
                }
            }
            $blog->delete();
            DB::commit();
            return response()->json(["message"=>"delete blog success"],200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["Error"=>$th->getMessage()],$th->getCode());
        }
    }
}