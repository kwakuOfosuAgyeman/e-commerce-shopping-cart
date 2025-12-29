<div>
    {{-- Add to Cart Component --}}

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 mt-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mt-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-6">
        {{-- Quantity Selector --}}
        <div class="flex items-center space-x-4 mb-4">
            <span class="text-gray-700">Quantity:</span>
            <div class="flex items-center space-x-2">
                <button wire:click="decrementQuantity"
                    class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300"
                    @if($quantity <= 1) disabled @endif>
                    -
                </button>
                <span class="w-12 text-center font-semibold">{{ $quantity }}</span>
                <button wire:click="incrementQuantity"
                    class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300"
                    @if($quantity >= $product->stock) disabled @endif>
                    +
                </button>
            </div>
        </div>

        {{-- Add to Cart Button --}}
        <button wire:click="addToCart"
            class="w-full bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-50">
            <span wire:loading.remove wire:target="addToCart">Add to Cart</span>
            <span wire:loading wire:target="addToCart">Adding...</span>
        </button>
    </div>
</div>
