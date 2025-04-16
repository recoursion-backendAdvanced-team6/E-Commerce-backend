<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStripeProductIdNullableInProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // stripe_product_id を nullable に変更
            $table->string('stripe_product_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // 元に戻す：NOT NULL に変更（必要な場合は default 値も指定）
            $table->string('stripe_product_id')->nullable(false)->change();
        });
    }
}
