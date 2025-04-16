<?php

return [
    // ファクトリを使用して初期データを生成するかどうか
    'factory' => true,
    
    // ファクトリを使用して生成するデータの件数
    'factory_count' => 10,
    
    // ファクトリを使用しない場合に挿入するデフォルトのカテゴリ項目リスト
    'default_list' => [
        [
            'name' => 'JavaScript',
        ],
        [
            'name' => 'PHP',
        ],
        [
            'name' => 'Python',
        ],
        [
            'name' => 'JAVA',
        ],
        // 必要に応じて追加
    ],
];
