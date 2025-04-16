<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\OrderItem;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'stripe_checkout_session_id',
        'total_amount',
        'status',
        'shipping_name',
        'shipping_email',
        'shipping_country',
        'shipping_zipcode',
        'shipping_street_address',
        'shipping_city',
        'shipping_phone',
        'stripe_invoice_url',
        'stripe_invoice_id',
    ];

    public function items(){
        return $this->hasMany(OrderItem::class);
    }
}
