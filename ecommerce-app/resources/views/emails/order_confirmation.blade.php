{{-- resources/views/emails/order_confirmation.blade.php --}}
@component('mail::message')
# ご注文ありがとうございます

{{ $order->shipping_name }} 様

以下の内容でご注文を承りました。

@component('mail::table')
| 商品名        | 数量  | 価格       |
| ------------- |:-----:| ----------:|
@foreach ($order->orderItems as $item)
| {{ $item->product->title ?? '不明な商品' }} | {{ $item->quantity }} | {{ number_format($item->price) }}円 |
@endforeach
@endcomponent

**合計金額:** {{ number_format($order->total_amount) }}円

今後の配送状況等は別途ご連絡いたします。

Thanks,<br>
{{ config('app.name') }}
@endcomponent
