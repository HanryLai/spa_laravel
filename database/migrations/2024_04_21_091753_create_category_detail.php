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
        Schema::create('category_product_detail', function (Blueprint $table) {
            $table->string('category_id',100);
            $table->string('product_id',100);
            $table->timestamps();
            $table->primary(['category_id','product_id']);
        });

        Schema::create('category_combo_product_detail', function (Blueprint $table) {
            $table->string('category_id',100);
            $table->string('combo_product_id',100);
            $table->timestamps();
            $table->primary(['category_id','combo_product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_detail');
    }
};