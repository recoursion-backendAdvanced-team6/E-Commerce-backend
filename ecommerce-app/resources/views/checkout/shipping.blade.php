<x-front-layout>
    <x-slot:title>配送先情報入力</x-slot:title>

    <h1 class="text-2xl font-bold mb-4">配送先情報</h1>

    <!-- カート情報の表示 -->
    @if (!empty($cart) && count($cart) > 0)
        <div class="mb-6 bg-gray-100 p-4 rounded">
            <h2 class="text-xl font-semibold mb-2">カートの内容</h2>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-2">商品画像</th>
                        <th class="text-left p-2">商品名</th>
                        <th class="text-right p-2">数量</th>
                        <th class="text-right p-2">価格</th>
                        <th class="text-right p-2">小計</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($cart as $product)
                        @php
                            $subtotal = $product->quantity * $product->taxed_price;
                            $total += $subtotal;
                        @endphp
                        <tr class="border-b">
                            <td class="p-2">
                                <img src="{{ $product->image_url ?? '/images/no-image.png' }}"
                                     alt="{{ $product->title }}"
                                     class="w-12 h-auto rounded">
                            </td>
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
    @else
        <p class="mb-6">カートは空です。</p>
    @endif

    <!-- 配送先選択フォーム -->
    <form action="{{ route('checkout.shipping.store') }}" method="POST">
        @csrf

        @if($user)
            <!-- ログインユーザー向け：既存住所 or 新しい住所入力の選択 -->
            <div class="mb-6 bg-gray-50 p-4 rounded">
                <h2 class="text-lg font-semibold mb-2">配送先の選択</h2>
                <label class="flex items-center mb-2">
                    <input type="radio" name="address_type" value="existing" checked class="mr-2">
                    登録済みの住所を使う
                </label>
                <div id="existing-address-section" class="ml-6 mb-4 bg-white p-3 border rounded">
                    <p>お名前: {{ $user->name }}</p>
                    <p>メール: {{ $user->email }}</p>
                    <p>国: {{ $user->country }}</p>
                    <p>郵便番号: {{ $user->zipcode }}</p>
                    <p>住所: {{ $user->street_address }}</p>
                    <p>市区町村: {{ $user->city }}</p>
                    <p>電話番号: {{ $user->phone }}</p>
                </div>

                <label class="flex items-center mb-2">
                    <input type="radio" name="address_type" value="new" class="mr-2">
                    新しい住所を入力する
                </label>
            </div>
        @endif

        <!-- 新しい住所入力フォーム（ゲストまたは会員が新規入力を選択した場合） -->
        <div id="new-address-section" class="mb-4 bg-gray-50 p-4 rounded"
             style="@if($user) display: none; @endif">
            <h2 class="text-lg font-semibold mb-2">新しい住所を入力</h2>
            <div class="mb-4">
                <label class="block mb-1">お名前</label>
                <input type="text" name="name" value="{{ old('name') }}" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block mb-1">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block mb-1">国</label>
                <input type="text" name="country" value="{{ old('country') }}" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block mb-1">郵便番号</label>
                <input type="text" name="zipcode" value="{{ old('zipcode') }}" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block mb-1">住所</label>
                <textarea name="street_address" class="border p-2 w-full">{{ old('street_address') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block mb-1">市区町村</label>
                <input type="text" name="city" value="{{ old('city') }}" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block mb-1">電話番号</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="border p-2 w-full">
            </div>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            次へ進む
        </button>
    </form>

    <x-slot:js>
        document.addEventListener('DOMContentLoaded', () => {
            const addressTypeRadios = document.querySelectorAll('input[name="address_type"]');
            const newAddressSection = document.getElementById('new-address-section');
            const existingAddressSection = document.getElementById('existing-address-section');

            if (addressTypeRadios) {
                addressTypeRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.value === 'existing') {
                            newAddressSection.style.display = 'none';
                            existingAddressSection.style.display = 'block';
                        } else {
                            newAddressSection.style.display = 'block';
                            existingAddressSection.style.display = 'none';
                        }
                    });
                });
            }
        });
    </x-slot:js>
</x-front-layout>
