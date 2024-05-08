<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class OrderProductDetail extends Model
{
    use HasUlids;
    protected $table = "order_product_detail";
    protected $primaryKey = ["order_id","product_id"];
    protected $keyType = "string";
    public $incrementing = false;
    protected $fillable = [
        "order_id",'product_id','quantity','total_money'
    ];

    protected $dates = [
        "created_at","updated_at"
    ];

    public function order(){
        return $this->belongsTo(Order::class,'order_id','id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function($model){
            $model->created_at = Carbon::now("Asia/Ho_Chi_Minh");
            $model->updated_at = Carbon::now("Asia/Ho_Chi_Minh");
        });
    }


}