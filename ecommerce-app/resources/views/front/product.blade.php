<x-front-layout>
    <x-slot:title>新刊・話題書</x-slot:title>

    <h1 class="text-4xl font-bold mb-4 brand-999">新刊・話題書</h1>
    <p class="text-lg mb-6 text-gray-700">
        いま話題の新刊プログラミング書をチェック！<br>
        最新の言語仕様やフレームワークの実践ガイド、独学者向けの入門書まで、注目度の高い書籍をピックアップ。<br>
        トレンドを追いかけながら、スキルを身につけていきましょう。
    </p>

    <!-- 商品グリッド -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach ($products as $i => $product)
            <div class="bg-white rounded-2xl shadow hover:shadow-md transition p-4">
                <!-- 画像 -->
                <img
                    src="{{ $product->image_url ?? '/images/no-image.png' }}"
                    alt="{{ $product->title }}"
                    class="w-full h-auto mb-3 rounded-2xl"
                />

                <!-- タイトル -->
                <h3 class="text-lg font-semibold text-gray-800 mb-1">
                    {{ $product->title }}
                </h3>

                <!-- 簡易説明 -->
                <p class="text-sm text-gray-600 mb-2 line-clamp-3">
                    {{ $product->description }}
                </p>

                <!-- カテゴリ & 価格 -->
                <div class="text-sm text-gray-600 mb-2">
                    カテゴリ: {{ $product->category_id }} / {{ $product->price }}円
                </div>

                <!-- 発売日など -->
                <div class="text-xs text-gray-500 mb-3">
                    発売日: {{ $product->published_date }}
                </div>

                <!-- ボタン群 -->
                <div class="flex justify-between items-center mt-3">
                    <!-- カートに入れるボタン -->
                    <button
                        class="rounded-2xl bg-pink-300 text-white px-4 py-2 font-semibold hover:bg-pink-400 transition">
                        カートに入れる
                    </button>

                    <!-- ほしいものボタン -->
                    <button
                        class="rounded-2xl bg-blue-300 text-white px-4 py-2 font-semibold hover:bg-blue-400 transition">
                        ほしいもの
                    </button>
                </div>
              </div>
        @endforeach
    </div>

    <!-- ページネーション -->
    <div class="mt-6">
        {{ $products->links() }}
    </div>
</x-front-layout>
