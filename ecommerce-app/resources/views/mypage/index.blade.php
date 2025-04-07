<x-front-layout>
    <div class="flex flex-col md:flex-row min-h-screen">
        {{-- サイドメニュー --}}
        <x-mypage-sidebar />

        {{-- メインコンテンツ --}}
        <main class="flex-1 p-4">
            <div class="max-w-5xl mx-auto">
                <h1 class="text-2xl font-bold mb-6">ようこそ、{{ $user->name }}さん</h1>

                <div class="bg-white shadow-sm rounded p-4 mb-6">
                    <p class="text-gray-600">メールアドレス: {{ $user->email }}</p>
                </div>

                {{-- 最新のお気に入りエリア --}}
                <div class="bg-white shadow-sm rounded p-4 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">最新のお気に入り</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @forelse($favorites->take(3) as $product)
                            <a href="{{ route('front.product.show', $product->id) }}">
                                <div class="border p-2 rounded">
                                    <img src="{{ $product->image_url ?? '/images/no-image.png' }}" alt="{{ $product->title }}" class="mb-2 w-full h-auto object-cover rounded-md">
                                    <h3 class="font-semibold">{{ $product->title }}</h3>
                                    <p class="text-sm text-gray-500">価格: {{ number_format($product->price) }}円</p>
                                </div>
                            </a>
                        @empty
                            <p class="col-span-full text-gray-500">お気に入りはありません。</p>
                        @endforelse
                    </div>
                </div>

                {{-- 最新の購入履歴エリア --}}
                <div class="bg-white shadow-sm rounded p-4 mb-6">
                    <h2 class="text-xl font-bold mb-4">最新の購入履歴</h2>
                    @if($orders->isNotEmpty())
                        @php
                            $latestOrder = $orders->first();
                        @endphp
                        <div class="border rounded p-4 bg-white shadow mb-6">
                            <div class="flex flex-col md:flex-row">
                                {{-- 左側：大きめの画像（ここでは最新の注文アイテムの最初の画像を表示） --}}
                                <div class="w-full md:w-1/3 flex justify-center items-center mb-4 md:mb-0">
                                    @php
                                        $firstItem = $latestOrder->items->first();
                                    @endphp
                                    @if($firstItem)
                                        <img src="{{ $firstItem->product->image_url ?? '/images/no-image.png' }}" alt="{{ $firstItem->product->title }}" class="w-full max-w-[200px] object-cover rounded-md">
                                    @else
                                        <img src="/images/no-image.png" alt="No image" class="w-full max-w-[200px] object-cover rounded-md">
                                    @endif
                                </div>
                                {{-- 右側：注文情報 --}}
                                <div class="w-full md:w-2/3 md:pl-6">
                                    <p class="font-semibold text-lg">注文番号: {{ $latestOrder->id }}</p>
                                    <p class="text-gray-600">注文日: {{ $latestOrder->created_at->format('Y/m/d') }}</p>
                                    <p class="text-gray-700 mt-2">合計金額: {{ number_format($latestOrder->total_amount) }}円</p>

                                    {{-- ステータス表示 --}}
                                    <div class="mb-4">
                                        <div class="flex items-center space-x-2 mt-2">
                                            @if($latestOrder->status === 'pending')
                                                <img src="{{ asset('images/status-pending.svg') }}" alt="保留中" class="max-w-[95%] min-w-[350px] mx-auto max-[1025px]:hidden">
                                                <span class="text-sm text-gray-600">保留中</span>
                                            @elseif($latestOrder->status === 'processing')
                                                <img src="{{ asset('images/status-purchase.svg') }}" alt="購入手続き中" class="max-w-[95%] min-w-[350px] mx-auto max-[1025px]:hidden">
                                                <span class="text-sm text-gray-600">購入手続き中</span>
                                            @elseif($latestOrder->status === 'completed')
                                                <img src="{{ asset('images/status-shipping.svg') }}" alt="発送準備中" class="max-w-[95%] min-w-[350px] mx-auto max-[1025px]:hidden">
                                                <span class="text-sm text-gray-600">発送準備中</span>
                                            @elseif($latestOrder->status === 'cancelled')
                                                <img src="{{ asset('images/status-cancel.svg') }}" alt="キャンセル" class="max-w-[95%] min-w-[350px] mx-auto max-[1025px]:hidden">
                                                <span class="text-sm text-gray-600">キャンセル</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <a href="{{ route('orders.show', $latestOrder->id) }}" class="text-blue-500 underline">
                                            詳細を見る
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500">購入履歴はありません。</p>
                    @endif


                </div>
            </div>
        </main>
    </div>
</x-front-layout>
