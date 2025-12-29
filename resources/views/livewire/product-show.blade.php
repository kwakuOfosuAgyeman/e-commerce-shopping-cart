<div>
    {{-- Product Detail Component --}}
    {{-- TODO: Enhance product detail UI with Tailwind CSS --}}

    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Product Image --}}
            <div class="bg-gray-100 rounded-lg p-4">
                {{-- TODO: Add product image --}}
                <div class="aspect-square bg-gray-200 rounded flex items-center justify-center">
                    <span class="text-gray-400">Product Image</span>
                </div>
            </div>

            {{-- Product Info --}}
            <div>
                <h1 class="text-3xl font-bold">{{ $product->name }}</h1>

                <p class="text-2xl font-bold mt-4">${{ number_format($product->price, 2) }}</p>

                <p class="mt-4 text-gray-600">{{ $product->description }}</p>

                <div class="mt-4">
                    @if($product->isInStock())
                        <span class="text-green-600">In Stock ({{ $product->stock }} available)</span>
                    @else
                        <span class="text-red-600">Out of Stock</span>
                    @endif
                </div>

                {{-- Add to Cart --}}
                @if($product->isInStock())
                    <livewire:add-to-cart :product="$product" />
                @endif
            </div>
        </div>
    </div>
</div>
