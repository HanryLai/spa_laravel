<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class CategoryProductDetail extends Model
{
    
    protected $table = 'category_product_detail';
    protected $primaryKey = ['category_id','product_id'];
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['category_id','product_id'];
    protected $dates = ['created_at','updated_at'];

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function product(){
        return $this->belongsTo(ComboProduct::class,'product_id','id');
    }
    
    protected static function boot()
    {
        parent::boot();
        static::creating(function($model){
            $model->created_at = Carbon::now('asia/Ho_Chi_Minh');
            $model->updated_at = Carbon::now('asia/Ho_Chi_Minh');
        });
    }
}