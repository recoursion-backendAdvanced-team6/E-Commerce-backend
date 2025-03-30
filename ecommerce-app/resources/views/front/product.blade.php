<x-front-layout>
    <x-slot:title>
        @isset($category)
            {{ $category->name }}
        @else
            新刊・話題書
        @endisset
    </x-slot:title>

    <!-- パンくずリスト -->
    <nav class="text-sm text-gray-500 mb-4">
        <a href="{{ route('front.home') }}">ホーム</a>
        @isset($category)
            > <a href="{{ route('front.products') }}">商品一覧</a> > {{ $category->name }}
        @else
            > 商品一覧
        @endisset
    </nav>

    <h1 class="text-4xl font-bold mb-4 brand-999">
        @isset($category)
            {{ $category->name }}
        @else
            新刊・話題書
        @endisset
    </h1>
    <p class="text-lg mb-6 text-gray-700">
        最新のプログラミング書籍から、定評ある実践ガイド、初心者向けの入門書まで、幅広いラインナップをご用意しております。各書籍は、業界の動向を踏まえた内容や実用的な知識を網羅しており、読者の多様なニーズにお応えします。ぜひ、お気に入りの一冊を見つけてください。
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

                <!-- 作者 -->
                <div class="text-sm text-gray-600 mb-2">
                    {{ $product->author?->name ?? '' }}
                </div>

                <!-- カテゴリ & 価格 -->
                <div class="text-sm text-gray-600 mb-2">
                    {{ $product->category ? $product->category->name : '' }} {{ number_format($product->taxed_price) }}円（税込）
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
                    <!-- お気に入りボタン -->
                    <button
                        class="rounded-2xl bg-blue-300 text-white px-4 py-2 font-semibold hover:bg-blue-400 transition">
                        <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor"
                     class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.435 4.353a5.847 5.847 0 00-8.29-.128l-.145.143-.145-.143a5.847 5.847 0 00-8.29.128 5.838 5.838 0 000 8.267l8.29 8.29 8.29-8.29a5.838 5.838 0 000-8.267v0z" />
                        </svg>
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
