<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Author;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // 必要に応じて、stripe_product_id を含めるか検討
        'stripe_product_id',
        'stripe_price_id',
        'image_url',
        'title',
        'description',
        'category_id',
        'published_date',
        'status',
        'price',
        'inventory',
        'is_digital',
    ];

    // Product が属するタグのリレーション
    public function tags()
    {
        // Eloquent はデフォルトで、products テーブルの category_id に対応する関係を探しますが、
        // belongsToMany では、中間テーブル product_tags を利用し、Product と Tag の関係を管理します。
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    // Categoryのリレーション
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    // Authorのリレーション
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    // favorites リレーションの定義
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id');
    }

    // 税込価格を計算して返すアクセサ
    public function getTaxedPriceAttribute()
    {
        $taxRate = config('tax.rate');
        return $this->price * (1 + $taxRate);
    }
    
}
