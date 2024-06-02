<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComboProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\Statistical;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use App\Http\Middleware\CheckToken;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsStaffAdmin;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function(){
    //login
    Route::post('login',[AuthController::class,"login"]);

    //logout
    Route::post('logout',[AuthController::class,"logout"])->middleware(CheckToken::class);
    
});
// 
Route::prefix('user')->middleware(IsAdmin::class)->group(function(){

    //find by email
    Route::get('email/{email}',[UserController::class,"email"]);

    //get all user account 
    Route::get('',[UserController::class,"index"]);

    // get user by id
    Route::get("{id}",[UserController::class,"getById"]);

    //get information role by id
    Route::get("role/{id}",[UserController::class,"findRoleByUserId"]);

    // update user
    Route::patch("{id}/update",[UserController::class,"update"]);
});

Route::prefix('customer')->middleware(IsStaffAdmin::class)->group(function(){
    // get all customer
    Route::get('',[CustomerController::class,"index"]);
    // get account and detail information by id
    Route::get('{id}',[CustomerController::class,'getCustomerByIdUser']);
    // create user and customer
    Route::post('create-customer',[CustomerController::class,"create_customer"]);

    //update accumulated point
    Route::patch("{customer_id}/update_point",[CustomerController::class,"update_accumulated_point"]);
});

Route::prefix('admin')->middleware(IsAdmin::class)->group(function(){
    //get all admin
    Route::get('',[AdminController::class,"index"]);
    //get by id
    Route::get('{id}',[AdminController::class,"findById"]);

    //create new admin
    Route::post('create-admin',[AdminController::class,"createAdmin"]);

    //update accumulated point
    Route::patch("{admin_id}/update_point",[AdminController::class,"update_accumulated_point"]);
});

Route::prefix('staff')->middleware(IsAdmin::class)->group(function(){
    //get all staff
    Route::get('',[StaffController::class,"index"]);
    //get by id
    Route::get('{id}',[StaffController::class,"findById"]);

    //create new staff
    Route::post('create-staff',[StaffController::class,"createStaff"]);

    //update accumulated point
    Route::patch("{staff_id}/update_point",[StaffController::class,"update_accumulated_point"]);
});

Route::prefix('product')->group(function(){
    Route::get('search-name',[ProductController::class,'searchProductName']);

    //find by id
    Route::get('{id}', [ProductController::class,'findProductById']);

    // find combo product container product 
    Route::get('{id}/combo-product',[ProductController::class,'findComboProductByProductID']);

    // get category by product id
    Route::get('category/{product_id}',[ProductController::class,'findCategoryByProductId']);
    
    //find all
    Route::get('', [ProductController::class,'getAll']);

    // create new product
    Route::post('',[ProductController::class,'create_product'])->middleware(IsStaffAdmin::class);

    //update information product
    Route::post('{id}',[ProductController::class,"update_product"])->middleware(IsStaffAdmin::class);

    //delete product by id
    Route::delete("{id}",[ProductController::class,"deleteProductById"])->middleware(IsStaffAdmin::class);   
});

Route::prefix('combo-product')->group(function(){
    //find by id
    Route::get('{id}', [ComboProductController::class,'findComboProductById']);

    //find all
    Route::get('', [ComboProductController::class,'getAll']);


    // create new product
    Route::post('',[ComboProductController::class,'create_combo_product'])->middleware(IsStaffAdmin::class);

    //update information product
    Route::post('{id}',[ComboProductController::class,"update_combo_product"])->middleware(IsStaffAdmin::class);

    //delete product by id
    Route::delete("{id}",[ComboProductController::class,"deleteComboProductById"])->middleware(IsStaffAdmin::class);  
});

Route::prefix('voucher')->group(function(){

    //find all
    Route::get("all",[VoucherController::class,'findAllVoucher']);

    //find by id
    Route::get('{id}', [ComboProductController::class,'getVoucherById']);

    //find voucher available
    Route::get("available",[VoucherController::class,'findAllAvailable']);

     //find voucher unavailable
    Route::get("unavailable",[VoucherController::class,'findAllUnavailable']);

    
    //update voucher by id
    Route::post('update/{id}',[VoucherController::class,'updateVoucherById'])->middleware(IsStaffAdmin::class);

    //create new voucher
    Route::post('',[VoucherController::class,'create_voucher'])->middleware(IsStaffAdmin::class);

    //delete voucher by id
    Route::delete('{id}',[VoucherController::class,'deleteVoucherById'])->middleware(IsStaffAdmin::class);
});

Route::prefix('blog')->group(function(){
    
    //get by id
    Route::get('{id}',[BlogController::class,'findById']);

    //get all
    Route::get('',[BlogController::class,'findAll']);

    //create new blog
    Route::post('',[BlogController::class,'createBlog'])->middleware(IsStaffAdmin::class);

    //update blog
    Route::post('{id}/update',[BlogController::class,'updateBlog'])->middleware(IsStaffAdmin::class);

    //delete blog
    Route::delete('{id}',[BlogController::class,'deleteBlog'])->middleware(IsStaffAdmin::class);
    
});

Route::prefix('category')->group(function(){
    // get by id
    Route::get('{id}',[CategoryController::class,'get_by_id']);
    // get all category
    Route::get('',[CategoryController::class,'get_all']);
    //create new category
    Route::post('',[CategoryController::class,'create_category'])->middleware(IsStaffAdmin::class);
    //update category
    Route::patch('{id}',[CategoryController::class,'update_category'])->middleware(IsStaffAdmin::class);

    // delete category
    Route::delete('{id}',[CategoryController::class,'delete_category'])->middleware(IsStaffAdmin::class);

});

Route::prefix('order')->group(function(){
    //get by id
    Route::get('{id}',[OrderController::class,'findOrderById']);
    //create new order
    Route::post('',[OrderController::class,'createOrder']);
    //update status order
    Route::patch('{id}/update-status',[OrderController::class,'updateStatus'])->middleware(IsStaffAdmin::class);
});

Route::prefix('dashboard')->group(function(){
    //get total product
    Route::get("total-product-in-stock",[Statistical::class,"totalProductInStock"]);

    //get products in stock
    Route::get("products-in-stock",[Statistical::class,"productInStock"]);
    
    // product-almost-out-of-stock
    Route::get("product-almost-out-of-stock",[Statistical::class,"almostOutOfStock"]);

    // product-out-of-stock
    Route::get("product-out-of-stock",[Statistical::class,"outOfStock"]);

    //get product out of stock


});