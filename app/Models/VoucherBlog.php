<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VoucherBlog extends Model
{
    protected $table="voucher_blog";
    protected $primaryKey = ['voucher_id','blog_id'];
    public $incrementing = false;
    protected $keyType = "string";

    protected $fillable = [
        'blog_id','voucher_id'
    ];

    protected $date = [
        "created_at","updated_at"
    ];

    public function blog():HasOne{
        return $this->hasOne(Blog::class);
    }

    public function voucher():HasOne{
        return $this->hasOne(Voucher::class);
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