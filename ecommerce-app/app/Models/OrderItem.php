<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product;
use App\Models\Order;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }


    public function product(){
        return $this->belongsTo(Product::class);
    }
}
