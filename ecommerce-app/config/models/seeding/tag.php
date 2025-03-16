<?php

return [
    // シーディングにファクトリを使用するかどうか
    'factory' => true,

    // ファクトリを使用して生成するタグの件数
    'factory_count' => 20,

    // シーディング時にデータベースに挿入するデフォルトのタグ項目のリスト
    'default_list' => [
        [
            'name' => 'New',
        ],
        [
            'name' => 'Sale',
        ],
        [
            'name' => 'Popular',
        ],
    ],
];
