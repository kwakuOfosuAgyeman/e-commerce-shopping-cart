<x-layouts.app>
    <div class="min-h-screen bg-gray-50">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl md:text-4xl font-bold text-white">Checkout</h1>
                <p class="mt-2 text-indigo-100">Complete your order securely</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-center">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-600 text-white font-semibold">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="ml-2 text-sm font-medium text-indigo-600">Cart</span>
                    </div>
                    <div class="w-16 h-0.5 mx-2 bg-indigo-600"></div>
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-600 text-white font-semibold">
                            2
                        </div>
                        <span class="ml-2 text-sm font-medium text-indigo-600">Checkout</span>
                    </div>
                    <div class="w-16 h-0.5 mx-2 bg-gray-300"></div>
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-500 font-semibold">
                            3
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Confirmation</span>
                    </div>
                </div>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Order Form -->
                <div class="lg:col-span-2 space-y-6">
                    <form action="{{ route('order.place') }}" method="POST" id="checkout-form">
                        @csrf

                        <!-- Payment Method -->
                        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b bg-gray-50">
                                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    Payment Method
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
                                    <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-gray-50 transition-all {{ old('payment_method', 'cash_on_delivery') === 'cash_on_delivery' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                                        <input type="radio" name="payment_method" value="cash_on_delivery"
                                               class="h-5 w-5 text-indigo-600 focus:ring-indigo-500"
                                               {{ old('payment_method', 'cash_on_delivery') === 'cash_on_delivery' ? 'checked' : '' }}>
                                        <div class="ml-4 flex-1">
                                            <div class="flex items-center justify-between">
                                                <span class="block text-sm font-semibold text-gray-900">Cash on Delivery</span>
                                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            </div>
                                            <span class="block text-sm text-gray-500 mt-1">Pay when you receive your order</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-gray-50 transition-all {{ old('payment_method') === 'card' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                                        <input type="radio" name="payment_method" value="card"
                                               class="h-5 w-5 text-indigo-600 focus:ring-indigo-500"
                                               {{ old('payment_method') === 'card' ? 'checked' : '' }}>
                                        <div class="ml-4 flex-1">
                                            <div class="flex items-center justify-between">
                                                <span class="block text-sm font-semibold text-gray-900">Credit/Debit Card</span>
                                                <div class="flex gap-2">
                                                    <div class="w-10 h-6 bg-blue-600 rounded flex items-center justify-center">
                                                        <span class="text-white text-xs font-bold">VISA</span>
                                                    </div>
                                                    <div class="w-10 h-6 bg-red-500 rounded flex items-center justify-center">
                                                        <span class="text-white text-xs font-bold">MC</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="block text-sm text-gray-500 mt-1">Secure payment (demo mode)</span>
                                        </div>
                                    </label>
                                </div>
                                @error('payment_method')
                                    <p class="mt-3 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="bg-white rounded-2xl shadow-sm overflow-hidden mt-6">
                            <div class="px-6 py-4 border-b bg-gray-50">
                                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Order Notes
                                </h2>
                            </div>
                            <div class="p-6">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Special Instructions (Optional)
                                </label>
                                <textarea name="notes" id="notes" rows="4"
                                          class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                                          placeholder="Any special instructions for your order...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500">Max 500 characters</p>
                            </div>
                        </div>
                    </form>

                    <!-- Back to Cart -->
                    <div class="mt-6">
                        <a href="{{ route('cart') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back to Cart
                        </a>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden sticky top-24">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
                        </div>

                        <div class="p-6">
                            <!-- Cart Items -->
                            <div class="space-y-4 max-h-64 overflow-y-auto">
                                @foreach($cartItems as $item)
                                    <div class="flex items-center gap-4">
                                        <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                                            @if($item->product->primaryImage)
                                                <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $item->product->name }}</p>
                                            <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-900">${{ number_format($item->quantity * $item->product->price, 2) }}</p>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Totals -->
                            <div class="mt-6 pt-6 border-t space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium text-gray-900">${{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium text-green-600">Free</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tax</span>
                                    <span class="font-medium text-gray-900">$0.00</span>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="mt-6 pt-6 border-t">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold text-gray-900">Total</span>
                                    <span class="text-2xl font-bold text-gray-900">${{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <!-- Place Order Button -->
                            <button type="submit" form="checkout-form"
                                    class="mt-6 w-full py-4 px-6 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/25 transition-all duration-200 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Place Order
                            </button>

                            <!-- Security Badges -->
                            <div class="mt-6 pt-6 border-t">
                                <div class="flex items-center justify-center gap-4 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        SSL Secure
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        Safe Checkout
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
