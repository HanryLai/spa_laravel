<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->foreign('user_id')->references("id")->on('user');
        });

         Schema::table('staff', function (Blueprint $table) {
            $table->foreign('user_id')->references("id")->on('user');
        });

        Schema::table('admin', function (Blueprint $table) {
            $table->foreign('user_id')->references("id")->on('user');
        });

        Schema::table('order', function (Blueprint $table) {
            $table->foreign('user_id')->references("id")->on('user');
        });

        Schema::table('voucher_blog', function (Blueprint $table) {
            $table->foreign('voucher_id')->references('id')->on('voucher');
            $table->foreign('blog_id')->references('id')->on('blog');
        });

        Schema::table('order_detail', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('order');
            $table->foreign('product_id')->references('id')->on('product');
            $table->foreign('combo_product_id')->references('id')->on('combo_product');
            // $table->unique(['order_id','product_id','combo_product_id']);
        });

        Schema::table('combo_product_detail',function(Blueprint $table){
            $table->foreign('product_id')->references('id')->on('product');
            $table->foreign('combo_product_id')->references('id')->on('combo_product');  
        });

        Schema::table('category_product_detail',function(Blueprint $table){
            $table->foreign('category_id')->references('id')->on('category');
            $table->foreign('product_id')->references('id')->on('product');
        });

        Schema::table('category_combo_product_detail',function(Blueprint $table){
            $table->foreign('category_id')->references('id')->on('category');
            $table->foreign('combo_product_id')->references('id')->on('combo_product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('customer', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('admin', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('order', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('voucher_blog', function (Blueprint $table) {
             $table->dropForeign(['blog_id']);
             $table->dropForeign(['voucher_id']);
        });

        Schema::table('order_detail', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['combo_product_id']);
        });

        Schema::table('combo_product_detail', function (Blueprint $table) {
             $table->dropForeign(['product_id']);
             $table->dropForeign(['combo_product_id']);
        });

        Schema::table('category_product_detail', function (Blueprint $table) {
             $table->dropForeign(['category_id']);
             $table->dropForeign(['product_id']);
        });

        Schema::table('category_combo_product_detail', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['combo_product_id']);
        });
    }
};