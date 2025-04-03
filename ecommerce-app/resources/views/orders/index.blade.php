<x-front-layout>
    <div class="flex flex-col md:flex-row min-h-screen">
        {{-- サイドメニュー --}}
        <x-mypage-sidebar />

        {{-- メインコンテンツ --}}
        <main class="flex-1 p-4">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-2xl font-bold mb-6">購入履歴</h1>

                @forelse($orders as $order)
                    <div class="border rounded-lg p-4 bg-white shadow mb-6">
                        {{-- 注文ヘッダー --}}
                        <div class="flex justify-between items-center mb-2">
                            <h2 class="text-lg font-semibold">注文番号: {{ $order->id }}</h2>
                            <p class="text-gray-600 text-sm">
                                注文日: {{ $order->created_at->format('Y/m/d') }}
                            </p>
                        </div>

                        {{-- 注文アイテム一覧 --}}
                        <div>
                            <h3 class="text-lg font-semibold mb-2">注文商品</h3>
                            <div class="space-y-4">
                                @foreach($order->orderItems as $item)
                                    <div class="flex items-center space-x-4">
                                        <img 
                                            src="{{ $item->product->image_url ?? '/images/no-image.png' }}" 
                                            alt="{{ $item->product->title }}" 
                                            class="w-16 h-16 object-cover rounded"
                                        />
                                        <div>
                                            <p class="font-semibold">{{ $item->product->title }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ number_format($item->price) }}円 × {{ $item->quantity }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 合計金額とステータス表示 --}}
                        <div class="mb-4">
                            <p class="text-gray-700 mt-3">合計金額: {{ number_format($order->total_amount) }}円</p>
                            <div class="flex items-center space-x-2 mt-2">
                                @if($order->status === 'pending')
                                    <img src="{{ asset('images/status-pending.svg') }}" alt="保留中" class="max-w-[95%] min-w-[350px] mx-auto max-[500px]:hidden">
                                    <span class="text-sm text-gray-600">保留中</span>
                                @elseif($order->status === 'processing')
                                    <img src="{{ asset('images/status-purchase.svg') }}" alt="購入手続き中" class="max-w-[95%] min-w-[350px] mx-auto max-[500px]:hidden">
                                    <span class="text-sm text-gray-600">購入手続き中</span>
                                @elseif($order->status === 'completed')
                                    <img src="{{ asset('images/status-shipping.svg') }}" alt="発送準備中" class="max-w-[95%] min-w-[350px] mx-auto max-[500px]:hidden">
                                    <span class="text-sm text-gray-600">発送準備中</span>
                                @elseif($order->status === 'cancelled')
                                    <img src="{{ asset('images/status-cancel.svg') }}" alt="キャンセル" class="max-w-[95%] min-w-[350px] mx-auto max-[500px]:hidden">
                                    <span class="text-sm text-gray-600">キャンセル</span>
                                @endif
                            </div>
                        </div>

                        {{-- 詳細リンク --}}
                        <div class="mt-4 text-right">
                            <a href="{{ route('orders.show', $order->id) }}"
                               class="text-blue-500 underline">
                                詳細を見る
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">購入履歴はありません。</p>
                @endforelse
            </div>
        </main>
    </div>
</x-front-layout>