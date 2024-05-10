<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Admin extends Model
{
   use HasUlids;
    protected $table = "admin";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = "false";
    protected $fillable = [
        "user_id",'accumulated_point'
    ];

    protected $dates = [
        "created_at","updated_at"
    ];

    public function user():BelongsTo{
        return $this->belongsTo(User::class,"user_id","id");
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