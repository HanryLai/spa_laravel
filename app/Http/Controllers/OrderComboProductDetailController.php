<?php

namespace App\Http\Controllers;

use App\Models\ComboProduct;
use App\Models\OrderComboProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderComboProductDetailController extends Controller
{
    public function createOrderComboProductDetail(Request $request, string $id_order)
    {   
        DB::beginTransaction();
        try {
            $data = $request->all();
            $list_product_id = $data['product_id'];
            $list_quantity = $data['quantity'];
            $list_orderComboProductDetail = [];
            foreach($list_product_id as $key => $product_id){
                $orderComboProductDetail = new OrderComboProductDetail();
                $orderComboProductDetail->order_id = $id_order;
                $orderComboProductDetail->product_id = $product_id;
                $orderComboProductDetail->quantity = $list_quantity[$key];

                $product = ComboProduct::find($product_id);
                
                $orderComboProductDetail->total_money = $product->price * $list_quantity[$key];
                $orderComboProductDetail->save();
                $list_orderComboProductDetail[] = $orderComboProductDetail;
            }
            DB::commit();
            return $list_orderComboProductDetail;
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }
    }
}