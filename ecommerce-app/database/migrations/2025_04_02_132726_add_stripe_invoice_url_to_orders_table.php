<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStripeInvoiceUrlToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // 請求書のURLを保存するカラム
            $table->string('stripe_invoice_url')->nullable()->after('shipping_phone');
            // 必要なら、Invoice ID を保存するカラムも追加
            $table->string('stripe_invoice_id')->nullable()->after('stripe_invoice_url');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['stripe_invoice_url', 'stripe_invoice_id']);
        });
    }
}
