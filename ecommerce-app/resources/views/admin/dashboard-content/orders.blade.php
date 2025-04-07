@props(['orderItems'])
<x-admin.dashboard-layout>

    <div class='min-w-max'>
        <div class="overflow-x-auto">
            <table class="min-w-full mb-6 border-collapse mx-auto">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-3">商品名</th>
                        <th class="text-left p-3">価格(税込)</th>
                        <th class="text-center p-3">数量</th>
                        <th class="text-right p-3">小計</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderItems as $item)
                        @php $product = $item->product; @endphp
                        @if($product)
                            <tr class="border-b">    
                                <td class="p-3">
                                    <a href="" class="flex items-center space-x-2">
                                        <img src="{{ $product->image_url ?? '/images/no-image.png' }}"
                                            alt="{{ $product->title ? $product->title : ''}}"
                                            class="w-12 h-auto rounded">
                                        <div>
                                            <div class="font-semibold">{{ $product->title }}</div>
                                            <div class="text-xs text-gray-500">カテゴリ: {{ $product->category->name ?? '未分類' }}</div>
                                        </div>
                                    </a>
                                </td>
                                <td class="p-3">¥{{ number_format($product->taxed_price) }}</td>
                                <td class="p-3 text-center">{{ number_format($item->quantity) }}</td>
                                <td class="p-3 text-right">¥{{ number_format($item->price) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin.dashboard-layout>