<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryComboProductDetail extends Model
{
    use HasUlids;
    protected $table = 'category_combo_product_detail';
    protected $primaryKey = ['category_id','combo_product_id'];
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['category_id','combo_product_id'];
    protected $dates = ['created_at','updated_at'];

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function combo_product(){
        return $this->belongsTo(ComboProduct::class,'combo_product_id','id');
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