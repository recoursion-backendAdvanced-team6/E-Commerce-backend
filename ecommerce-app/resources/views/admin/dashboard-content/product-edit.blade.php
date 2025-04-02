@props(['product'])

<x-admin.dashboard-layout>

    <form action='{{route('admin.products.update', $product->id)}}' method="POST" enctype="multipart/form-data" class="max-w-3xl mx-auto space-y-6">
        @csrf
        @method('PUT')

        <div class="max-w-3xl mx-auto space-y-16">
            <div class="grid grid-cols-1 grid-rows-2  items-center gap-1">
                <label for="title" class="text-base">タイトル</label>
                <input type="text" name="title" id="title"
                    class="border border-gray-300 rounded-md  w-full" value="{{ old('title', $product->title) }}">
            </div>

            <div class="grid grid-cols-1 grid-rows-2 items-center gap-1">
                <label for="price" class="text-base">価格</label>
                <input type="number" name="price" id="price"
                    class=" border border-gray-300 rounded-md  w-full" value="{{ old('price', $product->price) }}">
            </div>

            <div class="grid grid-cols-1 grid-rows-1 gap-2">
                <label for="image" class="text-base row-start-1">画像</label>
                <div class='col-span-1'>
                    @if ($product->image_url)
                        <img id="preview" src="{{  $product->image_url }}" class="w-32 " />
                    @endif
                </div>
                <input type="file" name="image" id="image"  accept="image/*"  onchange="previewImage(event)"
                    class="md:col-span-1 border rounded  w-full bg-white mt-2" >
            </div>

            <div class="grid grid-cols-1  grid-rows-1 gap-2">
                <label for="description" class="text-base">説明</label>
                <textarea  name="description" id="description" row="10" 
                    class=" border border-gray-300 rounded-md  w-full h-40 focus:ring-2" >{{ old('description', $product->description) }}
                </textarea>
            </div>

            <div class="grid grid-cols-1 grid-rows-1  gap-2">
                <label for="inventory" class="text-base">在庫</label>
                <input type="number" name="inventory" id="inventory"
                    class=" border border-gray-300 rounded-md  w-full" value="{{ old('inventory', $product->inventory) }}">
            </div>


            <div class="grid grid-cols-1 grid-rows-2 md:grid-cols-3 items-center gap-2 ">
                <label for="inventory" class="text-base">公開</label>
                <label class="inline-flex items-center gap-2 row-start-2">
                    <input type="radio" name="status" value="draft"
                        {{ old('status', $product->status) === 'draft' ? 'checked' : '' }}>
                    <span>下書き</span>
                </label>
                <label class="inline-flex items-center gap-2 row-start-2">
                    <input type="radio" name="status" value="public"
                        {{ old('status', $product->status) === 'published' ? 'checked' : '' }}>
                    <span>公開</span>
                </label>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" class="text-sm/6 font-semibold rounded-md p-2 text-gray-900 hover:bg-gray-200">キャンセル</button>
            <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">編集</button>
        </div>

        <!-- 他の項目も同様に -->
    </form>

@push('scripts')
    @vite('resources/js/productImagePreview.js') 
@endpush
</x-admin.dashboard-layout>