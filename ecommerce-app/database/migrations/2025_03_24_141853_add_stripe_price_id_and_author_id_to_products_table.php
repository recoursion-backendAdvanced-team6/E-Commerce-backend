<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('stripe_price_id')->nullable()->after('stripe_product_id');
            $table->unsignedBigInteger('author_id')->nullable()->after('stripe_price_id');

            // 外部キー制約（著者が削除された場合、NULL にするか、cascade するか検討）
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn(['stripe_price_id', 'author_id']);
        });
    }
};
