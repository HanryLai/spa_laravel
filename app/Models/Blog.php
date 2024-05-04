<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Blog extends Model
{
    use HasUlids;
    protected $table = "blog";
     protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'title','url_img','content'
    ];

    protected $date = [
        'created_at','updated_at'
    ];

    public function voucher_blog():HasMany{
        return $this->hasMany(VoucherBlog::class,'blog_id');
    }

    protected static function boot(){
        parent::boot();
        static::created(function($model){
            $model->created_at = Carbon::now("Asia/Ho_Chi_Minh");
            $model->update_at = Carbon::now("Asia/Ho_Chi_Minh");
        });
    }

}