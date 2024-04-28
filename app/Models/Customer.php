<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public function customer():HasOne{
        return $this->hasOne(Customer::class);
    }

    public function admin():HasOne{
        return $this->hasOne(Customer::class);
    }

    public function staff():HasOne{
        return $this->hasOne(Customer::class);
    }

    protected $date = [
        "created_at","updated_at"
    ];
}