<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorsTableSeeder extends Seeder
{
    public function run()
    {
        // 5人分のデータを挿入
        DB::table('authors')->insert([
            [
                'name' => '佐藤 太郎', 
                'bio' => 'Web 開発のエキスパートで、数多くの大規模システム開発に携わった経験を持つ。著書には『モダンWebアプリケーション開発』などがあり、JavaScriptやReactを中心としたフレームワークに関する深い知識を持つ。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '田中 花子', 
                'bio' => 'モバイルアプリ開発における専門家。特にiOSとAndroidアプリの開発に関する実績が豊富で、著書『実践モバイルアプリ開発』では最新の開発技術やベストプラクティスを解説している。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '鈴木 一郎', 
                'bio' => 'データサイエンス分野の研究者で、統計解析やPythonによるデータ解析手法に精通。著書『Pythonで学ぶデータサイエンス』では、実データを使った解析手法を紹介している。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '高橋 美咲', 
                'bio' => '機械学習とAIの分野で活躍するエンジニア。著書『AIアルゴリズムの実装』では、機械学習の基本から実践的なアルゴリズムの解説まで、実務で役立つ技術を網羅している。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '山田 健一', 
                'bio' => 'プログラミング言語の専門家で、特にC++とRustに関する知識が豊富。著書『C++によるシステムプログラミング』や『Rustで学ぶ高パフォーマンスプログラミング』がある。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '井上 修司', 
                'bio' => 'ソフトウェア工学の専門家。設計パターンやテスト駆動開発（TDD）の実践者。著書『ソフトウェア設計パターン』では、複雑なシステムの設計に役立つパターンを解説。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '松本 一郎', 
                'bio' => 'DevOpsとインフラに関する知識を持つエンジニア。著書『DevOps実践ガイド』では、CI/CDの自動化やインフラのコード化（IaC）について解説している。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '渡辺 美佳', 
                'bio' => 'セキュリティエンジニアで、特にネットワークセキュリティやペネトレーションテストに精通。著書『実践的ネットワークセキュリティ』では、実際の攻撃手法とその防御策を詳細に解説している。',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
