<div>
    {{-- Product List Component --}}
    {{-- TODO: Implement product listing UI with Tailwind CSS --}}

    {{-- Search and Filter Section --}}
    <div class="mb-6">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search products..."
            class="w-full px-4 py-2 border rounded-lg">
    </div>

    {{-- Category Filter --}}
    <div class="mb-6">
        <select wire:model.live="categoryId" class="px-4 py-2 border rounded-lg">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Products Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="border rounded-lg p-4 shadow-sm">
                <h3 class="font-semibold text-lg">{{ $product->name }}</h3>
                <p class="text-gray-600 text-sm mt-2">{{ Str::limit($product->description, 100) }}</p>
                <p class="text-lg font-bold mt-2">${{ number_format($product->price, 2) }}</p>
                <p class="text-sm text-gray-500">Stock: {{ $product->stock }}</p>
                <a href="{{ route('products.show', $product->slug) }}"
                   class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    View Details
                </a>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 py-8">
                No products found.
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
