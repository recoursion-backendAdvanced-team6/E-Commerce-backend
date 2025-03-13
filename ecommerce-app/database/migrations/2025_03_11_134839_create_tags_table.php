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
        Schema::create('tags', function (Blueprint $table) {
            // 自動インクリメントの BIGINT UNSIGNED PRIMARY KEY
            $table->id();

            // name VARCHAR(100) UNIQUE NOT NULL
            $table->string('name', 100)->unique();

            // created_at と updated_at を管理するタイムスタンプ
            $table->timestamps();

            // deleted_at カラム（ソフトデリート用）
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
