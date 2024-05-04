<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Admin extends Model
{
   use HasUlids;
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = "false";
    protected $fillable = [
        "user_id",'accumulated_point'
    ];

    protected $date = [
        "created_at","updated_at"
    ];

    public function user():HasOne{
        return $this->hasOne(User::class);
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