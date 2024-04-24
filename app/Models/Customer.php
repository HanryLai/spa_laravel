<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
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
}