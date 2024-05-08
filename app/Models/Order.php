<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasUlids;
    protected $table = 'order';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'status_order',
        'destination',
        'user_id',
        'voucher_id',
        'total_money'
    ];
    protected $datas = [
        'created_at',
        'updated_at'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = Carbon::now('Asia/Ho_Chi_Minh');
            $model->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        });
    }
}