<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model 
{
    use HasUlids;
    protected $table = "user";
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        "id",'username','email','phone','role','login_at'
    ];
    protected $dates = [
        'created_at','updated_at','login_at'
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    public function customer():HasOne{
        return $this->hasOne(Customer::class);
    }

    protected static function boot()
    {
        
        parent::boot();
        static::creating(function($model){
            $model->created_at = Carbon::now('Asia/Ho_Chi_Minh');
            $model->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        });
    }
    
    

}