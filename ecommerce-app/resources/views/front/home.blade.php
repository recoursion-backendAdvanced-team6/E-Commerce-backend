<x-front-layout>
    <!-- 新作セクション -->
    <section class="bg-pink-50 p-6 mb-6 rounded-3xl">
        <h2 class="text-2xl font-bold text-pink-600 mb-4">新刊</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($newReleases as $product)
                <div class="bg-white rounded-2xl shadow p-4 hover:shadow-md transition">
                    <img 
                        src="{{ $product->image_url ?? '/images/no-image.png' }}" 
                        alt="{{ $product->title }}" 
                        class="w-full h-auto mb-3 rounded-2xl"
                    />
                    <h3 class="text-lg font-semibold text-pink-600 mb-1">
                        {{ $product->title }}
                    </h3>
                    <p class="text-gray-600 text-sm line-clamp-2">
                        {{ $product->description }}
                    </p>
                    <p class="text-gray-600 text-sm line-clamp-2">
                        {{ $product->category ? $product->category->name : '' }}
                    </p>
                    <p class="text-gray-600 text-sm line-clamp-2">
                        {{ $product->price }}円
                    </p>
                </div>
            @endforeach
        </div>
        <!-- 「もっと見る」ボタン -->
        <div class="mt-6 text-right">
            <a href="{{ route('front.products') }}" 
               class="inline-flex items-center justify-center rounded-full border border-pink-600 px-6 py-2 text-pink-600 hover:bg-pink-600 hover:text-white transition">
                <span class="mr-2">もっと見る</span>
                <!-- 矢印アイコン (Heroiconsなどを使用) -->
                <svg xmlns="http://www.w3.org/2000/svg" 
                     fill="none" viewBox="0 0 24 24" 
                     stroke-width="2" stroke="currentColor" 
                     class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M13.5 4.5l6 6m0 0l-6 6m6-6H4.5" />
                </svg>
            </a>
        </div>
    </section>

    <!-- 人気セクション -->
    <section class="bg-blue-50 p-6 mb-6 rounded-3xl">
        <h2 class="text-2xl font-bold text-blue-600 mb-4">人気</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($popular as $product)
                <div class="bg-white rounded-2xl shadow p-4 hover:shadow-md transition">
                    <img 
                        src="{{ $product->image_url ?? '/images/no-image.png' }}" 
                        alt="{{ $product->title }}" 
                        class="w-full h-auto mb-3 rounded-2xl"
                    />
                    <h3 class="text-lg font-semibold text-pink-600 mb-1">
                        {{ $product->title }}
                    </h3>
                    <p class="text-gray-600 text-sm line-clamp-2">
                        {{ $product->description }}
                    </p>
                    <p class="text-gray-600 text-sm line-clamp-2">
                        {{ $product->category ? $product->category->name : '' }}
                    </p>
                    <p class="text-gray-600 text-sm line-clamp-2">
                        {{ $product->price }}円
                    </p>
                </div>
            @endforeach
        </div>
    </section>

    <x-slot:js>
        console.log("Home page loaded - with new & popular sections");
    </x-slot:js>
</x-front-layout>
