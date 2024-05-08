<?php

namespace App\Http\Controllers;

use App\Models\OrderProductDetail;
use App\Models\Product;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderProductDetailController extends Controller
{
    public function createOrderProductDetail(array $list_product, string $id_order)
    {   
        DB::beginTransaction();
        try {
            
            $list_product_id =[];
            $list_quantity = [];
            foreach($list_product as $list_item){
                $list_product_id[] = $list_item['product_id'];
                $list_quantity[] = $list_item['quantity'];
            }

            $list_orderProductDetail = [];
            foreach($list_product_id as $key => $product_id){
                $orderProductDetail = new OrderProductDetail();
                $orderProductDetail->order_id = $id_order;
                $orderProductDetail->product_id = $product_id;
                $orderProductDetail->quantity = $list_quantity[$key];
                
                $product = Product::find($product_id);
                if(!$product){
                    throw new Error("Product not found");
                }
                
                $orderProductDetail->total_money = $product->price * $list_quantity[$key];
                $result = $orderProductDetail->save();
                if(!$result){
                    throw new Error("Error when create order product detail");
                }
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