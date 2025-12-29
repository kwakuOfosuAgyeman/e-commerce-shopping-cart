<div class="min-h-screen bg-gray-50">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold text-white">Shopping Cart</h1>
            <p class="mt-2 text-indigo-100">Review and manage your items</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-start gap-3">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        @if($cartItems->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <!-- Header -->
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900">Cart Items ({{ $cartItems->count() }})</h2>
                                <button wire:click="clearCart"
                                        wire:confirm="Are you sure you want to clear your cart?"
                                        class="text-sm text-red-600 hover:text-red-700 font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Clear All
                                </button>
                            </div>
                        </div>

                        <!-- Items List -->
                        <div class="divide-y">
                            @foreach($cartItems as $item)
                                <div class="p-6 flex items-start gap-6 hover:bg-gray-50 transition-colors">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0 w-24 h-24 bg-gray-100 rounded-xl overflow-hidden">
                                        @if($item->product->primaryImage)
                                            <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}"
                                                 alt="{{ $item->product->name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                @if($item->product->brand)
                                                    <p class="text-xs font-medium text-indigo-600 mb-1">{{ $item->product->brand->name }}</p>
                                                @endif
                                                <h3 class="font-semibold text-gray-900 truncate">
                                                    <a href="{{ route('products.show', $item->product->slug) }}" wire:navigate class="hover:text-indigo-600">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </h3>
                                                <p class="mt-1 text-sm text-gray-500">${{ number_format($item->product->price, 2) }} each</p>
                                            </div>

                                            <!-- Item Total (Desktop) -->
                                            <div class="hidden sm:block text-right">
                                                <p class="text-lg font-bold text-gray-900">${{ number_format($item->quantity * $item->product->price, 2) }}</p>
                                            </div>
                                        </div>

                                        <!-- Quantity & Actions -->
                                        <div class="mt-4 flex items-center justify-between">
                                            <div class="flex items-center">
                                                <!-- Quantity Selector -->
                                                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                                    <button wire:click="decrementQuantity({{ $item->id }})"
                                                            class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors disabled:opacity-50"
                                                            @if($item->quantity <= 1) disabled @endif>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                        </svg>
                                                    </button>
                                                    <span class="w-12 h-10 flex items-center justify-center font-semibold text-gray-900 bg-gray-50">
                                                        {{ $item->quantity }}
                                                    </span>
                                                    <button wire:click="incrementQuantity({{ $item->id }})"
                                                            class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors disabled:opacity-50"
                                                            @if($item->quantity >= $item->product->stock) disabled @endif>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                    </button>
                                                </div>

                                                <!-- Stock Info -->
                                                <span class="ml-3 text-xs text-gray-500">{{ $item->product->stock }} available</span>
                                            </div>

                                            <!-- Remove Button -->
                                            <button wire:click="removeItem({{ $item->id }})"
                                                    wire:confirm="Remove this item from your cart?"
                                                    class="text-sm text-red-600 hover:text-red-700 font-medium flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Remove
                                            </button>
                                        </div>

                                        <!-- Item Total (Mobile) -->
                                        <div class="mt-3 sm:hidden">
                                            <p class="text-lg font-bold text-gray-900">${{ number_format($item->quantity * $item->product->price, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Continue Shopping -->
                    <div class="mt-6">
                        <a href="{{ route('products.index') }}" wire:navigate
                           class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Continue Shopping
                        </a>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden sticky top-24">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
                        </div>

                        <div class="p-6">
                            <!-- Summary Items -->
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                                    <span class="font-medium text-gray-900">${{ number_format($total, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium text-green-600">Free</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tax</span>
                                    <span class="font-medium text-gray-900">Calculated at checkout</span>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="mt-6 pt-6 border-t">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold text-gray-900">Total</span>
                                    <span class="text-2xl font-bold text-gray-900">${{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <!-- Checkout Button -->
                            <a href="{{ route('checkout') }}" wire:navigate
                               class="mt-6 w-full py-4 px-6 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/25 transition-all duration-200 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                                Proceed to Checkout
                            </a>

                            <!-- Security Badge -->
                            <div class="mt-6 flex items-center justify-center gap-2 text-xs text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Secure checkout
                            </div>
                        </div>
                    </div>

                    <!-- Promo Code (Optional) -->
                    <div class="mt-4 bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Have a promo code?</h3>
                            <div class="flex gap-2">
                                <input type="text" placeholder="Enter code"
                                       class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <button class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart State -->
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center max-w-2xl mx-auto">
                <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Your cart is empty</h3>
                <p class="mt-2 text-gray-500 max-w-md mx-auto">Looks like you haven't added any items to your cart yet. Start exploring our products!</p>
                <a href="{{ route('products.index') }}" wire:navigate
                   class="mt-6 inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Start Shopping
                </a>
            </div>
        @endif
    </div>
</div>
