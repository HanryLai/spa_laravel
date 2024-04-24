<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    
    protected $table = "user";
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $connection = 'mysql';

    protected $fillable = [
        "id",'username','email','phone','role','login_at'
    ];
    protected $date = [
        'created_at','updated_at','login_at'
    ];
    

}