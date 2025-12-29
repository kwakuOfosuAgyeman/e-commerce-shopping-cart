<?php

namespace App\Http\Controllers;

use App\Enums\ProductStatus;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\RemoveFromCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:100,1')->only(['add', 'update']);
    }

    // Display the cart
    public function index(Request $request)
    {
        $cart = $this->getCart($request);
        return view('cart', compact('cart'));
    }

    // Add item to cart
    public function add(AddToCartRequest $request)
    {
        $validated = $request->validated();

        $product = Product::findOrFail($validated['product_id']);

        // Check if product is active
        if ($product->status !== ProductStatus::ACTIVE) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available'
            ], 400);
        }

        if ($product->stock < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available'
            ], 400);
        }

        $this->addItemToCart($product, $validated['quantity']);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart'
        ]);
    }

    // Update item quantity
    public function update(UpdateCartRequest $request)
    {
        $validated = $request->validated();

        $product = Product::findOrFail($validated['product_id']);

        // Check if product is active
        if ($product->status !== ProductStatus::ACTIVE) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available'
            ], 400);
        }

        if ($product->stock < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available'
            ], 400);
        }

        $this->updateCartItem($product, $validated['quantity']);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully'
        ]);
    }

    // Remove item from cart
    public function remove(RemoveFromCartRequest $request)
    {
        $validated = $request->validated();

        $this->removeCartItem($validated['product_id']);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart'
        ]);
    }

    // Clear the cart
    public function clear()
    {
        $this->clearCart();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }

    // Fetch the cart for the current user or guest
    private function getCart(Request $request)
    {
        $perPage = 5; // Number of items per page

        if (Auth::check()) {
            // Authenticated user: fetch cart items from database
            $cart = Cart::where('user_id', Auth::id())->first();

            if ($cart) {
                $cartItemsQuery = CartItem::with('product')
                    ->where('cart_id', $cart->id);
                $cartItems = $cartItemsQuery->paginate($perPage);
            } else {
                // Return empty pagination if no cart exists
                $cartItems = new LengthAwarePaginator(
                    [],
                    0,
                    $perPage,
                    1,
                    ['path' => $request->url()]
                );
            }
        } else {
            $cartItems = new LengthAwarePaginator(
                [],
                0,
                $perPage,
                1,
                ['path' => $request->url()]
            );
        }
        return $cartItems;
    }

    // Add an item to the cart
    private function addItemToCart($product, $quantity)
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $cartItem = $cart->items()->firstOrNew(['product_id' => $product->id]);
            $cartItem->quantity += $quantity;
            $cartItem->save();
        }
    }

    // Update an item's quantity in the cart
    private function updateCartItem($product, $quantity)
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if (!$cart) {
                return;
            }
            $cartItem = $cart->items()->where('product_id', $product->id)->first();

            if ($cartItem) {
                $cartItem->quantity = $quantity;
                $cartItem->save();
            }
        }
    }

    // Remove an item from the cart
    private function removeCartItem($productId)
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if (!$cart) {
                return;
            }
            $cart->items()->where('product_id', $productId)->delete();
        }
    }

    // Clear the entire cart
    private function clearCart()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if (!$cart) {
                return;
            }
            $cart->items()->delete();
        }
    }
}
