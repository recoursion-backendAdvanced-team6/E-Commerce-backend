<x-front-layout>
    <x-slot:title>注文内容の確認</x-slot:title>

    <!-- パンくずリスト -->
    <nav class="text-sm text-gray-500 mb-4">
        <a href="{{ route('checkout.shipping')}}">配送先情報</a> > 注文内容の確認
    </nav>

    <h1 class="text-2xl font-bold mb-4">注文内容の確認</h1>

    <!-- 配送先情報の確認 -->
    <div class="mb-4 p-4 bg-gray-100 rounded border border-gray-200">
        <h2 class="font-bold mb-4 border-b pb-2 text-gray-800">配送先情報</h2>
        <div class="space-y-1 text-gray-700">
            <p><span class="font-semibold">お名前:</span> {{ $shipping['name'] }}</p>
            <p><span class="font-semibold">メール:</span> {{ $shipping['email'] }}</p>
            <p><span class="font-semibold">国:</span> {{ $shipping['country'] }}</p>
            <p><span class="font-semibold">郵便番号:</span> {{ $shipping['zipcode'] }}</p>
            <p><span class="font-semibold">住所:</span> {{ $shipping['street_address'] }}</p>
            <p><span class="font-semibold">市区町村:</span> {{ $shipping['city'] }}</p>
            <p><span class="font-semibold">電話番号:</span> {{ $shipping['phone'] }}</p>
        </div>
    </div>

    <!-- 注文内容の確認 -->
    @if (!empty($cart) && count($cart) > 0)
        <div class="mb-4 p-4 bg-gray-100 rounded">
            <h2 class="font-bold mb-2">注文内容</h2>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-2">商品</th>
                        <th class="text-left p-2"></th>
                        <th class="text-right p-2">数量</th>
                        <th class="text-right p-2">価格(税込)</th>
                        <th class="text-right p-2">小計</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart as $product)
                        @php
                            $subtotal = $product->quantity * $product->taxed_price;
                        @endphp
                        <tr class="border-b">
                            <td class="p-2">
                                <img src="{{ $product->image_url ?? '/images/no-image.png' }}"
                                     alt="{{ $product->title }}"
                                     class="w-12 h-auto rounded">
                            </td>
                            <td class="p-2">{{ $product->title }}
                                <p class="text-sm">{{ $product->author?->name ?? '' }}</p>
                            </td>
                            <td class="p-2 text-right">{{ $product->quantity }}</td>
                            <td class="p-2 text-right">¥{{ number_format($product->taxed_price) }}</td>
                            <td class="p-2 text-right">¥{{ number_format($subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-right font-bold mt-2">
                合計: ¥{{ number_format($total) }}
            </div>
        </div>
    @endif

    <!-- 支払い処理ボタン -->
    <div class="flex justify-between items-center mt-6">
        <a 
            href="{{ route('checkout.shipping') }}" 
            class="inline-block text-blue-600 hover:underline"
        >
            ← 配送先設定に戻る
        </a>
        <form action="{{ route('checkout.payment.process') }}" method="POST">
        @csrf
        <!-- Stripe Elements などの支払いフォームのコードをここに組み込みます -->
            <button type="submit" class="rounded-2xl bg-brand-100 text-brand-900 px-6 py-2 font-semibold border border-brand-200 hover:bg-brand-200 hover:text-white transition">
               📚 購入する 📕
            </button>
        </form>
    </div>
</x-front-layout>
