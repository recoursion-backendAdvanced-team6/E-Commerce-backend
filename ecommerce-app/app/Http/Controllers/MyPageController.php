<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyPageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 最新3件の「お気に入り」
        $favorites = $user->favorites()
                            ->wherePivot('deleted_at', NULL)
                            ->orderBy('favorites.updated_at', 'desc')
                            ->take(3)
                            ->get();

        // 最新1件の「注文履歴」
        $orders = $user->orders()->latest()->take(1)->get();

        return view('mypage.index', compact('user', 'favorites', 'orders'));
    }
}
