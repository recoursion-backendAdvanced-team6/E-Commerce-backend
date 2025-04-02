{{-- resources/views/favorites/index.blade.php --}}
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
                        <div class="border rounded-lg p-4 bg-white shadow">
                            <img 
                                src="{{ $product->image_url ?? '/images/no-image.png' }}" 
                                alt="{{ $product->title }}" 
                                class="w-full h-48 object-cover mb-3"
                            />
                            <h2 class="text-lg font-semibold mb-2">{{ $product->title }}</h2>
                            <p class="text-gray-700 mb-2">価格: {{ number_format($product->price) }}円</p>
                            <div class="flex space-x-2">
                                <form action="{{ route('favorites.remove', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                        削除
                                    </button>
                                </form>
                                <a href="{{ route('front.product.show', $product->id) }}"
                                   class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                    詳細を見る
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
</x-front-layout>
