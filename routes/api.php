<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('customer')->group(function(){
    Route::get('get',function(){
        return "Hello World";
    });
});