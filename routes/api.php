<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ComboProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

use function League\Flysystem\InMemory\time;

Route::prefix('user')->group(function(){
    //get all user account 
    Route::get('',[UserController::class,"index"]);

    // get user by id
    Route::get("{id}",[UserController::class,"getById"]);

    //get information role by id
    Route::get("role/{id}",[UserController::class,"findRoleByUserId"]);

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
    //find by id
    Route::get('{id}', [ProductController::class,'findProductById']);

    // find combo product container product 
    Route::get('{id}/combo-product',[ProductController::class,'findComboProductByProductID']);

    //find all
    Route::get('', [ProductController::class,'getAll']);

    // create new product
    Route::post('',[ProductController::class,'create_product']);

    //update information product
    Route::post('{id}',[ProductController::class,"update_product"]);

    //delete product by id
    Route::delete("{id}",[ProductController::class,"deleteProductById"]);   
});

Route::prefix('combo-product')->group(function(){
    //find by id
    Route::get('{id}', [ComboProductController::class,'findComboProductById']);

    //find all
    Route::get('', [ComboProductController::class,'getAll']);


    // create new product
    Route::post('',[ComboProductController::class,'create_combo_product']);

    //update information product
    Route::post('{id}',[ComboProductController::class,"update_combo_product"]);

    //delete product by id
    Route::delete("{id}",[ComboProductController::class,"deleteComboProductById"]);  
});

Route::prefix('voucher')->group(function(){

    //find voucher available
    Route::get("available",[VoucherController::class,'findAllAvailable']);

     //find voucher unavailable
    Route::get("unavailable",[VoucherController::class,'findAllUnavailable']);

    //find all
    Route::get("all",[VoucherController::class,'findAllVoucher']);

    //update voucher by id
    Route::post('update/{id}',[VoucherController::class,'updateVoucherById']);

    //create new voucher
    Route::post('',[VoucherController::class,'create_voucher']);


});

Route::prefix('blog')->group(function(){
    
    //get by id
    Route::get('{id}',[BlogController::class,'findById']);

    //get all
    Route::get('',[BlogController::class,'findAll']);

    //create new blog
    Route::post('',[BlogController::class,'createBlog']);

    //update blog
    
    
});