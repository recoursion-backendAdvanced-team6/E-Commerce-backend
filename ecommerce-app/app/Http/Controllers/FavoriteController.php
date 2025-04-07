<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // ソフトデリートされていないお気に入りのみを取得
        $favorites = $user->favorites()->wherePivot('deleted_at', null)->get();
    
        return view('favorites.index', compact('favorites'));
    }

    // お気に入り追加（Ajax 対応）
    public function store(Request $request)
    {
        $user = Auth::user();
        $productId = $request->input('product_id');
    
        // ソフトデリートされたレコードも含めて取得（返されるのは Product モデル）
        $favoriteProduct = $user->favorites()->withTrashed()->where('product_id', $productId)->first();
    
        if ($favoriteProduct) {
            $pivot = $favoriteProduct->pivot; // Favorite（Pivot）レコード
            if ($pivot->deleted_at) {
                // ソフトデリートされている場合は復元する
                $pivot->restore();
                $pivot->touch();  // updated_at を更新
                $action = 'restored';
            } else {
                $action = 'already_exists';
            }
        } else {
            // レコードが存在しない場合は新規作成
            $user->favorites()->attach($productId);
            $action = 'created';
        }
    
        return response()->json(['status' => 'success', 'action' => $action]);
    }

    // お気に入り削除（ソフトデリート、Ajax 対応）
    public function destroy($productId, Request $request)
    {
        $user = Auth::user();
        
        // favorites テーブルから、active なレコードを取得
        $favorite = Favorite::where('user_id', $user->id)
                    ->where('product_id', $productId)
                    ->whereNull('deleted_at')
                    ->first();
        
        if ($favorite) {
            $favorite->delete(); // ソフトデリートが実行される
            $action = 'deleted';
        } else {
            $action = 'not_found';
        }
        
        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'action' => $action]);
        }
        
        return back()->with('success', 'お気に入りから削除しました。');
    }
}
