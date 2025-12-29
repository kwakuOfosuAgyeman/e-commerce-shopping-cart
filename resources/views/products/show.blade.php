<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700">Products</a>
            <span class="text-gray-400">/</span>
            @if($product->categories->count() > 0)
                <a href="{{ route('products.index', ['category' => $product->categories->first()->id]) }}"
                   class="text-gray-500 hover:text-gray-700">
                    {{ $product->categories->first()->name }}
                </a>
                <span class="text-gray-400">/</span>
            @endif
            <span class="text-gray-900 font-medium">{{ $product->name }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Product Detail Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6 lg:p-8">
                    <!-- Product Images -->
                    <div>
                        <!-- Main Image -->
                        <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden mb-4">
                            @if($product->primaryImage)
                                <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover"
                                     id="main-product-image">
                            @else
                                <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            @endif
                        </div>

                        <!-- Thumbnail Images -->
                        @if($product->images && $product->images->count() > 1)
                            <div class="grid grid-cols-5 gap-2">
                                @foreach($product->images as $image)
                                    <button type="button"
                                            onclick="document.getElementById('main-product-image').src='{{ asset('storage/' . $image->image_path) }}'"
                                            class="aspect-square bg-gray-100 rounded overflow-hidden border-2 border-transparent hover:border-blue-500 focus:border-blue-500 transition-colors">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div>
                        <!-- Categories -->
                        @if($product->categories->count() > 0)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach($product->categories as $category)
                                    <a href="{{ route('products.index', ['category' => $category->id]) }}"
                                       class="text-xs bg-blue-50 text-blue-600 px-3 py-1 rounded-full hover:bg-blue-100 transition-colors">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <!-- Product Name -->
                        <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>

                        <!-- Brand -->
                        @if($product->brand)
                            <p class="text-gray-500 mt-2">by <span class="font-medium">{{ $product->brand->name }}</span></p>
                        @endif

                        <!-- SKU -->
                        @if($product->sku)
                            <p class="text-sm text-gray-400 mt-1">SKU: {{ $product->sku }}</p>
                        @endif

                        <!-- Price -->
                        <div class="mt-6">
                            <span class="text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                            @if($product->currency && $product->currency !== 'USD')
                                <span class="text-sm text-gray-500 ml-2">{{ $product->currency }}</span>
                            @endif
                        </div>

                        <!-- Stock Status -->
                        <div class="mt-4">
                            @if($product->isInStock())
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        In Stock
                                    </span>
                                    <span class="text-sm text-gray-500">({{ $product->stock }} available)</span>
                                </div>
                                @if($product->isLowStock())
                                    <p class="text-sm text-orange-600 mt-2">Only {{ $product->stock }} left - order soon!</p>
                                @endif
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Out of Stock
                                </span>
                            @endif
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                            <div class="prose prose-sm text-gray-600 max-w-none">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>

                        <!-- Add to Cart -->
                        <div class="mt-8 pt-6 border-t">
                            @if($product->isInStock())
                                @auth
                                    <livewire:add-to-cart :product="$product" />
                                @else
                                    <div class="space-y-3">
                                        <a href="{{ route('login') }}"
                                           class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                            Login to Add to Cart
                                        </a>
                                        <p class="text-sm text-gray-500 text-center">
                                            Don't have an account?
                                            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800">Register here</a>
                                        </p>
                                    </div>
                                @endauth
                            @else
                                <button disabled
                                        class="w-full bg-gray-300 text-gray-500 font-semibold py-3 px-6 rounded-lg cursor-not-allowed">
                                    Out of Stock
                                </button>
                            @endif
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-6 flex gap-4">
                            <a href="{{ route('products.index') }}"
                               class="flex-1 text-center border border-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg hover:bg-gray-50 transition-colors">
                                Continue Shopping
                            </a>
                            @auth
                                <a href="{{ route('cart') }}"
                                   class="flex-1 text-center border border-blue-600 text-blue-600 font-medium py-2 px-4 rounded-lg hover:bg-blue-50 transition-colors">
                                    View Cart
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow group">
                                <a href="{{ route('products.show', $relatedProduct->slug) }}">
                                    <div class="aspect-square bg-gray-100 flex items-center justify-center relative">
                                        @if($relatedProduct->primaryImage)
                                            <img src="{{ asset('storage/' . $relatedProduct->primaryImage->image_path) }}"
                                                 alt="{{ $relatedProduct->name }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                        @else
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        @endif
                                        @if($relatedProduct->stock <= 0)
                                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                                <span class="bg-red-600 text-white px-3 py-1 rounded text-sm font-medium">Out of Stock</span>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                                <div class="p-4">
                                    <a href="{{ route('products.show', $relatedProduct->slug) }}"
                                       class="block font-medium text-gray-900 hover:text-blue-600 truncate">
                                        {{ $relatedProduct->name }}
                                    </a>
                                    @if($relatedProduct->brand)
                                        <p class="text-sm text-gray-500 mt-1">{{ $relatedProduct->brand->name }}</p>
                                    @endif
                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="text-lg font-bold text-gray-900">${{ number_format($relatedProduct->price, 2) }}</span>
                                        @if($relatedProduct->stock > 0)
                                            <span class="text-xs text-green-600">In Stock</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
