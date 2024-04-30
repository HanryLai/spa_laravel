<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Carbon\Carbon;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VoucherController extends Controller
{
    public function create_voucher(Request $request){
        try {
            DB::beginTransaction();
            if($request->hasFile('image')){
                $path = $request->file('image')->store('voucher_img','public');
                $img_access = asset('storage/'.$path);
            }
            else{
                $img_access  = asset('storage/'.env('DEFAULT_IMAGE','default.png'));
            }
            
            $data = $request->all();
            $start_date = Carbon::createFromFormat('d/m/Y H:i:s',$data['start_date'] )->format('Y-m-d H:i:s');
            $end_date = Carbon::createFromFormat('d/m/Y H:i:s', $data['end_date'])->format('Y-m-d H:i:s');
    
            $voucher = new Voucher();
            $voucher->name = $data['name'];
            $voucher->content = $data['content'];
            $voucher->url_img = $img_access;
            $voucher->money_discount = $data['money_discount'];
            $voucher->percent_discount = $data['percent_discount'];
            $voucher->quantity = $data['quantity'];
            $voucher->start_date =$start_date;
            $voucher->end_date = $end_date;
            $voucher->save();
            DB::commit();
            return response()->json(["message"=>"create new voucher ",
            "data"=>$voucher],201);
        } catch (\Throwable $th) {
            if($request->hasFile('image')){
                Storage::delete("public/".$path);
            }
            DB::rollBack();
           return response()->json(["message"=>"create new voucher ",
            "Error"=>$th->getMessage()],500);
        }
    }

    public function findVoucherById(String $id_voucher){
        try {
            $voucher = Voucher::find($id_voucher);            
            if($voucher) return response()->json(["message"=>"find voucher by id","data"=>$voucher],200);
            else throw new Error("voucher not existed",404);
        } catch (\Throwable $th) {
            return response()->json(["Error"=>$th->getMessage()],$th->getCode());
        }
    }

    public function findAllAvailable(){
        try {
            $vouchers = Voucher::where('end_date','>',now())
            ->where('quantity','>',0)->get();
            if($vouchers) return response()->json(["message"=>"find all voucher available","data"=>$vouchers],200);
            else throw new Error("voucher not existed",404);
        } catch (\Throwable $th) {
            return response()->json(["Error"=>$th->getMessage()],$th->getCode());
        }
    }

    public function findAllUnavailable(){
        try {
            $vouchers = Voucher::where('end_date','<',now())
            ->orWhere('quantity','=',0)->get();
            if($vouchers) return response()->json(["message"=>"find all voucher Unavailable","data"=>$vouchers],200);
            else throw new Error("voucher not existed",404);
        } catch (\Throwable $th) {
            return response()->json(["Error"=>$th->getMessage()],$th->getCode());
        }
    }

    public function findAllVoucher(){
        try {
            $vouchers = Voucher::all();
            if($vouchers) return response()->json(["message"=>"find all voucher ","data"=>$vouchers],200);
            else throw new Error("Not have any voucher",404);
        } catch (\Throwable $th) {
           return response()->json(["Error"=>$th->getMessage()],$th->getCode());
        }
    }

    public function updateVoucherById(Request $request,String $id_voucher){
        try {
            $voucher = Voucher::find($id_voucher);
            if(!$voucher) throw new Error('voucher not existed',404);
            $access_img = $voucher->url_img;
            //check req has img
            $isRequestImg =$request->has('image');
            
            //check req has img default
            $isDefaultImg = basename($request->file('image')) ==  env('DEFAULT_IMAGE','default.png');

            //check server has img
            $serverIsImg =  basename($voucher->url_img) ==  env('DEFAULT_IMAGE','default.png');

            echo($isRequestImg . "\n".$isDefaultImg."\n".$serverIsImg."\n");

            //check request have img and it diff default:do if:do else
            if($isRequestImg&&!$isDefaultImg){
                //check server dont have default img:do if :do else
                if(!$serverIsImg){
                    echo(" server dont have default img");
                    Storage::delete('public/voucher_img/'.basename($voucher->url_img));
                }
                else echo("default");
                $name_img = $request->file('image')->store('voucher_img','public');
                $access_img = asset('storage/'.$name_img);
            }else{
                Storage::delete("public/voucher_img/".basename($voucher->url_img));
                $access_img = asset('storage/'.env('DEFAULT_IMAGE','default.png'));
            }
            $data = $request->all();
            $start_date = Carbon::createFromFormat('d/m/Y H:i:s',$data['start_date'] )->format('Y-m-d H:i:s');
            $end_date = Carbon::createFromFormat('d/m/Y H:i:s', $data['end_date'])->format('Y-m-d H:i:s');
            $voucher->update([
                'name' => $data['name'],
                'content' => $data['content'],
                'money_discount' => $data['money_discount'],
                'percent_discount' => $data['percent_discount'],
                'quantity' => $data['quantity'],
                'url_img' => $access_img,
                'start_date' => $start_date,
                'end_date' => $end_date 
            ]);

            if($voucher->wasChanged()) return response()->json(["message"=>"update voucher successfully","data"=>$voucher],200);
            else throw new Error("not thing update",200);
        } catch (\Throwable $th) {
            return response()->json(["Error"=>$th->getMessage()],$th->getCode());
        }
    }
}