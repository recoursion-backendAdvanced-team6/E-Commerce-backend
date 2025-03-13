<?php
namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FrontLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // 初期化処理が必要な場合はここに記述
    }

    /**
     * このコンポーネントを表すビュー/コンテンツを取得します。
     * renderメソッドは、このコンポーネントがどのビュー（Bladeテンプレート）を使用するかを指定します。
     * 
     * @return View|Closure|string このコンポーネントのビューを返します。
     */
    public function render(): View|Closure|string
    {
        return view('layouts.front');
    }
}