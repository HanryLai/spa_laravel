<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('customer')->group(function(){
    Route::get('get',function(){
        return "Hello World";
    });

    Route::get('all',[UserController::class,"index"]);

    Route::post('create-customer',[UserController::class,"create_user"]);

    
});