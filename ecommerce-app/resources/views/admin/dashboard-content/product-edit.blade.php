@props(['product'])

<x-admin.dashboard-layout>
    <x-flash-message/>
    @foreach ($errors->all() as $error )
        <div class="mb-4 p-4 rounded bg-red-100 text-red-800">
            {{ $error }}
        </div>
    @endforeach


    <form action='{{route('admin.products.update', $product->id)}}' method="POST" enctype="multipart/form-data" class="max-w-3xl mx-auto space-y-6">
        @csrf
        @method('PUT')

        <div class="max-w-3xl mx-auto space-y-16">
            <div class="grid grid-cols-1 grid-rows-2  items-center gap-1">

                <div class='flex gap-x-2 items-center'>
                    <label for="title" class="text-base">タイトル</label> 
                    @error('title')
                    <span class='text-sm text-red-800 px-1 rounded-sm bg-red-100'>修正</span> 
                    @enderror
                </div>

                <input type="text" name="title" id="title"
                    class="border border-gray-300 rounded-md  w-full" value="{{ old('title', $product->title) }}">
            </div>

            <div class="grid grid-cols-1 grid-rows-2 items-center gap-1">
                <div class='flex gap-x-2 items-center'>
                    <label for="price" class="text-base">価格</label>
                    @error('price')
                    <span class='text-sm text-red-800 px-1 rounded-sm bg-red-100'>修正</span> 
                    @enderror
                </div>
                <input type="number" name="price" id="price"
                    class=" border border-gray-300 rounded-md  w-full" value="{{ old('price', $product->price) }}">
            </div>

            <div class="grid grid-cols-1 grid-rows-1 gap-2">
                <div class='flex gap-x-2 items-center'>
                    <label for="image" class="text-base row-start-1">画像</label>
                    @error('image')
                    <span class='text-sm text-red-800 px-1 rounded-sm bg-red-100'>修正</span> 
                    @enderror
                </div>
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
                <div class='flex gap-x-2 items-center'>
                    <label for="is_digital" class="text-base">商品タイプ</label>
                    @error('is_digital')
                    <span class='text-sm text-red-800 px-1 rounded-sm bg-red-100'>修正</span> 
                    @enderror
                </div>
                <label class="inline-flex items-center gap-2 row-start-2">
                    <input type="radio" name="is_digital" value='0'
                        {{ old('is_digital', $product->is_digital) === 1 ? 'checked' : '' }}>
                    <span>デジタル商品</span>
                </label>
                <label class="inline-flex items-center gap-2 row-start-2">
                    <input type="radio" name="is_digital" value="1"
                        {{ old('is_digital', $product->is_digital) === 0 ? 'checked' : '' }}>
                    <span>物理商品</span>
                </label>
            </div>

            <div class="grid grid-cols-1 grid-rows-2 md:grid-cols-3 items-center gap-2 ">
                <div class='flex gap-x-2 items-center'>
                    <label for="inventory" class="text-base">公開</label>
                    @error('status')
                    <span class='text-sm text-red-800 px-1 rounded-sm bg-red-100'>修正</span> 
                    @enderror
                </div>
                <label class="inline-flex items-center gap-2 row-start-2">
                    <input type="radio" name="status" value="draft"
                        {{ old('status', $product->status) === 'draft' ? 'checked' : '' }}>
                    <span>下書き</span>
                </label>
                <label class="inline-flex items-center gap-2 row-start-2">
                    <input type="radio" name="status" value="published"
                        {{ old('status', $product->status) === 'published' ? 'checked' : '' }}>
                    <span>公開</span>
                </label>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" onclick="history.back()" class="text-sm/6 font-semibold rounded-md p-2 text-gray-900 hover:bg-gray-200">キャンセル</button>
            <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">編集</button>
        </div>

        <!-- 他の項目も同様に -->
    </form>

@push('scripts')
    @vite('resources/js/productImagePreview.js') 
@endpush
</x-admin.dashboard-layout>