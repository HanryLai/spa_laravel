<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function League\Flysystem\InMemory\time;

Route::prefix('user')->group(function(){
    //get all user account 
    Route::get('',[UserController::class,"index"]);

    // get user by id
    Route::get("{id}",[UserController::class,"getById"]);

    // update user
    Route::patch("{id}/update",[UserController::class,"update"]);
});

Route::prefix('customer')->group(function(){
    // get all customer
    Route::get('',[CustomerController::class,"index"]);
    // get account and detail information by id
    Route::get('{id}',[CustomerController::class,'getCustomerByIdUser']);
    // create user and customer
    Route::post('create-customer',[CustomerController::class,"create_customer"]);

    //update accumulated point
    Route::patch("{customer_id}/update_point",[CustomerController::class,"update_accumulated_point"]);
});

Route::prefix('product')->group(function(){
    // create new product
    Route::post('',[ProductController::class,'create_product']);

    //update information product
    Route::patch('{id}',[ProductController::class,"update_product"]);
});

Route::post("file",function(Request $request){
    if ($request->hasFile('photo')) {
        // Lưu file vào thư mục public/images
        $path = $request->file('photo')->store('images', 'public');

        // Trả về đường dẫn của file đã tải lên để hiển thị hoặc lưu vào cơ sở dữ liệu
        return $path;
    }
    return 'No file uploaded';
});