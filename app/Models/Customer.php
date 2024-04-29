<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasUlids;
    protected $table = "customer";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    protected $fillable = [
        "user_id",'accumulated_point'
    ];

    public function user():HasOne{
        return $this->hasOne(User::class);
    }

    

    protected $date = [
        "created_at","updated_at"
    ];
}