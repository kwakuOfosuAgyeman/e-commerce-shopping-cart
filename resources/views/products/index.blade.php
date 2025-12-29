<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $pageTitle }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Filters Sidebar -->
                <div class="lg:w-64 flex-shrink-0">
                    <form method="GET" action="{{ route('products.index') }}" class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                        <!-- Preserve section if set -->
                        @if($section !== 'all')
                            <input type="hidden" name="section" value="{{ $section }}">
                        @endif

                        <!-- Search -->
                        <div>
                            <label for="query" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" name="query" id="query" value="{{ request('query') }}"
                                   placeholder="Search products..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Categories -->
                        @if($categories->count() > 0)
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <select name="category" id="category"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Brands -->
                        @if($brands->count() > 0)
                            <div>
                                <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                                <select name="brand" id="brand"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Brands</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                            <div class="flex gap-2">
                                <input type="number" name="min_price" placeholder="Min"
                                       value="{{ request('min_price') }}"
                                       min="0" step="0.01"
                                       class="w-1/2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <input type="number" name="max_price" placeholder="Max"
                                       value="{{ request('max_price') }}"
                                       min="0" step="0.01"
                                       class="w-1/2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- In Stock Only -->
                        <div class="flex items-center">
                            <input type="checkbox" name="in_stock" id="in_stock" value="1"
                                   {{ request('in_stock') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="in_stock" class="ml-2 text-sm text-gray-700">In Stock Only</label>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                            <select name="sort" id="sort"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Apply Filters
                        </button>

                        <!-- Clear Filters -->
                        @if(request()->hasAny(['query', 'category', 'brand', 'min_price', 'max_price', 'in_stock', 'sort']))
                            <a href="{{ route('products.index', $section !== 'all' ? ['section' => $section] : []) }}"
                               class="block text-center text-sm text-gray-600 hover:text-gray-800">
                                Clear Filters
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Products Grid -->
                <div class="flex-1">
                    <!-- Section Tabs -->
                    <div class="mb-6 flex gap-2 flex-wrap">
                        <a href="{{ route('products.index') }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $section === 'all' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                            All Products
                        </a>
                        <a href="{{ route('products.index', ['section' => 'top-deals']) }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $section === 'top-deals' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                            Top Deals
                        </a>
                        <a href="{{ route('products.index', ['section' => 'new-arrivals']) }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $section === 'new-arrivals' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                            New Arrivals
                        </a>
                    </div>

                    <!-- Results Count -->
                    <div class="mb-4 text-sm text-gray-600">
                        Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                    </div>

                    @if($products->count() > 0)
                        <!-- Products Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($products as $product)
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow group">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <div class="aspect-square bg-gray-100 flex items-center justify-center relative">
                                            @if($product->primaryImage)
                                                <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                                     alt="{{ $product->name }}"
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                            @else
                                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            @endif
                                            @if($product->stock <= 0)
                                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                                    <span class="bg-red-600 text-white px-3 py-1 rounded text-sm font-medium">Out of Stock</span>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                    <div class="p-4">
                                        @if($product->categories->count() > 0)
                                            <div class="mb-2">
                                                <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                                    {{ $product->categories->first()->name }}
                                                </span>
                                            </div>
                                        @endif
                                        <a href="{{ route('products.show', $product->slug) }}"
                                           class="block font-medium text-gray-900 hover:text-blue-600 truncate">
                                            {{ $product->name }}
                                        </a>
                                        @if($product->brand)
                                            <p class="text-sm text-gray-500 mt-1">{{ $product->brand->name }}</p>
                                        @endif
                                        <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                                        <div class="mt-3 flex items-center justify-between">
                                            <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                            @if($product->stock > 0)
                                                <span class="text-xs text-green-600 font-medium">{{ $product->stock }} in stock</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('products.show', $product->slug) }}"
                                           class="mt-3 block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $products->withQueryString()->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No products found</h3>
                            <p class="mt-2 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                            <a href="{{ route('products.index') }}"
                               class="mt-6 inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                Clear All Filters
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
