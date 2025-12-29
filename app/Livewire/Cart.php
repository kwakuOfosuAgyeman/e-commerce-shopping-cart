<?php

namespace App\Livewire;

use App\Models\Cart as CartModel;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Cart extends Component
{
    public function updateQuantity(int $cartItemId, int $quantity): void
    {
        $cartItem = CartItem::whereHas('cart', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($cartItemId);

        if ($quantity < 1) {
            $this->removeItem($cartItemId);
            return;
        }

        if ($quantity > $cartItem->product->stock) {
            session()->flash('error', 'Not enough stock available.');
            return;
        }

        $cartItem->update(['quantity' => $quantity]);
        $this->dispatch('cart-updated');
    }

    public function incrementQuantity(int $cartItemId): void
    {
        $cartItem = CartItem::whereHas('cart', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($cartItemId);

        if ($cartItem->quantity < $cartItem->product->stock) {
            $cartItem->increment('quantity');
            $this->dispatch('cart-updated');
        } else {
            session()->flash('error', 'Not enough stock available.');
        }
    }

    public function decrementQuantity(int $cartItemId): void
    {
        $cartItem = CartItem::whereHas('cart', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($cartItemId);

        if ($cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
            $this->dispatch('cart-updated');
        } else {
            $this->removeItem($cartItemId);
        }
    }

    public function removeItem(int $cartItemId): void
    {
        CartItem::whereHas('cart', function ($query) {
            $query->where('user_id', Auth::id());
        })->where('id', $cartItemId)->delete();

        session()->flash('success', 'Item removed from cart.');
        $this->dispatch('cart-updated');
    }

    public function clearCart(): void
    {
        $cart = CartModel::where('user_id', Auth::id())->first();

        if ($cart) {
            $cart->items()->delete();
        }

        session()->flash('success', 'Cart cleared.');
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        $cart = CartModel::with(['items.product'])
            ->where('user_id', Auth::id())
            ->first();

        $cartItems = $cart ? $cart->items : collect();
        $total = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

        return view('livewire.cart', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }
}
