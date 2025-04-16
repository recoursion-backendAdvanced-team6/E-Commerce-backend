<x-front-layout>
    <x-slot:title>{{ $product->title }}</x-slot:title>

    <!-- パンくずリスト -->
    <nav class="text-sm text-gray-500 mb-4">
        <a href="{{ route('front.home') }}">ホーム</a>
        >
        <a href="{{ route('front.products') }}">商品一覧</a>
        @if ($product->category)
            >
            <a href="{{ route('front.product.category', ['category' => $product->category->id]) }}">
                {{ $product->category->name }}
            </a>
        @endif
        > {{ $product->title }}
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
            @if(Auth::check())
                @if(Auth::user()->favorites()->where('product_id', $product->id)->wherePivot('deleted_at', null)->exists())
                    <!-- 登録済みの場合：背景は白、文字はブランド色、ハートはブランド色 -->
                    <button id="fav-button-{{ $product->id }}"
                        class="rounded-2xl bg-white text-brand-900 px-6 py-2 font-semibold border border-purple-200 hover:bg-gray-50 transition inline-flex items-center"
                        onclick="toggleFavorite({{ $product->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            class="w-5 h-5 fill-brand-500 stroke-brand-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.435 4.353a5.847 5.847 0 00-8.29-.128l-.145.143-.145-.143a5.847 5.847 0 00-8.29.128 5.838 5.838 0 000 8.267l8.29 8.29 8.29-8.29a5.838 5.838 0 000-8.267v0z" />
                        </svg>
                        <span class="ml-2">お気に入り解除</span>
                    </button>
                @else
                    <!-- 未登録の場合：背景はブランド色（濃い）、文字とハートは白 -->
                    <button id="fav-button-{{ $product->id }}"
                        class="rounded-2xl bg-brand-900 text-white px-6 py-2 font-semibold border border-white hover:bg-brand-800 transition inline-flex items-center"
                        onclick="toggleFavorite({{ $product->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            class="w-5 h-5 fill-white stroke-purple-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.435 4.353a5.847 5.847 0 00-8.29-.128l-.145.143-.145-.143a5.847 5.847 0 00-8.29.128 5.838 5.838 0 000 8.267l8.29 8.29 8.29-8.29a5.838 5.838 0 000-8.267v0z" />
                        </svg>
                        <span class="ml-2">お気に入り追加</span>
                    </button>
                @endif
            @else
                <!-- ログインしていない場合は、ボタンをクリックするとログインページへ遷移 -->
                <a href="{{ route('login') }}"
                class="rounded-2xl bg-brand-900 text-white px-6 py-2 font-semibold border border-white hover:bg-brand-800 transition inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        class="w-5 h-5 fill-white stroke-purple-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.435 4.353a5.847 5.847 0 00-8.29-.128l-.145.143-.145-.143a5.847 5.847 0 00-8.29.128 5.838 5.838 0 000 8.267l8.29 8.29 8.29-8.29a5.838 5.838 0 000-8.267v0z" />
                    </svg>
                    <span class="ml-2">お気に入り追加</span>
                </a>
            @endif
        </div>
    </div>


    <x-slot:js>
    function toggleFavorite(productId) {
        var button = document.getElementById("fav-button-" + productId);
        var svg = button.querySelector("svg");
        var span = button.querySelector("span");
        console.log("Toggle favorite for product:", productId);

        // 登録済みの場合（ハートの色がブランド色なら）
        if (svg.classList.contains("fill-brand-500")) {
            // DELETE リクエストでお気に入り解除
            fetch('{{ url("favorites") }}/' + productId, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": '{{ csrf_token() }}'
                }
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(function(data) {
                // 未登録状態に切り替え：背景は bg-brand-900、文字とハートは白
                button.classList.remove("bg-white", "text-brand-900", "hover:bg-gray-50", "border-purple-200");
                button.classList.add("bg-brand-900", "text-white", "hover:bg-brand-800", "border-white");
                svg.classList.remove("fill-brand-500", "stroke-brand-500");
                svg.classList.add("fill-white", "stroke-white");
                span.textContent = "お気に入り追加";
            })
            .catch(function(error) {
                alert("お気に入り解除に失敗しました。");
                console.error("Error:", error);
            });
        } else {
            // 未登録の場合は POST リクエストでお気に入り追加
            fetch('{{ route("favorites.store") }}', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(function(data) {
                // 登録済み状態に切り替え：背景は白、文字はブランド色、ハートはブランド色
                button.classList.remove("bg-brand-900", "text-white", "hover:bg-brand-800", "border-white");
                button.classList.add("bg-white", "text-brand-900", "hover:bg-gray-50", "border-purple-200");
                svg.classList.remove("fill-white", "stroke-white");
                svg.classList.add("fill-brand-500", "stroke-brand-500");
                span.textContent = "お気に入り解除";
            })
            .catch(function(error) {
                alert("お気に入り追加に失敗しました。");
                console.error("Error:", error);
            });
        }
    }


    </x-slot:js>
</x-front-layout>
