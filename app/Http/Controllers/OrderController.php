<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Voucher;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Error;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function createOrder(Request $request){
        DB::beginTransaction();
        try {
            $data = $request->all();
            $order = new Order();
            $order->status_order = $data['status_order'];
            $order->destination = $data['destination'];
            $order->booking_date = $data['booking_date'];
            $order->user_id = $data['user_id'];
            $order->total_money = 0;
            $order->save();
            if($request->has('list_product')){
                $orderProductDetail = new OrderProductDetailController();
                $list_orderProductDetail = $orderProductDetail->createOrderProductDetail($data['list_product'],$order->id);
                if($list_orderProductDetail instanceof \Throwable){
                    throw $list_orderProductDetail;
                }
            }

            $list_orderComboProductDetail = [];
            if($request->has('list_combo_product')){
                $orderComboProductDetail = new OrderComboProductDetailController();
                $list_orderComboProductDetail = $orderComboProductDetail->createOrderComboProductDetail($data['list_combo_product'],$order->id);
                if($list_orderComboProductDetail instanceof \Throwable){
                    throw $list_orderComboProductDetail;
                }
            }

            $total = collect($list_orderComboProductDetail)->sum('total_money') + collect($list_orderProductDetail)->sum('total_money');
            //use voucher
            if($request->has('voucher_id')){
                //find this voucher
                $voucher = Voucher::find($request->voucher_id);
                // if exist
                if($voucher){
                    $current_datetime = new DateTime();
                    $current_datetime->setTimezone(new DateTimeZone("Asia/Ho_Chi_Minh"));

                    $end_date = new DateTime($voucher->end_date);
                    //check condition to use voucher
                    if($voucher->quantity >0 && $end_date > $current_datetime){
                        $voucher->quantity -= 1;
                        $voucher->update();
                    }
                    else{
                        throw new Error('voucher is expired or out of quantity');
                    }

                    //case use money discount

                    //discount base on money
                    
                    if($voucher->money_discount > 0){
                        $type_discount = "money";
                        $total = collect($list_orderProductDetail)->sum('total_money') + collect($list_orderComboProductDetail)->sum('total_money');
                        $total -= $voucher->money_discount;
                        if($total < 0){
                            $total = 0;
                        }
                    }
                    //discount base on percent
                    else if($voucher->percent_discount > 0 && $voucher->percent_discount <= 100){
                        $type_discount = "percent";
                        $total = collect($list_orderProductDetail)->sum('total_money') + collect($list_orderComboProductDetail)->sum('total_money');
                        $total -= $total * $voucher->percent_discount / 100;
                        if($total < 0){
                            $total = 0;
                        }
                    }
                    $order->voucher_id = $data['voucher_id'];
                }
                else{
                    throw new Error('voucher not exist');
                }
            }
            
            //not use voucher
            $order->total_money = $total;
            
            $order->save();
            $order->orderProductDetail = $list_orderProductDetail;
            $order->orderComboProductDetail = $list_orderComboProductDetail;
            DB::commit();
            return response()->json(["message"=>"create new order ","data"=>$order],201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }
    }
}