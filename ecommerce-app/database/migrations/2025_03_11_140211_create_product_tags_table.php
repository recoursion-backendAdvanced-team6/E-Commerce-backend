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
        Schema::create('product_tags', function (Blueprint $table) {
            // 外部キーとなるカラムを定義
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('tag_id');

            // 作成日時（デフォルトで現在時刻を設定）
            $table->timestamps();

            // 削除日時（ソフトデリート用）
            $table->softDeletes();

            // 複合主キーとして、同じ組み合わせが重複しないようにする
            $table->primary(['product_id', 'tag_id']);

            // 外部キー制約：製品とタグの整合性を保つ
            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade');

            $table->foreign('tag_id')
                  ->references('id')->on('tags')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_tags');
    }
};
