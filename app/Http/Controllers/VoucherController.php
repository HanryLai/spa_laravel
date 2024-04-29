<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VoucherController extends Controller
{
    public function create_voucher(Request $request){
        try {
            if($request->hasFile('image')){
                $path = $request->file('image')->store('voucher_img','public');
                $img_access = asset('storage/'.$path);
            }
            else{
                $img_access  = asset('storage/default.png');
            }
            
            $data = $request->all();
            $start_date = Carbon::createFromFormat('d/m/Y H:i:s',$data['start_date'] )->format('Y-m-d H:i:s');
            $start_date = Carbon::createFromFormat('d/m/Y H:i:s', $data['end_date'])->format('Y-m-d H:i:s');
    
            $voucher = new Voucher();
            $voucher->name = $data['name'];
            $voucher->content = $data['content'];
            $voucher->url_img = $img_access;
            $voucher->money_discount = $data['money_discount'];
            $voucher->percent_discount = $data['percent_discount'];
            $voucher->quantity = $data['quantity'];
            $voucher->start_date =$start_date;
            $voucher->end_date = $start_date;
            $voucher->save();
            return response()->json(["message"=>"create new voucher ",
            "data"=>$voucher],201);
        } catch (\Throwable $th) {
           return response()->json(["message"=>"create new voucher ",
            "Error"=>$th->getMessage()],500);
        }
    }
}