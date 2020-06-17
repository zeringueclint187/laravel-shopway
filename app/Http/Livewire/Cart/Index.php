<?php

namespace App\Http\Livewire\Cart;

use App\Helpers\Cart;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Services\Cart\CartManager;

class Index extends Component
{
    protected $listeners = ['itemChanged' => "render"];

    public function render()
    {
        return view('livewire.cart.index', [
            'cartReferences' => session('cart'),
            'totalWithoutTax' => Cart::totalWithoutTax(),
            'totalWithTax' => Cart::totalWithTax(),
            'totalTax' => Cart::totalTax(),
        ]);
    }
}