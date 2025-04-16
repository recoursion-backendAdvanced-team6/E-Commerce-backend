<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Validation\ValidationException;
use Throwable;

class AdminProductController extends Controller
{
    //商品一覧を表示
    public function index(){
        $products = Product::whereNull('deleted_at')->get();

        return view('admin.dashboard-content.products', compact('products'));
    }

    //商品の編集画面を表示
    public function edit(Product $product){
        return view('admin.dashboard-content.product-edit', compact('product'));
    }


    //更新情報を処理する
    public function update(Request $request, Product $product){

            // 入力のバリデーション
            $validated = $request->validate([
                'title' => ['required','string','max:255'] ,
                'description' => ['nullable', 'string'],
                'price' => ['required','numeric', 'min:0', 'max:9999999999' ],
                'image' =>['nullable', 'image', 'max:2048'],
                'inventory' => ['required', 'integer', 'min:0'],
                'status' => ['required', 'in:draft,published']
            ],
            [
                'title.required' => 'タイトルは必須です',
                'title.max' => 'タイトルは最大10文字までです',
                'price.max' => '金額は最大１０桁までです',
                'image.max' =>'画像の容量は2MBまでです',
                'status.required' => '公開状態を選択してください',
            ]
        
        );

            // 画像の保存
            if($request->hasFile('image')){
                $path = $request->file('image')->store('products', 'public');
                $product->image_url = $path;
            }

            // DBの商品情報の更新
            $product->fill($validated);
            $product->save();

            // stripeAPIで情報を更新
            StripeWebhookController::sendUpdateProductData($product);

            return redirect()
            ->route('admin.products.edit', $product->id)
            ->with([
                'success' => '情報が更新されました',
            ]);

    }

    // アイテムを削除
    public function destroy(Product $product){
        $product->delete();
        StripeWebhookController::deleteProduct($product);

        return redirect()
        ->route('admin.dashboard.products')
        ->with('success', "{$product->title} が削除されました");
    }
}
