<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

class CheckoutController extends Controller
{
    // 配送先入力フォームの表示
    public function showShippingForm(Request $request)
    {
        // ログインユーザーがいる場合、既存の住所情報を初期として渡す
        $user = $request->user();
        // カートの情報もセッションから取得
        $sessionCart = $request->session()->get('cart', []);

        // Blade で使うための $cart を作り直す
        $cart = [];

        // sessionCart をループし、productId から Product モデルを取得して、quantity をセット
        foreach ($sessionCart as $productId => $qty) {
            $product = \App\Models\Product::find($productId);
            if ($product) {
                // 数量をプロパティとして設定
                $product->quantity = $qty;
                $cart[] = $product;
            }
        }

        return view('checkout.shipping', compact('user', 'cart'));
    }

    public function storeShipping(Request $request)
    {
        // address_type が既に選択されているか確認（ログインユーザーの場合のみ有効）
        if ($request->has('address_type') && $request->input('address_type') === 'existing' && $request->user()) {
            // ログインユーザーの既存住所情報を使用
            $user = $request->user();
            $shippingData = [
                'name'           => $user->name,
                'email'          => $user->email,
                'country'        => $user->country,
                'zipcode'        => $user->zipcode,
                'street_address' => $user->street_address,
                'city'           => $user->city,
                'phone'          => $user->phone,
            ];
        } else {
            // 新しい住所が入力される場合はバリデーションを実施
            $shippingData = $request->validate([
                'name'           => 'required|string|max:255',
                'email'          => 'required|email|max:255',
                'country'        => 'required|string|max:100',
                'zipcode'        => 'required|string|max:20',
                'street_address' => 'required|string',
                'city'           => 'required|string|max:255',
                'phone'          => 'required|string|max:20',
            ]);
        }
    
        // 住所情報をセッションに保存して、次の支払いページに渡す
        $request->session()->put('shipping', $shippingData);
    
        return redirect()->route('checkout.payment');
    }

    // 支払い方法と最終確認ページの表示
    public function showPaymentForm(Request $request)
    {
        // $shipping = $request->session()->get('shipping');
        
        // // カートの情報もセッションから取得
        // $cart = $request->session()->get('cart', []);
        
        // $total = 0;
        // foreach ($cart as $productId => $quanity) {
        //     $product = Product::find($productId);
        //     if ($product) {
        //         $total += $product->taxed_price * $quanity;
        //     }
        // }

        // return view('checkout.payment', compact('shipping', 'cart', 'total'));



        $shipping = $request->session()->get('shipping');
        $sessionCart = $request->session()->get('cart', []);
        $total = 0;
        $cart = [];
        foreach ($sessionCart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $product->quantity = $quantity;
                $subtotal = $product->taxed_price * $quantity;
                $total += $subtotal;
                $cart[] = $product;
            }
        }
        return view('checkout.payment', compact('shipping', 'cart', 'total'));
    }

    // 要修正！！
    // 支払い処理と注文確定
    public function processPayment(Request $request)
    {
        // セッションから配送先情報とカート情報を取得
        $shipping = $request->session()->get('shipping');
        $sessionCart = $request->session()->get('cart', []);
        $total = 0;
        $orderItems = [];

        // カート情報（形式: [productId => quantity]）をループして各商品の小計と合計を計算
        foreach ($sessionCart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $itemPrice = $product->taxed_price; // 税込価格（アクセサ利用）
                $subtotal = $itemPrice * $quantity;
                $total += $subtotal;
                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity'   => $quantity,
                    'price'      => $itemPrice,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // ユーザーがログインしていなければ、ゲスト用に仮ユーザーを作成する
        $user = $request->user();
        if (!$user) {
            $guestEmail = 'guest+' . Str::random(8) . '@example.com';
            $user = User::create([
                'name'           => $shipping['name'],
                'email'          => $guestEmail,
                'country'        => $shipping['country'],
                'zipcode'        => $shipping['zipcode'],
                'street_address' => $shipping['street_address'],
                'city'           => $shipping['city'],
                'phone'          => $shipping['phone'],
                'password'       => bcrypt(Str::random(16)),  // 仮パスワード
            ]);
        }

        // 注文レコードの作成（stripe_checkout_session_id は後で更新）
        $order = Order::create([
            'user_id'                     => $user->id,
            'stripe_checkout_session_id'  => 'dummy_session_id',
            'total_amount'                => $total,
            'status'                      => 'pending',
            'shipping_name'               => $shipping['name'],
            'shipping_email'              => $shipping['email'],
            'shipping_country'            => $shipping['country'],
            'shipping_zipcode'            => $shipping['zipcode'],
            'shipping_street_address'     => $shipping['street_address'],
            'shipping_city'               => $shipping['city'],
            'shipping_phone'              => $shipping['phone'],
        ]);

        // 注文商品の明細を order_items テーブルに挿入
        foreach ($orderItems as $item) {
            $item['order_id'] = $order->id;
            \DB::table('order_items')->insert($item);
        }

        // 支払い金額の設定（日本円の場合、1円＝1単位として計算）
        $amount = (int) $total;

        // オプション配列を準備（通貨は.envの設定から自動で決まるため指定不要）
        $options = [
            'success_url' => (string) route('order.complete'),
            'cancel_url'  => (string) route('checkout.payment'),
            // 'payment_method_types' => ['card'], // 必要なら追加、エラーが出る場合はコメントアウト
        ];

        // Stripe Checkout セッションの生成とリダイレクトのため、checkoutCharge() ではなく redirectToCheckout() を使用
        $redirectResponse = $user->redirectToCheckout($amount, $options);

        // 注文レコードの stripe_checkout_session_id を更新（通常は webhook で行うのが望ましい）
        $order->update([
            'stripe_checkout_session_id' => $redirectResponse->getTargetUrl(), // ※ checkoutCharge() ではなく、リダイレクト URL からセッションIDを取得する方法はケースによります
        ]);

        // セッションのカートと配送情報をクリア
        $request->session()->forget('cart');
        $request->session()->forget('shipping');

        // Stripe Checkout セッションへリダイレクト
        return $redirectResponse;
    }
}
