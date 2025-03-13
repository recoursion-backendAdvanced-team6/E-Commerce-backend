<?php

$defaultList = [];
for ($i = 1; $i <= 20; $i++) {
    $defaultList[] = [
        'stripe_product_id' => "prod_prog_$i",
        'image_url'         => "https://picsum.photos/seed/' . $i . '/480/640",
        'title'             => "Programming Book $i",
        'description'       => "This is a sample description for Programming Book $i. It covers various programming topics, frameworks, and best practices.",
        'category_id'       => rand(1, 9), // 1〜9のランダムな数字を設定
        'published_date'    => '2025-01-01 00:00:00',
        'status'            => ($i % 2 === 0) ? 'published' : 'draft',
        'price'             => 1000 + ($i * 100), // 例: 1100, 1200, 1300, ...
        'inventory'         => 10 + $i,
        'is_digital'        => ($i % 2 === 0), // 偶数はデジタル版、奇数は紙媒体など
    ];
}

return [
    // ファクトリを使用して初期データを生成するかどうか
    'factory' => false,
    
    // ファクトリを使用して生成するデータの件数
    'factory_count' => 50,
    
    // ファクトリを使用しない場合にデータベースに挿入するデフォルトの製品項目リスト
    'default_list' => $defaultList,
];
