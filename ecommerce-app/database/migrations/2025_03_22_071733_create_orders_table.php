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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // 会員の場合は user_id に値を、ゲストの場合は NULL
            $table->unsignedBigInteger('user_id')->nullable();
            // Stripe Checkout セッションID（決済連携用）
            $table->string('stripe_checkout_session_id')->collation('utf8mb4_unicode_ci');
            // 注文合計金額（税込価格など）
            $table->decimal('total_amount', 10, 2);
            // 注文ステータス
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])
                  ->collation('utf8mb4_unicode_ci')->default('pending');

            // 配送先情報（会員のデフォルト住所に合わせた項目）
            $table->string('shipping_name', 255)->collation('utf8mb4_unicode_ci');
            $table->string('shipping_email', 255)->collation('utf8mb4_unicode_ci');
            $table->string('shipping_country', 100)->collation('utf8mb4_unicode_ci');
            $table->string('shipping_zipcode', 20)->collation('utf8mb4_unicode_ci');
            $table->text('shipping_street_address')->collation('utf8mb4_unicode_ci');
            $table->string('shipping_city', 255)->collation('utf8mb4_unicode_ci');
            $table->string('shipping_phone', 20)->collation('utf8mb4_unicode_ci');

            $table->timestamps();
            $table->softDeletes();

            // 外部キー制約（ユーザーが削除された場合は orders.user_id を NULL に）
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
