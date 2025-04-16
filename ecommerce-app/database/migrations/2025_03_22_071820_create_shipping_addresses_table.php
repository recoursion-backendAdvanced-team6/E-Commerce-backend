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
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            // 会員専用の住所なので、必ずユーザーに紐づく
            $table->unsignedBigInteger('user_id');
            $table->string('name', 255)->collation('utf8mb4_unicode_ci');
            $table->string('zipcode', 20)->collation('utf8mb4_unicode_ci');
            $table->string('city', 255)->collation('utf8mb4_unicode_ci');
            $table->text('street_address')->collation('utf8mb4_unicode_ci');
            $table->string('phone', 20)->collation('utf8mb4_unicode_ci');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_addresses');
    }
};
