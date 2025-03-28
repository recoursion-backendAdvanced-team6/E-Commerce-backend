<x-front-layout>
    <x-slot:title>新刊・話題書</x-slot:title>

    <!-- パンくずリスト -->
    <nav class="text-sm text-gray-500 mb-4">
        <a href="{{ route('front.home')}}">ホーム</a> > 商品一覧
    </nav>

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
                <a href="{{ route('front.product.show', $product->id) }}">
                    <img
                        src="{{ $product->image_url ?? '/images/no-image.png' }}"
                        alt="{{ $product->title }}"
                        class="w-full h-auto mb-3 rounded-2xl"
                    />
                </a>

                <!-- タイトル -->
                <a href="{{ route('front.product.show', $product->id) }}">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">
                        {{ $product->title }}
                    </h3>
                </a>

                <!-- 簡易説明 -->
                <p class="text-sm text-gray-600 mb-2 line-clamp-3">
                    {{ $product->description }}
                </p>

                <!-- カテゴリ & 価格 -->
                <div class="text-sm text-gray-600 mb-2">
                    {{ $product->category ? $product->category->name : '' }} {{ $product->price }}円
                </div>

                <!-- 発売日など -->
                <div class="text-xs text-gray-500 mb-3">
                    発売日: {{ $product->published_date }}
                </div>

                <!-- ボタン群 -->
                <div class="flex justify-between items-center mt-3">
                    <!-- カートに入れるボタン -->
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="add-to-cart-btn rounded-2xl bg-pink-300 text-white px-4 py-2 font-semibold hover:bg-pink-400 transition">
                            カートに入れる
                        </button>
                    </form>
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

    <x-slot:js>
        <script>
       document.addEventListener('DOMContentLoaded', () => {
            const forms = document.querySelectorAll('.cart-add-form');

            forms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();  // フォームのデフォルト送信をキャンセル

                    const url = this.action;
                    const formData = new FormData(this);

                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Success:', data);
                        alert('商品がカートに追加されました！');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('カートに追加できませんでした。');
                    });
                });
            });
        });
        </script>
    </x-slot:js>
</x-front-layout>
