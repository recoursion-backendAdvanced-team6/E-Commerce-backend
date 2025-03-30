@props(['products'])

<x-admin.dashboard-layout>
    <div class='flex mb-5' >
        <a 
            class="ml-auto border-2 border-indigo-400 rounded-sm p-2" 
            href='https://dashboard.stripe.com/' 
            target='_blank'
            >
            商品を追加
        </a>
    </div>
    <div class='flex w-full justify-center'> 
        <div class='flex  flex-col gap-y-5 '>
        @foreach ( $products as $i=> $product)
            <div class='grid grid-cols-3 grid-rows-1 gap-x-5 pb-5 border-b-2'>
                <div class='flex justify-center'>
                    <div class='w-32  overflow-hidden '>
                        <img 
                            src="{{ $product->image_url ?? '/images/no-image.png' }}"  
                            class="w-full h-auto object-cover" 
                        />
                    </div>

                </div>
                <div class='flex flex-col gap-y-7 '>
                    <p class='text-xl'>  {{ $product->title  }}  </p>
                    <div class='flex felx-wrap gap-x-2'>
                        <p class='border border-green-400 rounded-md px-1  text-xs text-green-600'>tag1</p>
                        <p class='border border-green-400 rounded-md px-1  text-xs text-green-600'>tag2</p>
                    </div>
                    <div class='flex flex-col gap-y-2'>
                        <p class='text-sm'> 在庫数: {{ $product->inventory  }}  </p>
                        <p class='text-sm text-gray-500'> 公開状態: {{ $product->status === 'draft' ? '下書き' : '公開' }}  </p>
                    </div>
                </div>
                <div class='flex flex-col grid-span-end gap-y-5 justify-end'>
                    <p class='text-base'> 販売価格: {{ number_format($product->taxed_price) }}円（税込）</p>
                </div>
            </div>
        @endforeach
        </div>

    </div>
</x-admin.dashboard-layout>