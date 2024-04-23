<?php

use App\Models\User;
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
        Schema::create('user', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username',32);
            $table->string('email',15)->unique();
            $table->string('phone',15)->unique();
            $table->string('role',8);
            $table->timestamp('login_at');
            $table->timestamps();
        });

        User::setTable('user');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};