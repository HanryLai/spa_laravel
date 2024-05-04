<?php

namespace App\Http\Controllers;

use App\Models\VoucherBlog;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoucherBlogController extends Controller
{
    // create
    public function createVoucher_Blog(string $voucher_id,string $blog_id){
        try {
            $voucher_blog = new VoucherBlog();
            $voucher_blog->blog_id = $blog_id;
            $voucher_blog->voucher_id = $voucher_id;
            $voucher_blog->save();
            

            return true;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    //delete
    public function deteleVoucher_blog(string $voucher_id,string $blog_id){
        try {
            VoucherBlog::where("voucher_id", $voucher_id)
            ->where("blog_id", $blog_id)
            ->delete();
            return true;
        } catch (\Throwable $th) {
            return $th;
        }
    }
}