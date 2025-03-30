<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;

class OrderController extends Controller
{
    public function complete(Request $request)
    {
        // リクエストからCheckoutセッションIDを取得
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('front.home')->with('error', 'セッションIDが見つかりませんでした。');
        }

        // CheckoutセッションIDから注文を検索
        $order = Order::where('stripe_checkout_session_id', $sessionId)->first();

        if (!$order) {
            return redirect()->route('front.home')->with('error', '注文が見つかりませんでした。');
        }

        // 在庫を更新する
        foreach ($order->orderItems as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->decrement('inventory', $item->quantity);
            }
        }

        // 注文のステータスを更新
        $order->update(['status' => 'completed']);

        // メール送信
        Mail::to($order->shipping_email)->send(new OrderConfirmationMail($order));

        return view('checkout.complete', compact('order'));
    }
}
