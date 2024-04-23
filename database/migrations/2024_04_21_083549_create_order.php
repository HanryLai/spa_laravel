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
        Schema::create('order', function (Blueprint $table) {
            $table-> uuid('id')->primary();
            $table-> string('status_order',16);
            $table-> timestamp('booking_date');
            $table-> string('user_id',100);
            $table-> string('voucher_id')->nullable(); 
            $table-> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};