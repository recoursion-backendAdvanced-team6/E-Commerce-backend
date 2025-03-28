<x-front-layout>
    <x-slot:title>配送先情報入力</x-slot:title>

    <!-- パンくずリスト -->
    <nav class="text-sm text-gray-500 mb-4">
        <a href="{{ route('cart.index')}}">カート</a> > 配送先情報
    </nav>

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
                        <th class="text-right p-2">価格(税込)</th>
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
                    <p><span class="font-semibold">お名前:</span> {{ $user->name }}</p>
                    <p><span class="font-semibold">メール:</span> {{  $user->email }}</p>
                    <p><span class="font-semibold">国:</span> {{ $user->country }}</p>
                    <p><span class="font-semibold">郵便番号:</span> {{ $user->zipcode }}</p>
                    <p><span class="font-semibold">住所:</span> {{ $user->street_address }}</p>
                    <p><span class="font-semibold">市区町村:</span> {{ $user->city }}</p>
                    <p><span class="font-semibold">電話番号:</span> {{ $user->phone }}</p>
                </div>

                <label class="flex items-center mb-2">
                    <input type="radio" name="address_type" value="new" class="mr-2">
                    新しい住所を入力する
                </label>
            </div>
        @endif

        <!-- 新しい住所入力フォーム（ゲストまたは会員が新規入力を選択した場合） -->
        <!-- 新しい住所の入力フォーム（ゲスト or 新規入力を選択した場合） -->
        <div 
                id="new-address-section" 
                class="bg-[#f9f9f7] p-4 rounded-xl border border-gray-200 shadow-sm"
                style="@if($user) display: none; @endif"
            >
            @if(!$user)
                <div class="mb-6 bg-gradient-to-r from-pink-100 to-purple-100 p-6 rounded-xl border border-pink-200 shadow-lg text-center">
                    <h2 class="text-lg font-bold mb-2 text-purple-700">会員登録しませんか？</h2>
                    <p class="text-purple-600 mb-4">
                        会員登録すると、購入履歴の確認やお得な情報が届く便利な機能が使えます！
                    </p>
                    <a href="{{ route('register') }}" class="inline-block bg-purple-500 text-white px-5 py-2 rounded-full hover:bg-purple-600 transition">
                        📚 会員登録する
                    </a>
                </div>
            @endif

            @if(!Auth::check())
                <div class="flex gap-4 mt-4 justify-center flex-wrap">
                    <a href="{{ route('login') }}" class="inline-block bg-purple-400 text-white px-5 py-2 rounded-full hover:bg-purple-600 transition">
                        😊 会員登録済みの方はこちらからログイン 🔑
                    </a>
                </div>
            @endif

            <h2 class="text-lg font-semibold mb-2 text-gray-700">配送先の住所を入力</h2>

            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">お名前</label>
                <input 
                    type="text" 
                    name="name"
                    value="{{ old('name', session('shipping.name')) }}" 
                    class="border border-gray-300 p-2 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-300"
                >
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ 'お名前は必須です' }}</p>
                @enderror
            </div>
            @if(!Auth::check())
                <div class="mb-4">
                    <label class="block mb-1 font-medium text-gray-700">メールアドレス</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email', session('shipping.email')) }}" 
                        class="border border-gray-300 p-2 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-300"
                    >
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ 'メールアドレスは必須です' }}</p>
                    @enderror
                </div>
            @endif
            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">国</label>
                <input 
                    type="text" 
                    name="country" 
                    value="{{ old('country', session('shipping.country')) }}" 
                    class="border border-gray-300 p-2 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-300"
                >
                @error('country')
                    <p class="text-red-600 text-sm mt-1">{{ '国の名前は必須です' }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">郵便番号</label>
                <input 
                    type="text" 
                    name="zipcode" 
                    value="{{ old('zipcode', session('shipping.zipcode')) }}" 
                    class="border border-gray-300 p-2 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-300"
                >
                @error('zipcode')
                    <p class="text-red-600 text-sm mt-1">{{ '郵便番号は必須です' }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">都道府県</label>
                <textarea 
                    name="street_address" 
                    class="border border-gray-300 p-2 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-300"
                >{{ old('street_address', session('shipping.street_address')) }}</textarea>
                @error('street_address')
                    <p class="text-red-600 text-sm mt-1">{{ '都道府県は必須です' }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">市区町村以降の住所</label>
                <input 
                    type="text" 
                    name="city" 
                    value="{{ old('city', session('shipping.city')) }}" 
                    class="border border-gray-300 p-2 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-300"
                >
                @error('city')
                    <p class="text-red-600 text-sm mt-1">{{ "市区町村以降の住所は必須です" }}</p>
                @enderror
            </div>
            @if(!Auth::check())
                <div class="mb-4">
                    <label class="block mb-1 font-medium text-gray-700">電話番号</label>
                    <input 
                        type="text" 
                        name="phone" 
                        value="{{ old('phone', session('shipping.phone')) }}" 
                        class="border border-gray-300 p-2 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-300"
                    >
                    @error('phone')
                        <p class="text-red-600 text-sm mt-1">{{ '電話番号は必須です' }}</p>
                    @enderror
                </div>
            @endif
        </div>

        <div class="flex justify-between items-center mt-6">
            <a 
                href="{{ route('cart.index') }}" 
                class="inline-block text-blue-600 hover:underline"
            >
                ← カートに戻る
            </a>
            <button 
                type="submit" 
                class="rounded-2xl bg-brand-100 text-brand-900 px-6 py-2 font-semibold border border-brand-200 hover:bg-brand-200 hover:text-white transition"
            >
                確認ページへ進む
            </button>
        </div>
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
