<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    // mass assignment で更新可能な属性を限定
    protected $fillable = ['name'];

    // Tag に関連する製品のリレーション
    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }
}
