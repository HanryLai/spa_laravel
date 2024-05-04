<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasUlids;
    protected $table = "category";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    protected $fillable = [
        "name","description"
    ];

    protected $dates = ["created_at","updated_at"];

    protected static function boot(){
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = Carbon::now('asia/Ho_Chi_Minh');
            $model->updated_at = Carbon::now('asia/Ho_Chi_Minh');
        });
    }
}