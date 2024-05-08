<?php

namespace App\Http\Controllers;

use App\Models\ComboProduct;
use App\Models\OrderComboProductDetail;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderComboProductDetailController extends Controller
{
    public function createOrderComboProductDetail(array $list_combo_product, string $id_order)
    {   
        DB::beginTransaction();
        try {
            
            $list_combo_product_id = [];
            $list_quantity = [];
            foreach ($list_combo_product as $combo_product) {
                $list_combo_product_id[] = $combo_product['combo_product_id'];
                $list_quantity[] = $combo_product['quantity'];
            }
            
            $list_orderComboProductDetail = [];
            foreach($list_combo_product_id as $key => $combo_product_id){
                $orderComboProductDetail = new OrderComboProductDetail();
                $orderComboProductDetail->order_id = $id_order;
                $orderComboProductDetail->combo_product_id = $combo_product_id;
                $orderComboProductDetail->quantity = $list_quantity[$key];

                $combo_product = ComboProduct::find($combo_product_id);
                if(!$combo_product){
                    throw new Error("Combo product not found");
                }
                
                $orderComboProductDetail->total_money = $combo_product->price * $list_quantity[$key];
                $result = $orderComboProductDetail->save();
                if(!$result){
                    throw new Error("Error when create order combo product detail");
                }
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