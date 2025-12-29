<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Get user's orders
     */
    public function getUserOrders()
    {
        return Order::with(['items.product', 'payment'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    /**
     * Track specific order
     */
    public function trackOrder($id)
    {
        return Order::with(['items.product', 'payment', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
    }

    /**
     * Cancel order
     */
    public function cancelOrder($id, $request)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return ['success' => false, 'message' => 'Order not found'];
        }

        if (!$order->canBeCancelled()) {
            return ['success' => false, 'message' => 'This order cannot be cancelled'];
        }

        DB::beginTransaction();

        try {
            $order->update(['status' => OrderStatus::CANCELLED]);

            // Restore product stock
            foreach ($order->items as $item) {
                $product = Product::where('id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            // Update payment status if exists
            if ($order->payment) {
                $order->payment->update(['status' => PaymentStatus::CANCELLED]);
            }

            DB::commit();

            return ['success' => true, 'message' => 'Order cancelled successfully', 'order' => $order];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to cancel order', [
                'order_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return ['success' => false, 'message' => 'Failed to cancel order. Please try again.'];
        }
    }

    /**
     * Place order from checkout
     */
    public function placeOrder(array $validated = [])
    {
        if (!Auth::check()) {
            return [
                'success' => false,
                'message' => 'You must be logged in to place an order',
                'code' => 401
            ];
        }

        DB::beginTransaction();

        try {
            // Get cart items
            $cart = Cart::with(['items.product'])
                ->where('user_id', Auth::id())
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'Your cart is empty',
                    'code' => 400
                ];
            }

            // Calculate totals and verify stock
            $subtotal = 0;
            $itemsData = [];

            foreach ($cart->items as $cartItem) {
                $product = Product::where('id', $cartItem->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new \Exception('Product not found');
                }

                if ($product->status !== ProductStatus::ACTIVE) {
                    throw new \Exception("Product '{$product->name}' is no longer available");
                }

                if ($product->stock < $cartItem->quantity) {
                    throw new \Exception("Insufficient stock for '{$product->name}'. Available: {$product->stock}");
                }

                $price = $product->sale_price ?? $product->price;
                $subtotal += $price * $cartItem->quantity;

                $itemsData[] = [
                    'product' => $product,
                    'quantity' => $cartItem->quantity,
                    'price' => $price,
                ];
            }

            // Generate order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $orderNumber,
                'total_amount' => $subtotal,
                'shipping_cost' => 0,
                'notes' => $validated['notes'] ?? null,
                'status' => OrderStatus::PENDING,
            ]);

            // Create order items and decrement stock
            foreach ($itemsData as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $itemData['product']->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                ]);

                // Decrement stock
                $itemData['product']->decrement('stock', $itemData['quantity']);
            }

            // Create payment record (simulated - always pending)
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $subtotal,
                'payment_method' => $validated['payment_method'] ?? 'cash_on_delivery',
                'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
                'status' => PaymentStatus::PENDING,
            ]);

            // Clear the cart
            $cart->items()->delete();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Order placed successfully',
                'order' => $order->load(['items.product', 'payment']),
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'code' => 201
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to place order', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => 400
            ];
        }
    }
}
