<?php

namespace App\Http\Controllers;

use App\Models\OrderProductDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderProductDetailController extends Controller
{
    public function createOrderProductDetail(Request $request, string $id_order)
    {   
        DB::beginTransaction();
        try {
            $data = $request->all();
            $list_product_id = $data['product_id'];
            $list_quantity = $data['quantity'];
            $list_orderProductDetail = [];
            foreach($list_product_id as $key => $product_id){
                $orderProductDetail = new OrderProductDetail();
                $orderProductDetail->order_id = $id_order;
                $orderProductDetail->product_id = $product_id;
                $orderProductDetail->quantity = $list_quantity[$key];

                $product = Product::find($product_id);
                
                $orderProductDetail->total_money = $product->price * $list_quantity[$key];
                $orderProductDetail->save();
                $list_orderProductDetail[] = $orderProductDetail;
            }
            DB::commit();
            return $list_orderProductDetail;
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }
    }
}