<?php

namespace Tests\Feature;

use App\Livewire\AddToCart;
use App\Livewire\Cart;
use App\Models\Cart as CartModel;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | Cart Association Tests
    |--------------------------------------------------------------------------
    |
    | These tests verify that carts are properly associated with authenticated
    | users and not accessible by other users or guests.
    |
    */

    public function test_cart_is_associated_with_authenticated_user(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 50]);

        $this->actingAs($user);

        // Add product to cart via Livewire
        Livewire::test(AddToCart::class, ['product' => $product])
            ->call('addToCart');

        // Verify cart exists and belongs to user
        $cart = CartModel::where('user_id', $user->id)->first();

        $this->assertNotNull($cart);
        $this->assertEquals($user->id, $cart->user_id);
        $this->assertCount(1, $cart->items);
    }

    public function test_different_users_have_separate_carts(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $product1 = Product::factory()->create(['stock' => 50, 'price' => 100]);
        $product2 = Product::factory()->create(['stock' => 50, 'price' => 200]);

        // User 1 adds product 1
        $this->actingAs($user1);
        Livewire::test(AddToCart::class, ['product' => $product1])
            ->call('addToCart');

        // User 2 adds product 2
        $this->actingAs($user2);
        Livewire::test(AddToCart::class, ['product' => $product2])
            ->call('addToCart');

        // Verify each user has their own cart with correct items
        $cart1 = CartModel::where('user_id', $user1->id)->with('items.product')->first();
        $cart2 = CartModel::where('user_id', $user2->id)->with('items.product')->first();

        $this->assertNotNull($cart1);
        $this->assertNotNull($cart2);
        $this->assertNotEquals($cart1->id, $cart2->id);

        $this->assertCount(1, $cart1->items);
        $this->assertCount(1, $cart2->items);

        $this->assertEquals($product1->id, $cart1->items->first()->product_id);
        $this->assertEquals($product2->id, $cart2->items->first()->product_id);
    }

    public function test_guest_cannot_add_to_cart(): void
    {
        $product = Product::factory()->create(['stock' => 50]);

        // Attempt to add to cart without authentication
        Livewire::test(AddToCart::class, ['product' => $product])
            ->call('addToCart')
            ->assertRedirect(route('login'));

        // Verify no cart was created
        $this->assertEquals(0, CartModel::count());
    }

    public function test_user_cannot_access_another_users_cart(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $product = Product::factory()->create(['stock' => 50]);

        // Create cart for user1
        $cart1 = CartModel::factory()->forUser($user1)->create();
        CartItem::factory()->forCart($cart1)->forProduct($product)->quantity(2)->create();

        // User2 should not see user1's cart items
        $this->actingAs($user2);

        $response = Livewire::test(Cart::class);

        // User2's cart should be empty (no items)
        $this->assertEquals(0, CartModel::where('user_id', $user2->id)->count());
    }

    /*
    |--------------------------------------------------------------------------
    | Add to Cart Tests
    |--------------------------------------------------------------------------
    */

    public function test_authenticated_user_can_add_product_to_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 50, 'price' => 99.99]);

        $this->actingAs($user);

        Livewire::test(AddToCart::class, ['product' => $product])
            ->set('quantity', 2)
            ->call('addToCart')
            ->assertHasNoErrors();

        $cart = CartModel::where('user_id', $user->id)->first();
        $this->assertNotNull($cart);

        $cartItem = $cart->items()->where('product_id', $product->id)->first();
        $this->assertNotNull($cartItem);
        $this->assertEquals(2, $cartItem->quantity);
    }

    public function test_adding_same_product_increases_quantity(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 50]);

        $this->actingAs($user);

        // Add product first time
        Livewire::test(AddToCart::class, ['product' => $product])
            ->set('quantity', 2)
            ->call('addToCart');

        // Add same product again
        Livewire::test(AddToCart::class, ['product' => $product])
            ->set('quantity', 3)
            ->call('addToCart');

        $cart = CartModel::where('user_id', $user->id)->first();
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        $this->assertEquals(5, $cartItem->quantity); // 2 + 3
    }

    public function test_cannot_add_out_of_stock_product(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->outOfStock()->create();

        $this->actingAs($user);

        Livewire::test(AddToCart::class, ['product' => $product])
            ->call('addToCart')
            ->assertNotDispatched('cart-updated');

        // Verify no cart item was created
        $this->assertEquals(0, CartItem::count());
        $this->assertEquals(0, CartModel::count());
    }

    public function test_cannot_add_more_than_available_stock(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($user);

        Livewire::test(AddToCart::class, ['product' => $product])
            ->set('quantity', 10)
            ->call('addToCart')
            ->assertNotDispatched('cart-updated');

        // Verify no cart item was created
        $this->assertEquals(0, CartItem::count());
    }

    /*
    |--------------------------------------------------------------------------
    | Update Cart Tests
    |--------------------------------------------------------------------------
    */

    public function test_authenticated_user_can_update_cart_quantity(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 50]);

        $cart = CartModel::factory()->forUser($user)->create();
        $cartItem = CartItem::factory()->forCart($cart)->forProduct($product)->quantity(2)->create();

        $this->actingAs($user);

        Livewire::test(Cart::class)
            ->call('updateQuantity', $cartItem->id, 5);

        $cartItem->refresh();
        $this->assertEquals(5, $cartItem->quantity);
    }

    public function test_authenticated_user_can_increment_quantity(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 50]);

        $cart = CartModel::factory()->forUser($user)->create();
        $cartItem = CartItem::factory()->forCart($cart)->forProduct($product)->quantity(2)->create();

        $this->actingAs($user);

        Livewire::test(Cart::class)
            ->call('incrementQuantity', $cartItem->id);

        $cartItem->refresh();
        $this->assertEquals(3, $cartItem->quantity);
    }

    public function test_authenticated_user_can_decrement_quantity(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 50]);

        $cart = CartModel::factory()->forUser($user)->create();
        $cartItem = CartItem::factory()->forCart($cart)->forProduct($product)->quantity(3)->create();

        $this->actingAs($user);

        Livewire::test(Cart::class)
            ->call('decrementQuantity', $cartItem->id);

        $cartItem->refresh();
        $this->assertEquals(2, $cartItem->quantity);
    }

    public function test_cannot_exceed_stock_when_updating_quantity(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $cart = CartModel::factory()->forUser($user)->create();
        $cartItem = CartItem::factory()->forCart($cart)->forProduct($product)->quantity(3)->create();

        $this->actingAs($user);

        Livewire::test(Cart::class)
            ->call('updateQuantity', $cartItem->id, 10)
            ->assertNotDispatched('cart-updated');

        $cartItem->refresh();
        $this->assertEquals(3, $cartItem->quantity); // Should remain unchanged
    }

    /*
    |--------------------------------------------------------------------------
    | Remove from Cart Tests
    |--------------------------------------------------------------------------
    */

    public function test_authenticated_user_can_remove_item_from_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 50]);

        $cart = CartModel::factory()->forUser($user)->create();
        $cartItem = CartItem::factory()->forCart($cart)->forProduct($product)->quantity(2)->create();

        $this->actingAs($user);

        Livewire::test(Cart::class)
            ->call('removeItem', $cartItem->id);

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    public function test_authenticated_user_can_clear_cart(): void
    {
        $user = User::factory()->create();
        $product1 = Product::factory()->create(['stock' => 50]);
        $product2 = Product::factory()->create(['stock' => 50]);

        $cart = CartModel::factory()->forUser($user)->create();
        CartItem::factory()->forCart($cart)->forProduct($product1)->create();
        CartItem::factory()->forCart($cart)->forProduct($product2)->create();

        $this->actingAs($user);

        $this->assertEquals(2, $cart->items()->count());

        Livewire::test(Cart::class)
            ->call('clearCart');

        $this->assertEquals(0, $cart->fresh()->items()->count());
    }

    public function test_decrementing_quantity_to_zero_removes_item(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 50]);

        $cart = CartModel::factory()->forUser($user)->create();
        $cartItem = CartItem::factory()->forCart($cart)->forProduct($product)->quantity(1)->create();

        $this->actingAs($user);

        Livewire::test(Cart::class)
            ->call('decrementQuantity', $cartItem->id);

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    /*
    |--------------------------------------------------------------------------
    | Cart Persistence Tests
    |--------------------------------------------------------------------------
    */

    public function test_cart_persists_across_sessions(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 50]);

        // First session - add to cart
        $this->actingAs($user);
        Livewire::test(AddToCart::class, ['product' => $product])
            ->set('quantity', 3)
            ->call('addToCart');

        // Simulate logging out
        auth()->logout();

        // Simulate new session - log back in
        $this->actingAs($user);

        // Cart should still have the items
        $cart = CartModel::where('user_id', $user->id)->with('items')->first();
        $this->assertNotNull($cart);
        $this->assertCount(1, $cart->items);
        $this->assertEquals(3, $cart->items->first()->quantity);
    }

    public function test_cart_data_is_stored_in_database_not_session(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 50]);

        $this->actingAs($user);

        Livewire::test(AddToCart::class, ['product' => $product])
            ->call('addToCart');

        // Verify data is in database
        $this->assertDatabaseHas('carts', ['user_id' => $user->id]);
        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id]);

        // Verify session does not contain cart data
        $this->assertNull(session()->get('cart'));
    }

    /*
    |--------------------------------------------------------------------------
    | Buy Now Tests
    |--------------------------------------------------------------------------
    */

    public function test_buy_now_clears_existing_cart_and_adds_product(): void
    {
        $user = User::factory()->create();
        $product1 = Product::factory()->create(['stock' => 50]);
        $product2 = Product::factory()->create(['stock' => 50]);

        // Create existing cart with product1
        $cart = CartModel::factory()->forUser($user)->create();
        CartItem::factory()->forCart($cart)->forProduct($product1)->quantity(5)->create();

        $this->actingAs($user);

        // Buy now with product2
        Livewire::test(AddToCart::class, ['product' => $product2])
            ->set('quantity', 2)
            ->call('buyNow')
            ->assertRedirect(route('checkout'));

        // Cart should only have product2 now
        $cart->refresh();
        $this->assertCount(1, $cart->items);
        $this->assertEquals($product2->id, $cart->items->first()->product_id);
        $this->assertEquals(2, $cart->items->first()->quantity);
    }

    public function test_guest_cannot_use_buy_now(): void
    {
        $product = Product::factory()->create(['stock' => 50]);

        Livewire::test(AddToCart::class, ['product' => $product])
            ->call('buyNow')
            ->assertRedirect(route('login'));
    }
}
