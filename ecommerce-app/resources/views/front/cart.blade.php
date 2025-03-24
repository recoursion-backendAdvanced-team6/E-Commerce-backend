<x-front-layout>
    <x-slot:title>カート</x-slot:title>

    <h1 class="text-3xl font-bold mb-6">カート</h1>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (count($products) > 0)
        <div class="overflow-x-auto">
            <table class="w-full mb-6 border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-3">商品名</th>
                        <th class="text-left p-3">価格</th>
                        <th class="text-center p-3">数量</th>
                        <th class="text-right p-3">小計</th>
                        <th class="p-3">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr class="border-b">    
                            <td class="p-3">
                                <a href="{{ route('front.product.show', $product->id) }}" class="flex items-center space-x-2">
                                    <img src="{{ $product->image_url ?? '/images/no-image.png' }}"
                                        alt="{{ $product->title }}"
                                        class="w-12 h-auto rounded">
                                    <div>
                                        <div class="font-semibold">{{ $product->title }}</div>
                                        <div class="text-xs text-gray-500">カテゴリ: {{ $product->category->name ?? '未分類' }}</div>
                                    </div>
                                </a>
                            </td>
                            <td class="p-3">¥{{ number_format($product->taxed_price) }}</td>
                            <td class="p-3 text-center">
                                <form action="{{ route('cart.update', $product->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <input type="number" name="quantity"
                                           value="{{ $product->quantity }}"
                                           min="1"
                                           class="w-16 p-1 border rounded text-center" />
                                    <button type="submit" class="text-blue-600 hover:underline ml-2">
                                        更新
                                    </button>
                                </form>
                            </td>
                            <td class="p-3 text-right">¥{{ number_format($product->subtotal) }}</td>
                            <td class="p-3 text-center">
                                <form action="{{ route('cart.remove', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:underline">削除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-right font-bold text-xl mb-6">
            商品合計: ¥{{ number_format($total) }}
        </div>

        <!-- 購入ボタン -->
        <div class="text-right">
            <a href="{{ route('checkout.shipping') }}"
            class="rounded-2xl bg-brand-100 text-brand-900 px-6 py-2 font-semibold border border-brand-200 hover:bg-brand-200 hover:text-white transition">
                購入する
            </a>
        </div>
    @else
        <p>カートは空です。</p>
    @endif

</x-front-layout>
