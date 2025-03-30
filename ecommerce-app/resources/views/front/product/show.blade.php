<x-front-layout>
    <x-slot:title>{{ $product->title }}</x-slot:title>

    <!-- パンくずリスト -->
    <nav class="text-sm text-gray-500 mb-4">
        <a href="{{ route('front.home')}}">ホーム</a> > <a href="{{ route('front.products')}}">商品一覧</a> > {{ $product->title }}
    </nav>

    <div class="md:flex md:space-x-8">
        <!-- 左側：商品画像 -->
        <div class="md:w-1/2 mb-4 md:mb-0 flex justify-center">
            <img
                src="{{ $product->image_url ?? '/images/no-image.png' }}"
                alt="{{ $product->title }}"
                class="w-full h-auto max-w-sm rounded shadow"
            />
        </div>

        <!-- 右側：商品情報 -->
        <div class="md:w-1/2 space-y-4">
            <!-- カテゴリ・タイトル -->
            <div>
                <span class="inline-block bg-brand-50 text-brand-900 px-2 py-1 rounded-full text-xs font-semibold">
                    {{ $product->category->name ?? '未分類' }}
                </span>
                <h1 class="text-2xl font-bold mt-2">{{ $product->title }}</h1>
            </div>

            <!-- 著者名 -->
            <ul class="text-sm text-gray-600">
                <li> {{ $product->author?->name ?? '' }}
                </li>
            </ul>

            <!-- 価格 -->
            <div class="text-xl text-brand-900">
                {{ number_format($product->taxed_price) }}円 <span class="text-sm text-gray-500">(税込)</span>
            </div>

            <!-- 商品情報（発売日・ISBNなど） -->
            <ul class="text-sm text-gray-600 space-y-1">
                <li>発売日：{{ \Carbon\Carbon::parse($product->published_date)->format('Y-m-d') }}
                </li>
                <!-- 必要に応じて追加 -->
            </ul>

            <!-- カートに入れるボタン -->
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-4">
                @csrf
                <button
                    type="submit"
                    class="rounded-2xl bg-brand-100 text-brand-900 px-6 py-2 font-semibold border border-brand-200 hover:bg-brand-200 hover:text-white transition flex items-center space-x-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor"
                         class="w-5 h-5">
                      <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 2.25h1.635c.267 0 .51.173.59.432l3.713 11.72a.75.75 0 00.59.432h8.704a.75.75 0 00.74-.59l1.538-7.69a.75.75 0 00-.74-.91H6.295" />
                      <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 19.5a1.5 1.5 0 11-3.001.001A1.5 1.5 0 019 19.5zM19.5 19.5a1.5 1.5 0 11-3.001.001A1.5 1.5 0 0119.5 19.5z" />
                    </svg>
                    <span>カートに入れる</span>
                </button>
            </form>

            <!--  お気に入りボタン -->
            <button
                class="rounded-2xl bg-white text-brand-900 px-6 py-2 font-semibold border border-brand-200 hover:bg-brand-50 transition flex items-center space-x-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor"
                     class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21.435 4.353a5.847 5.847 0 00-8.29-.128l-.145.143-.145-.143a5.847 5.847 0 00-8.29.128 5.838 5.838 0 000 8.267l8.29 8.29 8.29-8.29a5.838 5.838 0 000-8.267v0z" />
                </svg>
                <span>お気に入りに追加</span>
            </button>
        </div>
    </div>
</x-front-layout>
