<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // config/models/seeding/product.php の設定を取得
        $seedConfig = config('models.seeding.product');
        
        // デフォルトの製品項目リストを取得
        $defaultProducts = $seedConfig['default_list'];

        // 各デフォルト製品について、同じ title をキーに updateOrCreate() を実行
        foreach ($defaultProducts as $productData) {
            Product::updateOrCreate(
                ['title' => $productData['title']],  // 検索条件（ここでは title をキーとしています）
                $productData                         // 更新または作成する値
            );
        }

        // ファクトリを利用する設定の場合は、指定件数のランダム製品データを作成
        if ($seedConfig['factory']) {
            Product::factory()->count($seedConfig['factory_count'])->create();
        }
    }
}
