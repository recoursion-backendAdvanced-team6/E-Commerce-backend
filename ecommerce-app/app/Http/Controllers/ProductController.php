<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('author')
            ->where('status', 'published')
            ->where('inventory', '>=', 1)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('front.product', compact('products'));
    }

    /**
     * カテゴリ別
     */
    public function category(Category $category)
    {
        $products = Product::with('author')
            ->where('status', 'published')
            ->where('inventory', '>=', 1)
            ->where('category_id', $category->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        // category も渡して、ビュー側でパンくずリストやタイトルに利用できるようにする
        return view('front.product', compact('products', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // $product は単一のモデルインスタンス
        return view('front.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
