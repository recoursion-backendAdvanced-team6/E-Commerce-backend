<?php

namespace App\Http\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Invoice;
use Stripe\InvoiceItem;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Models\Order;

class CustomCashierWebhookController extends CashierWebhookController
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        $env = App::environment();

        $event = null;

        if ($env === 'local') {
            Log::info("[LOCAL] Skipping signature verification");
            $event = json_decode($payload);
        } else {
            $secret = match ($env) {
                'production' => env('STRIPE_WEBHOOK_SECRET_MAIN_PRODUCTION'),
                'staging' => env('STRIPE_WEBHOOK_SECRET_MAIN_STAGING'),
                default => null,
            };

            try {
                $event = Webhook::constructEvent($payload, $sigHeader, $secret);
            } catch (\UnexpectedValueException | SignatureVerificationException $e) {
                Log::error("Stripe Webhook verification failed", ['error' => $e->getMessage()]);
                return response('Invalid payload or signature', 400);
            }
        }

        // Cashier の標準処理を実行する
        $response = parent::handleWebhook($request);
        
        // Checkout Session 完了時の処理
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            Log::info("Checkout session completed", ['session_id' => $session->id]);

            // Checkout Session のIDを使って Order を特定
            $order = Order::where('stripe_checkout_session_id', $session->id)->first();
            if ($order) {
                Log::info("Order found", ['order_id' => $order->id, 'total_amount' => $order->total_amount]);
                Stripe::setApiKey(config('services.stripe.secret'));

                try {
                    // Invoice Item を作成（注文金額を反映）
                    $invoiceItem = InvoiceItem::create([
                        'customer'    => $session->customer,
                        'amount'      => (int) round($order->total_amount), // 日本円の場合、1円=1単位と仮定
                        'currency'    => 'jpy',
                        'description' => "Order #{$order->id} Purchase",
                    ]);
                    Log::info("Invoice item created", ['invoiceItem_id' => $invoiceItem->id]);

                    // Invoice の作成。ここで Order ID を metadata に設定
                    $invoice = Invoice::create([
                        'customer'           => $session->customer,
                        'collection_method'  => 'send_invoice',
                        'days_until_due'     => 30,
                        'metadata'           => ['order_id' => $order->id],
                    ]);
                    Log::info("Invoice created", ['invoice_id' => $invoice->id]);

                    // 請求書の最終化
                    $finalizedInvoice = $invoice->finalizeInvoice();

                    // Order に請求書情報を保存し、ステータスも更新 
                    $order->update([
                        'stripe_invoice_url' => $finalizedInvoice->hosted_invoice_url,
                        'stripe_invoice_id' => $finalizedInvoice->id,
                        'status' => 'completed',
                    ]);
                } catch (\Exception $e) {
                    Log::error("Invoice generation failed for Order #{$order->id}: " . $e->getMessage());
                }
            } else {
                Log::error("No Order found for Checkout Session ID: " . $session->id);
            }
        } else {
            Log::info("Received event type: " . $event->type);
        }

        return $response;
    }
}
