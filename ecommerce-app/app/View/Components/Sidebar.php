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
        // adminとuserに対応
        
        $this->menuItems =  [
            ['label' => '商品追加', 'url' => '/admin/dashbord/product/create'],
            ['label' => '商品一覧', 'url' => '/admin/dashbord/product/list'],
            ['label' => '注文一覧', 'url' => '/admin/dashbord/orders'],
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
