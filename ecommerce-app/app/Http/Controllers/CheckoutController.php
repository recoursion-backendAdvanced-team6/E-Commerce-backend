<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
            $rules = [
                'name'           => 'required|string|max:255',
                'email'          => 'required|email|max:255',
                'country'        => 'required|string|max:100',
                'zipcode'        => 'required|string|max:20',
                'street_address' => 'required|string|max:100',
                'city'           => 'required|string|max:255|max:100',
                'phone'          => 'required|string|max:20|regex:/^\+?[0-9]{10,15}$/',
            ];

            // ログインユーザーが存在する場合
            if ($request->user()) {
                // ログインユーザーの情報を適用するため、emailとphoneのバリデーションをスキップ
                unset($rules['email'], $rules['phone']);

                // バリデーションを実行
                $shippingData = $request->validate($rules);

                // 新しい住所を入力する場合でも、emailとphoneはユーザー情報を使用
                $user = $request->user();
                $shippingData['email'] = $user->email;
                $shippingData['phone'] = $user->phone;
            } else {
                // ログインしていない場合のバリデーション
                $shippingData = $request->validate($rules);
            }
        }
    
        // 住所情報をセッションに保存して、次の支払いページに渡す
        $request->session()->put('shipping', $shippingData);
    
        return redirect()->route('checkout.payment');
    }

    // 支払い方法と最終確認ページの表示
    public function showPaymentForm(Request $request)
    {
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

    // 支払い処理と注文確定
    public function processPayment(Request $request)
    {
        $shipping = $request->session()->get('shipping');
        $sessionCart = $request->session()->get('cart', []);
        $total = 0;

        // Build the Stripe prices mapping array
        $priceMap = [];
        $orderItems = [];

        foreach ($sessionCart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $priceMap[$product->stripe_price_id] = $quantity;
                $itemPrice = $product->taxed_price;
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

        // ログインユーザーがいない場合は、ゲスト用に仮ユーザーを作成または既存のゲストユーザーを利用する
        $user = $request->user();
        if (!$user) {
            // 一意のサフィックスを付与して、内部的にユニークなメールアドレスに変換
            $uniqueEmail = preg_replace('/@/', '+' . Str::random(8) . '@', $shipping['email']);
            $user = User::create([
                'name'           => $shipping['name'],
                'email'          => $uniqueEmail,
                'country'        => $shipping['country'],
                'zipcode'        => $shipping['zipcode'],
                'street_address' => $shipping['street_address'],
                'city'           => $shipping['city'],
                'phone'          => $shipping['phone'],
                'password'       => bcrypt(Str::random(16)),  // 仮パスワード
                'is_guest'       => true,
            ]);
        }

        // 注文レコードの作成（stripe_checkout_session_id は後で更新）
        $order = Order::create([
            'user_id'                     => $user->id,
            'stripe_checkout_session_id'  => 'dummy_session_id',  // Cashierで実際のセッションIDが設定される
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

        foreach ($orderItems as $item) {
            $item['order_id'] = $order->id;
            DB::table('order_items')->insert($item);
        }

        $amount = (int)$total; // 日本円の場合、1円＝1単位
        // Checkout セッションのオプションを設定（通貨は.envのCASHIER_CURRENCY=JPYで自動設定）
        $options = [
            'success_url' => route('order.complete') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('checkout.payment'),
            'metadata'    => ['order_id' => $order->id],
        ];

        // checkout() メソッドを呼び出して、既存の Stripe Price を利用した Checkout セッションを生成
        $checkoutSession = $user->checkout($priceMap, $options);

        // 注文レコードの stripe_checkout_session_id を更新
        $order->update([
            'stripe_checkout_session_id' => $checkoutSession->id,
        ]);

        // セッションのカートと配送情報をクリア
        $request->session()->forget('cart');
        $request->session()->forget('shipping');

        return redirect($checkoutSession->url);
    }
}
