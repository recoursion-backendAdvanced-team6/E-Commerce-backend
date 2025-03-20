<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index($page='dashboard', $subpage='') {
        $path = $subpage ? "{$page}/{$subpage}" : $page;

        // バリデーションを行い、該当しないパスを404にリダイレクトする
        $validPagesToAdmin = ['dashboard', 'products', 'product/create', 'orders'];

        if(!in_array($path, $validPagesToAdmin)){
            abort(404, 'ページが見つかりません');
        }

        // ダミーデータ
        $data = ['test' => 'これはダミーです'];
        return view('admin.dashboard', compact('path', 'data'));
    }
}
