@props(['products'])

<x-admin.dashboard-layout>
<x-flash-message/>

    <div class='flex mb-5' >
        <a 
            class="ml-auto border-2 border-indigo-400 rounded-sm p-2" 
            href='https://dashboard.stripe.com/' 
            target='_blank'
            >
            商品を追加
        </a>
    </div>

    <div class='min-w-max'>
        <div class="overflow-x-auto">
            <table class="min-w-full mb-6 border-collapse mx-auto">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-3">商品名</th>
                        <th class="text-left p-3">価格(税込)</th>
                        <th class="text-left p-3">数量</th>
                        <th class="text-left p-3">公開状態</th>
                        <th class="text-center p-3">小計</th>
                        <th class="text-center p-3">操作</th>
                    </tr>
                </thead>
    
                <tbody>
                    @foreach ($products as $product)
                        <tr class="border-b">
                            {{-- 商品名・画像 --}}
                            <td class="p-3">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $product->image_url ?? '/images/no-image.png' }}" 
                                         class="w-16 h-16 object-cover rounded" 
                                         alt="{{ $product->title }}">
                                    <div>
                                        <p class="font-semibold text-lg">{{ $product->title }}</p>
                                        <div class="flex gap-1 mt-1">
                                            <span class="text-xs border px-1 rounded text-green-600 border-green-400">tag1</span>
                                            <span class="text-xs border px-1 rounded text-green-600 border-green-400">tag2</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
    
                            {{-- 価格 --}}
                            <td class="p-3">
                                ¥{{ number_format($product->taxed_price) }}
                            </td>
    
                            {{-- 数量 --}}
                            <td class="p-3 text-center">
                                {{ $product->inventory }}
                            </td>
    
                            {{-- 公開状態 --}}
                            <td class="p-3 text-center">
                                {{ $product->status === 'draft' ? '下書き' : '公開' }}
                            </td>
    
                            {{-- 小計（仮に価格×数量）--}}
                            <td class="p-3 text-right">
                                ¥{{ number_format($product->taxed_price * $product->inventory) }}
                            </td>
    
                            {{-- 操作 --}}
                            <td class="p-3 text-right space-x-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-800 hover:underline">編集</a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('本当に削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">削除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin.dashboard-layout>