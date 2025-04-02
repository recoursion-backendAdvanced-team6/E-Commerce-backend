<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class AdminProductController extends Controller
{
    //商品の編集画面を表示
    public function edit(Product $product){
        
        return view('admin.dashboard-content.product-edit', compact('product'));
    }

    //更新情報を処理する
    public function update(){

    }

    // アイテムを削除
    public function destroy(){
    }
}
