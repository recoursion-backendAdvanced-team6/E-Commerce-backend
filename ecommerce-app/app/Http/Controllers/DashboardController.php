<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;
use App\Models\OrderItem;

class DashboardController extends Controller
{
    /**
     * ダッシュボードページを表示
     * 
     * @return View
     */
    public function home(): View {
        return view('admin.dashboard-content.dashboard');
    }

    /**
     * 商品一覧を取得し、表示
     * 
     * @return View
     */
    public function products(): View{
        $products = Product::whereNull('deleted_at')->get();

        return view('admin.dashboard-content.products', compact('products'));
    }

    /**
     * 注文一覧を取得し、表示
     * 
     * @return View
     */
    public function orders(): View {
        $orderItems = OrderItem::whereHas('order', function($query) {
            // orderからcompletedの値を取得
            $query->where('status', 'completed');
        })
        //取得したorder_idを元にproduct_idからproductを取得
        ->with('order', 'product')
        ->get();

        return view('admin.dashboard-content.orders', compact('orderItems'));
    }
}
