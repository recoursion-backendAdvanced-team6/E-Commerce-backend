<x-front-layout>
    <div class="flex flex-col md:flex-row min-h-screen">
        {{-- サイドメニュー --}}
        <x-mypage-sidebar />

        {{-- メインコンテンツ --}}
        <main class="flex-1 p-4">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-2xl font-bold mb-6">お気に入り一覧</h1>
                {{-- お気に入り商品の表示 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($favorites as $product)
                        <div id="favorite-item-{{ $product->id }}" class="favorite-item border rounded-lg p-4 bg-white shadow">
                            <img 
                                src="{{ $product->image_url ?? '/images/no-image.png' }}" 
                                alt="{{ $product->title }}" 
                                class="w-full h-48 object-cover mb-3 rounded-lg"
                            />
                            <h2 class="text-lg font-semibold mb-2">{{ $product->title }}</h2>
                            <p class="text-gray-700 mb-2">価格: {{ number_format($product->price) }}円</p>
                            <div class="flex space-x-2">
                                {{-- お気に入り解除ボタン（Ajaxで削除してリロード） --}}
                                <button id="fav-button-{{ $product->id }}"
                                    class="rounded-2xl bg-white text-brand-900 px-6 py-2 font-semibold border border-purple-200 hover:bg-gray-50 transition inline-flex items-center"
                                    onclick="removeFavorite({{ $product->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        class="w-5 h-5 fill-brand-500 stroke-brand-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21.435 4.353a5.847 5.847 0 00-8.29-.128l-.145.143-.145-.143a5.847 5.847 0 00-8.29.128 5.838 5.838 0 000 8.267l8.29 8.29 8.29-8.29a5.838 5.838 0 000-8.267v0z" />
                                    </svg>
                                    <span class="ml-2">お気に入り解除</span>
                                </button>
                                {{-- 詳細を見るボタン --}}
                                <a href="{{ route('front.product.show', $product->id) }}"
                                   class="rounded-2xl bg-brand-100 text-brand-900 px-6 py-2 font-semibold border border-brand-200 hover:bg-brand-200 hover:text-white transition flex items-center space-x-2">
                                    <span>詳細を見る</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full text-gray-500">お気に入りはありません。</p>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <!-- Ajax 用 JavaScript -->
    <x-slot:js>
        // お気に入り解除用の関数（Ajaxで削除してページをリロード）
        function removeFavorite(productId) {
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
                // 成功したらページリロード
                console.log(data);
                location.reload();
            })
            .catch(function(error) {
                alert("お気に入り解除に失敗しました。");
                console.error("Error:", error);
            });
        }
    </x-slot:js>
</x-front-layout>
