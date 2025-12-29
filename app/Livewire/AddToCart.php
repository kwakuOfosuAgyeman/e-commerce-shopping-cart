<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddToCart extends Component
{
    public Product $product;
    public int $quantity = 1;

    public function mount(Product $product): void
    {
        $this->product = $product;
    }

    public function incrementQuantity(): void
    {
        if ($this->quantity < $this->product->stock) {
            $this->quantity++;
        }
    }

    public function decrementQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart(): void
    {
        if (!Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        if (!$this->product->isInStock()) {
            session()->flash('error', 'Product is out of stock.');
            return;
        }

        if ($this->quantity > $this->product->stock) {
            session()->flash('error', 'Not enough stock available.');
            return;
        }

        // Get or create cart for user
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Check if product already in cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $this->product->id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $this->quantity;

            if ($newQuantity > $this->product->stock) {
                session()->flash('error', 'Cannot add more items. Stock limit reached.');
                return;
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $this->product->id,
                'quantity' => $this->quantity,
            ]);
        }

        $this->quantity = 1;
        session()->flash('success', 'Product added to cart!');
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
