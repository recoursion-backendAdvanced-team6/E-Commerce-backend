<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyPageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $favorites = $user->favorites; // Userモデルにお気に入り用のリレーションが定義されていることが前提
        $orders = $user->orders;       // 同様に注文履歴用のリレーション

        return view('mypage.index', compact('user', 'favorites', 'orders'));
    }
}
