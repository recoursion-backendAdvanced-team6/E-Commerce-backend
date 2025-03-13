<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 * 
 * Thinker で $rand = \App\Models\Product::factory()->count(10)->create(); するとランダムな商品10個作成されてDBに登録される。
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Stripe の商品ID（例: prod_ + ランダムな文字列）
            'stripe_product_id' => 'prod_' . Str::random(10),
            // 商品画像URL（faker の imageUrl を利用）
            'image_url' => $this->faker->imageUrl(640, 480, 'technics', true),
            // タイトル：文書の一部を生成
            'title' => $this->faker->sentence(6),
            // 商品説明
            'description' => $this->faker->paragraph,
            // 外部キー：Category は存在しない場合は、CategoryFactory で自動生成する
            'category_id' => Category::factory(),
            // 公開日付：過去1年以内の日時
            'published_date' => $this->faker->dateTimeBetween('-1 years', 'now'),
            // ステータス： 'draft' か 'published' をランダムに選択
            'status' => $this->faker->randomElement(['draft', 'published']),
            // 価格：例として 1000～10000 の整数
            'price' => $this->faker->numberBetween(1000, 10000),
            // 在庫数：例として 0～100
            'inventory' => $this->faker->numberBetween(0, 100),
            // デジタル商品の場合は true を多めに設定（80%の確率など）
            'is_digital' => $this->faker->boolean(80)
        ];
    }
}
