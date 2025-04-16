<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // カート一覧の表示
    public function index(Request $request)
    {
        // セッションからカート情報を取得（なければ空の配列）
        $cart = $request->session()->get('cart', []);
        
        // カート内の各商品について、Product モデルから詳細を取得する
        $products = [];
        $total = 0;
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $product->quantity = $quantity;
                // 後で税込価格に変更する！！
                $product->subtotal = $product->taxed_price * $quantity;
                $products[] = $product;
                $total += $product->subtotal;
            }
        }
        
        return view('front.cart', compact('products', 'total'));
    }

    // カートに商品を追加する処理
    public function add(Request $request, $id)
    {
        // セッションからカート情報を取得（なければ空の配列）
        $cart = $request->session()->get('cart', []);

        // もしすでにカートに存在するなら数量を増やす、なければ1を設定
        if (isset($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        // セッションに更新したカート情報を保存
        $request->session()->put('cart', $cart);

        // AJAX リクエストの場合は JSON レスポンスを返す
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '商品がカートに追加されました。',
                'cart' => $cart
            ]);
        }

        return redirect()->route('cart.index')->with('success', '商品がカートに追加されました。');
    }

        // カート内商品の数量更新
        public function update(Request $request, $id)
        {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);
    
            $cart = $request->session()->get('cart', []);
            if (isset($cart[$id])) {
                $cart[$id] = $request->input('quantity');
                $request->session()->put('cart', $cart);
            }
    
            return redirect()->route('cart.index')->with('success', 'カートが更新されました。');
        }
    
        // カートから商品を削除
        public function remove(Request $request, $id)
        {
            $cart = $request->session()->get('cart', []);
            if (isset($cart[$id])) {
                unset($cart[$id]);
                $request->session()->put('cart', $cart);
            }
    
            return redirect()->route('cart.index')->with('success', '商品がカートから削除されました。');
        }
}
