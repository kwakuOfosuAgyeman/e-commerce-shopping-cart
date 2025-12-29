<?php

namespace App\Observers;

use App\Jobs\SendLowStockNotification;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     * Check if stock has fallen below the low stock threshold.
     */
    public function updated(Product $product): void
    {
        // Check if stock was changed
        if (!$product->wasChanged('stock')) {
            return;
        }

        $this->checkLowStock($product);
    }

    /**
     * Check if product stock is low and dispatch notification.
     */
    protected function checkLowStock(Product $product): void
    {
        $threshold = $product->low_stock_threshold ?? 10;
        $currentStock = $product->stock;
        $previousStock = $product->getOriginal('stock');

        // Only trigger if stock just crossed below threshold
        // (was above threshold before, now at or below)
        if ($previousStock > $threshold && $currentStock <= $threshold) {
            Log::info('Low stock detected, dispatching notification', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'previous_stock' => $previousStock,
                'current_stock' => $currentStock,
                'threshold' => $threshold,
            ]);

            SendLowStockNotification::dispatch($product);
        }
    }
}
