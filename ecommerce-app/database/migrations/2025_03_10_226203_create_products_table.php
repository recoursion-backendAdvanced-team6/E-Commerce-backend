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
        Schema::create('products', function (Blueprint $table) {
            // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->id();

            // stripe_product_id VARCHAR(255) NOT NULL
            $table->string('stripe_product_id', 255);

            // image_url VARCHAR(1024) NULL
            $table->string('image_url', 1024)->nullable();

            // title VARCHAR(255) NOT NULL
            $table->string('title', 255);

            // description TEXT
            $table->text('description')->nullable();

            // category_id BIGINT UNSIGNED, 外部キーは後で定義
            $table->unsignedBigInteger('category_id')->nullable();

            // published_date DATETIME NOT NULL
            $table->dateTime('published_date');

            // status ENUM('draft', 'published') DEFAULT 'draft'
            $table->enum('status', ['draft', 'published'])->default('draft');

            // price DECIMAL(10,0) NOT NULL
            $table->decimal('price', 10, 0);

            // inventory INT UNSIGNED NOT NULL DEFAULT 0
            $table->unsignedInteger('inventory')->default(0);

            // is_digital BOOLEAN DEFAULT TRUE
            $table->boolean('is_digital')->default(true);

            // created_at, updated_at は $table->timestamps() で自動生成
            $table->timestamps();

            // deleted_at DATETIME NULL を使用する場合、ソフトデリートを利用
            $table->softDeletes();

            // 外部キー制約: category_id REFERENCES categories(id) ON DELETE SET NULL
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
