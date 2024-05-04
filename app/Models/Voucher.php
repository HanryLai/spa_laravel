<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    use HasUuids;
    protected $table="voucher";
    protected $primaryKey = 'id';
    protected $keyType="string";
    public $incrementing =false;

    protected $fillable = [
        'name','content','url_img','money_discount','percent_discount','quantity',
        'start_date','end_date'
    ];

    protected $date = [
        'created_at','updated_at'
    ];

    public function voucher_blog():HasMany{
        return $this->hasMany(VoucherBlog::class,"voucher_id");
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