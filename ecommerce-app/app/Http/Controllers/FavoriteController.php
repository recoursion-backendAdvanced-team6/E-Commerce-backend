<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        //Userモデルに favorites() リレーションが定義してる
        $favorites = $user->favorites;

        return view('favorites.index', compact('favorites'));
    }
}
