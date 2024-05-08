<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
            $order->voucher_id = $data['voucher_id'];
            $order->total_money = 0;
            $order->save();
            if($request->has('list_product')){
                $orderProductDetail = new OrderProductDetailController();
                $list_orderProductDetail = $orderProductDetail->createOrderProductDetail($request,$order->id);
            }
            if($request->has('list_combo_product')){
                $orderComboProductDetail = new OrderComboProductDetailController();
                $list_orderComboProductDetail = $orderComboProductDetail->createOrderComboProductDetail($request,$order->id);
            }
            $order->total_money = collect($list_orderProductDetail)->sum('total_money') + collect($list_orderComboProductDetail)->sum('total_money');
            $order->save();
            $order->orderProductDetail = $list_orderProductDetail;
            $order->orderComboProductDetail = $list_orderComboProductDetail;
            DB::commit();
            return $order;
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }
    }
}