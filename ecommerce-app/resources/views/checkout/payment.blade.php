<x-front-layout>
    <x-slot:title>支払い方法・最終確認</x-slot:title>

    <h1 class="text-2xl font-bold mb-4">支払い方法と注文内容の確認</h1>

    <!-- 配送先情報の確認 -->
    <div class="mb-4 p-4 bg-gray-100 rounded">
        <h2 class="font-bold mb-2">配送先情報</h2>
        <p>お名前: {{ $shipping['name'] }}</p>
        <p>メール: {{ $shipping['email'] }}</p>
        <p>国: {{ $shipping['country'] }}</p>
        <p>郵便番号: {{ $shipping['zipcode'] }}</p>
        <p>住所: {{ $shipping['street_address'] }}</p>
        <p>市区町村: {{ $shipping['city'] }}</p>
        <p>電話番号: {{ $shipping['phone'] }}</p>
    </div>

    <!-- 注文内容の確認 -->
    @if (!empty($cart) && count($cart) > 0)
        <div class="mb-4 p-4 bg-gray-100 rounded">
            <h2 class="font-bold mb-2">注文内容</h2>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-2">商品名</th>
                        <th class="text-right p-2">数量</th>
                        <th class="text-right p-2">価格</th>
                        <th class="text-right p-2">小計</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart as $product)
                        @php
                            $subtotal = $product->quantity * $product->taxed_price;
                        @endphp
                        <tr class="border-b">
                            <td class="p-2">{{ $product->title }}</td>
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
    <form action="{{ route('checkout.payment.process') }}" method="POST">
        @csrf
        <!-- Stripe Elements などの支払いフォームのコードをここに組み込みます -->
        <button type="submit" class="rounded-2xl bg-brand-100 text-brand-900 px-6 py-2 font-semibold border border-brand-200 hover:bg-brand-200 hover:text-white transition">
            購入する
        </button>
    </form>
</x-front-layout>
