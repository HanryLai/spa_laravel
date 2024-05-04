<?php

namespace App\Models;

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


}