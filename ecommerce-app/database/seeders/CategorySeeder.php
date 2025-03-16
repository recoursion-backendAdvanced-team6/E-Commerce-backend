<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Web 開発',
            'モバイル開発',
            'データサイエンス',
            '機械学習 / AI',
            'プログラミング言語',
            'ソフトウェア工学',
            'DevOps & インフラ',
            'セキュリティ',
            'その他'
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['name' => $name],  // 検索条件
                ['name' => $name]   // 挿入または更新する値
            );
        }
    }
}
