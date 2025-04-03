<x-front-layout>
    <div class="flex flex-col md:flex-row min-h-screen">
        {{-- サイドメニュー --}}
        <x-mypage-sidebar />

        {{-- メインコンテンツ --}}
        <main class="flex-1 p-4">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-2xl font-bold mb-6">注文詳細 - 注文番号: {{ $order->id }}</h1>
                <p>注文日: {{ $order->created_at->format('Y/m/d') }}</p>
                <p>合計金額: {{ number_format($order->total_amount) }}円</p>

                {{-- ステータス表示 --}}
                <div class="flex items-center space-x-2 mt-2">
                    <p>ステータス</p>
                    @if($order->status === 'pending')
                        <span class="text-sm text-gray-600">保留中</span>
                    @elseif($order->status === 'processing')
                        <span class="text-sm text-gray-600">購入手続き中</span>
                    @elseif($order->status === 'completed')
                        <span class="text-sm text-gray-600">発送準備中</span>
                    @elseif($order->status === 'cancelled')
                        <span class="text-sm text-gray-600">キャンセル</span>
                    @endif
                </div>

                {{-- 請求書ダウンロード --}}
                @if($order->stripe_invoice_url)
                    <div class="mt-4">
                        <a href="{{ $order->stripe_invoice_url }}" target="_blank" class="text-blue-500 underline">
                            請求書（領収書）を確認・ダウンロード
                        </a>
                    </div>
                @endif

                {{-- 注文アイテム一覧 --}}
                <div class="mt-6">
                    <h2 class="text-xl font-bold mb-4">注文商品</h2>
                    <ul class="space-y-4">
                        @foreach ($order->orderItems as $item)
                            <li class="flex items-center space-x-4 border-b pb-2">
                                <img src="{{ $item->product->image_url ?? '/images/no-image.png' }}" alt="{{ $item->product->title }}" class="w-16 h-16 object-cover rounded">
                                <div>
                                    <p class="font-semibold">{{ $item->product->title }}</p>
                                    <p class="text-sm text-gray-500">{{ number_format($item->price) }}円 × {{ $item->quantity }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </main>
    </div>
</x-front-layout>
