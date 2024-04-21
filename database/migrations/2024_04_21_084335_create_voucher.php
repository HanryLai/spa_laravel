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
        Schema::create('voucher', function (Blueprint $table) {
            $table->string('id',100)->primary()->unique();
            $table->string('name',50);
            $table->longText('content');
            $table->double('money_discount')->nullable();
            $table->unsignedInteger('percent_discount')->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher');
    }
};