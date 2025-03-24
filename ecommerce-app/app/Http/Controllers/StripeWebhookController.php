<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload    = $request->getContent();
        $sigHeader  = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            // 無効なペイロードの場合
            return response('', 400);
        } catch (SignatureVerificationException $e) {
            // シグネチャ検証に失敗した場合
            return response('', 400);
        }

        // イベントのタイプに応じた処理
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object; // Checkout Session オブジェクト

            // セッションIDを利用して該当する注文を検索（checkoutSession のIDは決済セッションID）
            $order = Order::where('stripe_checkout_session_id', $session->id)->first();

            if ($order) {
                // 決済成功なので、注文ステータスを "completed" に更新するなど
                $order->update(['status' => 'completed']);
            }
        }

        // 他のイベントタイプにも必要に応じた処理を追加可能

        return response()->json(['status' => 'success']);
    }
}
