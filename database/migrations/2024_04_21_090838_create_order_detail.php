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
        Schema::create('order_product_detail', function (Blueprint $table) {
            $table->string('order_id', 100);
            $table->string('product_id',100);
            $table->unsignedInteger('quantity');
            $table->double("total_money");
            $table->timestamps();
            $table->primary(['order_id', 'product_id']);
        });

        Schema::create('order_combo_product_detail', function (Blueprint $table) {
            $table->string('order_id', 100);
            $table->string('combo_product_id',100);
            $table->unsignedInteger('quantity');
            $table->double("total_money");
            $table->timestamps();
            $table->primary(['order_id', 'combo_product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product_detail');
        Schema::dropIfExists('order_combo_product_detail');
    }
};