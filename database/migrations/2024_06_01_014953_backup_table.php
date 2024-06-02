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
        Schema::create('backup_product', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('price');
            $table->timestamps();
        });

        Schema::create('backup_combo_product', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('price');
            $table->timestamps();
        });

        Schema::create('backup_category', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('backup_voucher', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('content');
            $table->double('money_discount')->nullable();
            $table->double('percent_discount')->nullable();
            $table->timestamps();
        });

        Schema::create('backup_user', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('username');
            $table->string('email');
            $table->string('phone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};