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
        Schema::create('categories', function (Blueprint $table) {
            // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->id();
            
            // name VARCHAR(100) UNIQUE NOT NULL
            $table->string('name', 100)->unique();

            // Laravel の Eloquent で自動管理するタイムスタンプ
            $table->timestamps();

            // deleted_at DATETIME NULL (ソフトデリート用)
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
