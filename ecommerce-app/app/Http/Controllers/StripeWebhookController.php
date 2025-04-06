<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Stripe\Exception\SignatureVerificationException;

use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Event;

use App\Models\Order;
use App\Models\Product;

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


        // 商品追加時のイベント一覧
        $productCreateEvents = ['price.created','product.created', 'product.deleted'];
        // 商品追加時のイベントの処理
        if(in_array( $event->type,$productCreateEvents)){
            $this->createAnddeleteProduct($event);
        };

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

    public function createAnddeleteProduct(Event $event){

        $data = $event->data->object;

        // 重複しているか確認し、重複している場合はduplicateを返す
        if(Cache::has('webhook_stripe_' . $data->id)){
            return response('', 200);
        }
        Cache::put('webhook_stripe_' . $data->id, true, 60);


        switch ($event->type) {

            // priceをtype: price.createdから取得
            case  'price.created':
                $product = Product::where('stripe_product_id', $data->product)->first();
                if(!$product){
                    Product::updateOrCreate(
                        ['stripe_product_id' => $data->product],
                        [
                            'stripe_price_id' => $data->id,
                            'price' => $data->unit_amount,
                            'image_url' => null,
                            'title' => '',
                            'description' => '',
                            'published_date' => null,
                            'status' => 'draft',
                            'inventory' => 0,
                            'is_digital' => false,
                        ],
                    );
                }else {
                    Product::updateOrCreate(
                        ['stripe_product_id' => $data->product],
                        [
                            'stripe_price_id' => $data->id,
                            'price' => $data->unit_amount,
                        ],
                    );
                }
                break;

            // 作成されたproductをDBに登録
            case 'product.created':
                $product = Product::where('stripe_product_id', $data->id)->first();
                if(!$product){
                    Product::updateOrCreate(
                        ['stripe_product_id' => $data->id],
                        [
                            'image_url' => $data->images[0] ?? null,
                            'title' => $data->name,
                            'description' => $data->description,
                            'published_date' => null,
                            'status' => 'draft',
                            'price' => 0,
                            'inventory' => 0,
                            'is_digital' => false,
                        ],
                    );

                }else {
                    Product::updateOrCreate(
                        ['stripe_product_id' => $data->id],
                        [
                            'image_url' => $data->images[0] ?? null,
                            'title' => $data->name,
                            'description' => $data->description,
                        ],
                    );
                }
                break;
            
            
            case 'product.deleted':
                $stripeProductId = $data->id;
                $product = Product::where('stripe_product_id', $stripeProductId)->first();
                if($product){
                    $product->delete();
                }

                break;
        }

        return response('success', 200);
    }


    public function sendUpdateProductData(Product $product){

    }

    protected function generateProductData(Product $product){

    }

    protected function generatePriceData(Product $product){

    }
}

