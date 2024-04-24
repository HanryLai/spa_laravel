<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    
    protected $table = "user";
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        "id",'username','email','phone','role','login_at'
    ];
    protected $date = [
        'created_at','updated_at','login_at'
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    protected static function boot()
    {
        
        parent::boot();
        static::creating(function($model){
            $model->created_at = Carbon::now('Asia/Ho_Chi_Minh');
            $model->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        });
    }
    

}