<div>
    {{-- Shopping Cart Component --}}
    {{-- TODO: Enhance cart UI with Tailwind CSS --}}

    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Shopping Cart</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($cartItems->count() > 0)
            <div class="space-y-4">
                @foreach($cartItems as $item)
                    <div class="flex items-center justify-between border rounded-lg p-4">
                        <div class="flex-1">
                            <h3 class="font-semibold">{{ $item->product->name }}</h3>
                            <p class="text-gray-600">${{ number_format($item->product->price, 2) }} each</p>
                        </div>

                        <div class="flex items-center space-x-4">
                            {{-- Quantity Controls --}}
                            <div class="flex items-center space-x-2">
                                <button wire:click="decrementQuantity({{ $item->id }})"
                                    class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">-</button>
                                <span class="w-12 text-center">{{ $item->quantity }}</span>
                                <button wire:click="incrementQuantity({{ $item->id }})"
                                    class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">+</button>
                            </div>

                            {{-- Item Total --}}
                            <div class="w-24 text-right font-semibold">
                                ${{ number_format($item->quantity * $item->product->price, 2) }}
                            </div>

                            {{-- Remove Button --}}
                            <button wire:click="removeItem({{ $item->id }})"
                                class="text-red-500 hover:text-red-700">
                                Remove
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Cart Summary --}}
            <div class="mt-8 border-t pt-6">
                <div class="flex justify-between text-xl font-bold">
                    <span>Total:</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>

                <div class="mt-6 flex space-x-4">
                    <button wire:click="clearCart"
                        class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        Clear Cart
                    </button>
                    <a href="{{ route('checkout') }}"
                       class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        @else
            <div class="text-center py-12 text-gray-500">
                <p class="text-xl">Your cart is empty</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block text-blue-500 hover:underline">
                    Continue Shopping
                </a>
            </div>
        @endif
    </div>
</div>
