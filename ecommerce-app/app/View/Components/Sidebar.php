<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    /**
     * Create a new component instance.
     */
    public array $menuItems;

    public function __construct()
    {
        /**
         * 概要:
         * 管理者とユーザーの管理画面のサイドバーに使用できる
         * 今の段階では管理者のみの実装になっています
         * 
         * 必要な処理:
         * 管理者とユーザーを区別して、設定するデータを変更する
         * 
        */ 
        
        // 管理者
        $this->menuItems =  [
            ['label' => '商品一覧', 'url' => route('admin.products.index')],
            ['label' => '注文一覧', 'url' => route('admin.dashboard.orders')],
            ['label' => 'ログアウト', 'url' => route('admin.logout')],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar', ['menuItems' => $this->menuItems]);
    }
}
