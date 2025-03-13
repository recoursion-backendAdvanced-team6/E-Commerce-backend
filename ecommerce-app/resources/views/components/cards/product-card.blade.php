<div class="bg-{{ $color }}-200 p-4 rounded shadow">
    <h2 class="text-2xl font-bold mb-2">{{ $product->title }}</h2>
    <p class="mb-1"><strong>Protein:</strong> {{ $product->description }}g</p>
    <p class="mb-1"><strong>Carbs:</strong> {{ $product->category_id }}g</p>
    <p class="mb-1"><strong>Fat:</strong> {{ $product->price }}g</p>
    <p class="mb-1"><strong>日付:</strong> {{ $product->created_at ?? 'N/A' }}</p>
    <div class="mt-auto">
        {{ $slot }}
    </div>
</div>