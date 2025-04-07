<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;

class HomeController extends Controller
{
    public function index() {

        // 新作
        $newReleases = Product::with('author')
            ->where('status', 'published')
            ->where('inventory', '>=', 1)
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        // 人気：ロジック後で変更する
        $popular = Product::with('author')
            ->where('status', 'published')
            ->where('inventory', '>=', 1)
            ->orderByDesc('inventory')
            ->take(4)
            ->get();

        return view('front.home', compact('newReleases', 'popular'));
    }
}
