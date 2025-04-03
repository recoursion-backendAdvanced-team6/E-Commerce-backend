<?php

namespace App\Http\Controllers;

use App\Models\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()
                    ->with('orderItems.product')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // ログインユーザーの注文かチェックするなど
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('orders.show', compact('order'));
    }
}
