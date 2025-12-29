<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the total number of items in the cart.
     */
    public function getTotalQuantityAttribute()
    {
        return $this->items->sum('quantity');
    }

    /**
     * Get the total price of all items in the cart.
     */
    public function getTotalPriceAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }

    /**
     * Check if cart is empty.
     */
    public function isEmpty()
    {
        return $this->items->isEmpty();
    }

    /**
     * Clear all items from the cart.
     */
    public function clear()
    {
        $this->items()->delete();
    }

    /**
     * Add a product to the cart.
     */
    public function addProduct($productId, $quantity = 1)
    {
        $existingItem = $this->items()->where('product_id', $productId)->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $quantity);
            return $existingItem;
        }

        return $this->items()->create([
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
    }

    /**
     * Remove a product from the cart.
     */
    public function removeProduct($productId)
    {
        return $this->items()->where('product_id', $productId)->delete();
    }

    /**
     * Update product quantity in cart.
     */
    public function updateProductQuantity($productId, $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeProduct($productId);
        }

        return $this->items()->where('product_id', $productId)->update([
            'quantity' => $quantity
        ]);
    }
}
