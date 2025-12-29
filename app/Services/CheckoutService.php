<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CheckoutService
{
    /**
     * Get checkout data with cart items and totals
     */
    public function getCheckoutData(): array
    {
        if (!Auth::check()) {
            return [
                'cartItems' => collect(),
                'subtotal' => 0,
                'total' => 0,
            ];
        }

        $cart = Cart::with(['items.product.brand'])
            ->where('user_id', Auth::id())
            ->first();

        $cartItems = $cart ? $cart->items : collect();

        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ];
    }

    /**
     * Get cart item count
     */
    public function getCartItemCount(): int
    {
        if (!Auth::check()) {
            return 0;
        }

        $cart = Cart::where('user_id', Auth::id())->first();
        return $cart ? $cart->items()->sum('quantity') : 0;
    }
}
