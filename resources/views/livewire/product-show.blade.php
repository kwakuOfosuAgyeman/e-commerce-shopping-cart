<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" wire:navigate class="text-gray-500 hover:text-indigo-600 transition-colors">Home</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('products.index') }}" wire:navigate class="text-gray-500 hover:text-indigo-600 transition-colors">Products</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900 font-medium truncate">{{ $product->name }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                <!-- Product Images -->
                <div class="relative bg-gray-100">
                    <!-- Main Image -->
                    <div class="aspect-square relative overflow-hidden">
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover"
                                 id="main-product-image">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                <svg class="w-32 h-32 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- Stock Badge -->
                        @if($product->stock <= 0)
                            <div class="absolute top-4 left-4">
                                <span class="px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-lg shadow-lg">Sold Out</span>
                            </div>
                        @elseif($product->stock <= 5)
                            <div class="absolute top-4 left-4">
                                <span class="px-4 py-2 bg-orange-500 text-white text-sm font-semibold rounded-lg shadow-lg">Only {{ $product->stock }} left!</span>
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnail Gallery -->
                    @if($product->images && $product->images->count() > 1)
                        <div class="p-4 border-t bg-white">
                            <div class="flex gap-3 overflow-x-auto pb-2">
                                @foreach($product->images as $image)
                                    <button class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 border-transparent hover:border-indigo-500 focus:border-indigo-500 transition-colors">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="p-6 lg:p-10 flex flex-col">
                    <!-- Brand & Category -->
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        @if($product->brand)
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full">{{ $product->brand->name }}</span>
                        @endif
                        @foreach($product->categories as $category)
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">{{ $category->name }}</span>
                        @endforeach
                    </div>

                    <!-- Product Name -->
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">{{ $product->name }}</h1>

                    <!-- SKU -->
                    @if($product->sku)
                        <p class="mt-2 text-sm text-gray-500">SKU: {{ $product->sku }}</p>
                    @endif

                    <!-- Price -->
                    <div class="mt-6 flex items-baseline gap-3">
                        <span class="text-3xl md:text-4xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                        @if($product->currency && $product->currency !== 'USD')
                            <span class="text-sm text-gray-500">{{ $product->currency }}</span>
                        @endif
                    </div>

                    <!-- Stock Status -->
                    <div class="mt-4 flex items-center gap-2">
                        @if($product->isInStock())
                            <span class="flex items-center gap-1.5 text-green-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">In Stock</span>
                            </span>
                            <span class="text-sm text-gray-500">({{ $product->stock }} available)</span>
                        @else
                            <span class="flex items-center gap-1.5 text-red-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">Out of Stock</span>
                            </span>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="mt-6 pt-6 border-t">
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-3">Description</h3>
                        <div class="prose prose-sm text-gray-600 max-w-none">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>

                    <!-- Add to Cart Component -->
                    <div class="mt-auto pt-6">
                        @if($product->isInStock())
                            <livewire:add-to-cart :product="$product" />
                        @else
                            <button disabled
                                    class="w-full py-4 px-6 bg-gray-300 text-gray-500 font-semibold rounded-xl cursor-not-allowed">
                                Out of Stock
                            </button>
                            <p class="mt-3 text-center text-sm text-gray-500">This item is currently unavailable</p>
                        @endif
                    </div>

                    <!-- Trust Badges -->
                    <div class="mt-8 pt-6 border-t">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="w-10 h-10 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-600 font-medium">Quality Guaranteed</p>
                            </div>
                            <div class="text-center">
                                <div class="w-10 h-10 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-600 font-medium">Fast Shipping</p>
                            </div>
                            <div class="text-center">
                                <div class="w-10 h-10 mx-auto bg-purple-100 rounded-full flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-600 font-medium">Secure Checkout</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section (Placeholder) -->
        @if($product->categories->isNotEmpty())
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">You May Also Like</h2>
                <p class="text-gray-500">Explore more products in {{ $product->categories->first()->name }}</p>
                <a href="{{ route('products.index', ['category' => $product->categories->first()->id]) }}" wire:navigate
                   class="inline-flex items-center mt-4 text-indigo-600 hover:text-indigo-700 font-medium">
                    Browse {{ $product->categories->first()->name }}
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</div>
