@props(['product'])

<x-admin.dashboard-layout>

    <form action='{{route('admin.products.update', $product->id)}}' method="POST" enctype="multipart/form-data" class="max-w-3xl mx-auto space-y-6">
        @csrf
        @method('PUT')

        <div class="max-w-3xl mx-auto space-y-16">
            <div class="grid grid-cols-1 md:grid-cols-3 items-center gap-2">
                <label for="title" class="text-base">タイトル</label>
                <input type="text" name="title" id="title"
                    class="md:col-span-2 border border-gray-300 rounded-md px-4 py-2 w-full" value="{{ old('title', $product->title) }}">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 items-center gap-2">
                <label for="price" class="text-base">価格</label>
                <input type="number" name="price" id="price"
                    class="md:col-span-2 border border-gray-300 rounded-md px-4 py-2 w-full" value="{{ old('price', $product->price) }}">
            </div>

            <div class="grid grid-cols-1  md:grid-cols-3 items-center">
                <label for="image" class="text-base">画像</label>
                <div class='col-span-1'>
                    @if ($product->image_url)
                        <img id="preview" src="{{  $product->image_url }}" class="w-32 mb-4  mx-auto" />
                    @endif
                </div>
                <input type="file" name="image" id="image"  accept="image/*"  onchange="previewImage(event)"
                    class="md:col-span-1 border rounded px-2 py-2 w-full" >
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 items-center gap-2">
                <label for="description" class="text-base">説明</label>
                <textarea  name="description" id="description" row="10" 
                    class="md:col-span-2 border border-gray-300 rounded-md px-4 py-2 w-full h-40 focus:ring-2" >{{ old('description', $product->description) }}
                </textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 items-center gap-2">
                <label for="inventory" class="text-base">在庫</label>
                <input type="number" name="inventory" id="inventory"
                    class="md:col-span-2 border border-gray-300 rounded-md px-4 py-2 w-full" value="{{ old('inventory', $product->inventory) }}">
            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 items-center gap-2 ">
                <label for="inventory" class="text-base">公開</label>
                    <label class="inline-flex items-center gap-2 ">
                        <input type="radio" name="status" value="draft"
                            {{ old('status', $product->status) === 'draft' ? 'checked' : '' }}>
                        <span>下書き</span>
                    </label>
                    <label class="inline-flex items-center gap-2 ">
                        <input type="radio" name="status" value="public"
                            {{ old('status', $product->status) === 'published' ? 'checked' : '' }}>
                        <span>公開</span>
                    </label>
            </div>

        </div>

        <!-- 他の項目も同様に -->
    </form>

@push('scripts')
    @vite('resources/js/productImagePreview.js') 
@endpush
</x-admin.dashboard-layout>