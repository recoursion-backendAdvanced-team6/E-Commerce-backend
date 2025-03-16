<?php

namespace App\View\Components\Cards;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class productCard extends Component
{
    public Product $product;
    public string $color;

    /**
     * Create a new component instance.
     */
    public function __construct(Product $product, string $color)
    {
        $this->product = $product;
        $this->color = $color;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cards.product-card');
    }
}
