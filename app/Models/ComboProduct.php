<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComboProduct extends Model
{
    use HasUuids;
    protected $table = "combo_product";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;

    

    protected $fillable=[
        "name","description","price",'url_img'
    ];

    protected $date = [
        "created_at","updated_at"
    ];

    public function comboProductDetails():HasMany{
        return $this->hasMany(ComboProductDetail::class);
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