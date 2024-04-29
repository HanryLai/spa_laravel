<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ComboProductDetail extends Model
{
    protected $table = "combo_product_detail";
    protected $fillable = [
        "product_id",'combo_product_id','quantity'
    ];

    public function comboProduct():BelongsTo{
        return $this->belongsTo(ComboProduct::class);
    }

    public function product():BelongsTo{
        return $this->belongsTo(Product::class);
    }

    protected $date = [
        "created_at","updated_at"
    ];
}