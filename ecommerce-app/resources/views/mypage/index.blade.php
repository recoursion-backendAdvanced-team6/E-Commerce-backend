<x-front-layout>
    {{-- 
        スマホ・タブレット: 縦並び (flex-col)
        デスクトップ: 横並び (flex-row)
        画面いっぱいを使う (min-h-screen)
    --}}
    <div class="flex flex-col md:flex-row min-h-screen">
        {{-- サイドメニュー部分 --}}
        <x-mypage-sidebar />
        {{-- メインコンテンツ部分 --}}
        <main class="flex-1 p-4">
            <div class="max-w-5xl mx-auto">
                <h1 class="text-2xl font-bold mb-6">ようこそ、{{ Auth::user()->name }}さん</h1>

                <div class="bg-white shadow-sm rounded p-4 mb-6">
                    <p class="text-gray-600">
                        メールアドレス: {{ Auth::user()->email }}
                    </p>
                </div>

                {{-- 以下は例: お気に入り一覧や購入履歴を表示する場所 --}}
                <div class="bg-white shadow-sm rounded p-4 mb-6">
                    <h2 class="text-xl font-bold mb-4">お気に入り一覧</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        {{-- お気に入り商品のループ --}}
                        {{-- 例: @foreach($favorites as $product) --}}
                        <div class="border p-2 rounded">
                            <img src="/images/no-image.png" alt="サンプル商品" class="mb-2">
                            <h3 class="font-semibold">サンプル商品</h3>
                            <p class="text-sm text-gray-500">価格: 1,000円</p>
                        </div>
                        {{-- @endforeach --}}
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded p-4 mb-6">
                    <h2 class="text-xl font-bold mb-4">購入履歴</h2>
                    <ul class="space-y-2">
                        {{-- 購入履歴のループ --}}
                        {{-- 例: @foreach($orders as $order) --}}
                        <li class="border-b py-2">
                            <p>注文番号: 123</p>
                            <p>注文日: 2025-03-24</p>
                            <p>合計金額: 3,500円</p>
                            <a href="#" class="text-blue-500 underline">詳細を見る</a>
                        </li>
                        {{-- @endforeach --}}
                    </ul>
                </div>
            </div>
        </main>
    </div>
</x-front-layout>
